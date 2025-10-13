<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepositController extends Controller
{
    public function decline($id)
    {
        try {
            $deposit = Deposit::findOrFail($id);

            if ($deposit->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This deposit has already been processed.'
                ]);
            }

            $deposit->update(['status' => 'declined']);

            return response()->json([
                'success' => true,
                'message' => 'Deposit declined successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Deposit decline error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ]);
        }
    }
}
