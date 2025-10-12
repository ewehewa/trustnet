<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class WithdrawalController extends Controller
{
    public function approve($id)
    {
        try {
            $withdrawal = Withdrawal::find($id);

            if (!$withdrawal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Withdrawal not found.',
                ], Response::HTTP_NOT_FOUND);
            }

            if ($withdrawal->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Withdrawal already approved.',
                ], Response::HTTP_BAD_REQUEST);
            }

            $withdrawal->update(['status' => 'approved']);

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal approved successfully.',
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
