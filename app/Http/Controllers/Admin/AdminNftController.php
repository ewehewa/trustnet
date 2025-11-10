<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nft;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminNftController extends Controller
{
    public function mint(Request $request, CloudinaryService $cloudinaryService)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'image_file' => 'required|file|mimes:jpeg,jpg,png,gif|max:5120', // 5MB max
                'category' => 'required|string|in:art,collectibles,music,photography',
            ]);

            $admin = Auth::guard('admin')->user();

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

            // Create NFT: Admin minting auto-approved and on sale
            $nft = $admin->nfts()->create([
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
                'message' => 'NFT minted successfully by Admin.',
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

    public function create()
    {
        return view('dashboard.admin.add_nft'); // points to resources/views/admin/nfts/create.blade.php
    }

    public function index()
    {
        // Get all NFTs created by admins (or all NFTs)
        $nfts = \App\Models\Nft::where('owner_type', 'App\Models\Admin')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.admin.nfts_index', compact('nfts'));
    }


}
