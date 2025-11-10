<?php

namespace App\Http\Controllers;

use App\Models\Nft;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class UserNftController extends Controller
{
    public function mint(Request $request, CloudinaryService $cloudinaryService)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'image_file' => 'required|file|mimes:jpeg,jpg,png,gif|max:5120', // max 5MB
                'category' => 'required|string|in:art,collectibles,music,photography',
            ]);

            $user = Auth::user();

            // Check user balance
            if ($user->balance < 50) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance to mint NFT. You need at least $50.',
                ], 422);
            }

            DB::beginTransaction();

            $file = $request->file('image_file');

            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file uploaded.',
                ], 400);
            }

            // Upload image to Cloudinary
            $uploadedFileUrl = $cloudinaryService->uploadFile($file->getRealPath());

            if (!$uploadedFileUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'Upload to Cloudinary failed. Try again later.',
                ], 500);
            }

            // Create NFT
            $nft = $user->nfts()->create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'image_url' => $uploadedFileUrl,
                'category' => $validated['category'],
                'approved' => true,
                'on_sale' => true,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'NFT minted successfully.',
                'data' => $nft,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function showMintForm()
    {
        $user = Auth::user();

        // Optionally, pass user balance to the view
        return view('dashboard.user.nft_mint', [
            'balance' => $user->balance,
        ]);
    }


    public function marketplace(Request $request)
    {
        $category = $request->query('category', 'all');

        $query = Nft::where('approved', true)
                     ->where('on_sale', true);

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $nfts = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('dashboard.user.nft-market', compact('nfts', 'category'));
    }

    public function show($id)
    {
        $user = auth('web')->user() ?? auth('admin')->user();

        $nft = Nft::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('approved', true)
                    ->where('on_sale', true)
                    ->orWhere(function ($q) use ($user) {
                        $q->where('owner_id', $user->id)
                            ->where('owner_type', get_class($user));
                    });
            })
            ->firstOrFail();

        return view('dashboard.user.nft-show', compact('nft'));
    }


    public function myNfts()
    {
        $user = Auth::user();

        // Get NFTs owned by the user (created or purchased)
        $nfts = Nft::where('owner_type', get_class($user))
                    ->where('owner_id', $user->id)
                    ->get();

        return view('dashboard.user.my-nfts', compact('nfts'));
    }

    public function buy(Nft $nft)
    {
        $user = auth()->user();

        // 🛑 Prevent buying your own NFT
        if ($nft->owner_type === get_class($user) && $nft->owner_id === $user->id) {
            return response()->json(['success' => false, 'message' => 'You already own this NFT.'], 400);
        }

        // 🛑 Ensure NFT is for sale
        if (!$nft->on_sale) {
            return response()->json(['success' => false, 'message' => 'This NFT is not for sale.'], 400);
        }

        // 1️⃣ Get ETH-USD conversion rate
        try {
            $response = Http::get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => 'ethereum',
                'vs_currencies' => 'usd'
            ]);
            $ethToUsdRate = $response->json()['ethereum']['usd'] ?? 3500; // fallback
        } catch (\Exception $e) {
            $ethToUsdRate = 3500;
        }

        // 2️⃣ Convert price ETH → USD
        $priceUsd = $nft->price * $ethToUsdRate;

        // 3️⃣ Check buyer’s balance (stored in users table)
        if ($user->balance < $priceUsd) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance. Please top up your wallet.',
                'price_usd' => round($priceUsd, 2),
                'user_balance' => round($user->balance, 2)
            ], 402);
        }

        DB::beginTransaction();

        try {
            // 4️⃣ Deduct from buyer
            $user->decrement('balance', $priceUsd);

            // 5️⃣ Credit seller if seller is a User (not Admin)
            if ($nft->owner_type === 'App\\Models\\User') {
                $seller = app($nft->owner_type)::find($nft->owner_id);
                if ($seller) {
                    $seller->increment('balance', $priceUsd);
                }
            }

            // 6️⃣ Transfer ownership
            $nft->update([
                'owner_type' => get_class($user),
                'owner_id' => $user->id,
                'on_sale' => false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'NFT purchased successfully!',
                'data' => [
                    'nft' => $nft,
                    'price_usd' => round($priceUsd, 2),
                    'new_balance' => round($user->fresh()->balance, 2),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transaction failed.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

}
