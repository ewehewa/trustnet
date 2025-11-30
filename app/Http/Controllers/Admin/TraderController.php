<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\CloudinaryService;

class TraderController extends Controller
{
    public function showTradersForm()
    {
        return view('dashboard.admin.add-traders');
    }

    public function addTrader(Request $request, CloudinaryService $cloudinaryService)
    {
        try {
            $request->validate([
                'name'           => 'required|string|max:255',
                'picture'        => 'required|file|mimes:jpeg,jpg,png|max:2048',
                'average_return' => 'required|numeric|min:0',
                'followers'      => 'nullable|integer|min:0',
                'profit_share'   => 'required|numeric|min:0|max:100',
                'win_rate'       => 'required|numeric|min:0|max:100',
                'total_profit'   => 'required|numeric|min:0',
            ]);

            $file = $request->file('picture');

            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'No picture uploaded.',
                ], 400);
            }

            // Upload to Cloudinary
            $uploadedFileUrl = $cloudinaryService->uploadFile($file->getRealPath());

            if (!$uploadedFileUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'Upload to Cloudinary failed. Try again later.',
                ], 500);
            }

            // Save to database
            Trader::create([
                'name'           => $request->name,
                'picture'        => $uploadedFileUrl,
                'average_return' => $request->average_return,
                'followers'      => $request->followers ?? 0,
                'profit_share'   => $request->profit_share,
                'win_rate'       => $request->win_rate,
                'total_profit'   => $request->total_profit,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trader added successfully!',
            ]);

        } catch (\Exception $e) {
            Log::error('Trader create error', [
                'message' => $e->getMessage(),
                'input'   => $request->except('picture'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding trader. Try again.',
            ], 500);
        }
    }

    public function fetchTraders()
    {
        $traders = Trader::latest()->get();

        return view('dashboard.admin.traders', compact('traders'));
    }

    public function deleteTrader($id)
    {
        try {
            $trader = Trader::findOrFail($id);
            $trader->delete();

            return response()->json([
                'success' => true,
                'message' => 'Trader deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete trader. Try again.',
            ], 500);
        }
    }  

    public function editTrader($id)
    {
        $trader = Trader::findOrFail($id);
        return view('dashboard.admin.edit_trader', compact('trader'));
    }

    public function updateTrader(Request $request, CloudinaryService $cloudinaryService, $id)
    {
        try {
            $trader = Trader::findOrFail($id);

            $request->validate([
                'name'           => 'required|string|max:255',
                'picture'        => 'nullable|file|mimes:jpeg,jpg,png|max:2048',
                'average_return' => 'required|numeric|min:0',
                'followers'      => 'nullable|integer|min:0',
                'profit_share'   => 'required|numeric|min:0|max:100',
                'win_rate'       => 'required|numeric|min:0|max:100',
                'total_profit'   => 'required|numeric|min:0',
            ]);

            $data = $request->only([
                'name',
                'average_return',
                'followers',
                'profit_share',
                'win_rate',
                'total_profit',
            ]);

            // If a new picture is uploaded, replace old
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $uploadedFileUrl = $cloudinaryService->uploadFile($file->getRealPath());
                $data['picture'] = $uploadedFileUrl;
            }

            $trader->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Trader updated successfully!',
            ]);

        } catch (\Exception $e) {
            Log::error('Trader update error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update trader. Try again.',
            ], 500);
        }
    }
}
