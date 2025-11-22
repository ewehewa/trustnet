<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Signal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminSignalController extends Controller
{
    // Show all signals
    public function index()
    {
        $signals = Signal::orderBy('created_at', 'desc')->get();

        return view('dashboard.admin.signal_index', compact('signals'));
    }

    // Show create form
    public function create()
    {
        return view('dashboard.admin.add_signals');
    }

    // Store new Signal
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'category' => 'nullable|string|max:255',
                'description_1' => 'nullable|string',
                'description_2' => 'nullable|string',
                'duration' => 'nullable|string|max:255',
                'status' => 'nullable|boolean',
            ]);

            $admin = Auth::guard('admin')->user();

            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            DB::beginTransaction();

            $signal = Signal::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'category' => $validated['category'] ?? 'General trading signal',
                'description_1' => $validated['description_1'] ?? null,
                'description_2' => $validated['description_2'] ?? null,
                'duration' => $validated['duration'] ?? null,
                'status' => $validated['status'] ?? true,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Signal created successfully!',
                'data' => $signal,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
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

    // Edit Signal Page
    public function edit(Signal $signal)
    {
        return view('dashboard.admin.edit_signals', compact('signal'));
    }

    // Update Signal
    public function update(Request $request, Signal $signal)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'category' => 'nullable|string|max:255',
                'description_1' => 'nullable|string',
                'description_2' => 'nullable|string',
                'duration' => 'nullable|string|max:255',
                'status' => 'nullable|boolean',
            ]);

            $admin = Auth::guard('admin')->user();

            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            DB::beginTransaction();

            $signal->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'category' => $validated['category'] ?? 'General trading signal',
                'description_1' => $validated['description_1'] ?? null,
                'description_2' => $validated['description_2'] ?? null,
                'duration' => $validated['duration'] ?? null,
                'status' => $validated['status'] ?? true,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Signal updated successfully!',
                'data' => $signal,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
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

    // Delete Signal
    public function delete(Signal $signal)
    {
        try {
            $admin = Auth::guard('admin')->user();

            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $signal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Signal deleted successfully!',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
