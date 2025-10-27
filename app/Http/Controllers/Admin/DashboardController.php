<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminToUserMail;
use App\Mail\DepositApprovedMail;
use App\Mail\OtpGeneratedMail;
use App\Models\Deposit;
use App\Models\Investment;
use App\Models\Profit;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
         $admin = Auth::guard('admin')->user();

        $totalUsers = User::count();
        $totalDeposits = Deposit::sum('amount');
        $totalWithdrawals = Withdrawal::sum('amount');
        $pendingDeposits = Deposit::where('status', 'pending')->count();
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
        $activePlansCount = Investment::count();

        return view('dashboard.admin.index', compact(
            'totalUsers',
            'totalDeposits',
            'totalWithdrawals',
            'pendingDeposits',
            'pendingWithdrawals',
            'activePlansCount'
        ));
    }

    public function allUsers()
    {
        $users = User::latest()->paginate(10); // 10 users per page
        return view('dashboard.admin.users', compact('users'));
    }

    public function showUser($id)
    {
        $user = User::with([
            'deposits' => function ($q) {
                $q->latest();
            },
            'withdrawals' => function ($q) {
                $q->latest();
            },
            'investments' => function ($q) {
                $q->latest();
            },
            'investments.plan',
            'copiedTraders' => function ($q) {   // <-- add this
                $q->latest();
            },
            'copiedTraders.trader', // eager load trader details
        ])->findOrFail($id);

        // calculate totals
    $totalProfits = $user->profits()->sum('amount');
    $totalBonuses = $user->bonuses()->sum('amount');

        return view('dashboard.admin.show_user', compact('user', 'totalProfits', 'totalBonuses'));
    }


    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function approveDeposit($id)
    {
        $deposit = Deposit::findOrFail($id);

        if ($deposit->status !== 'pending') {
            return back()->with('error', 'Deposit already approved.');
        }

        // Approve deposit and update user balance
        $deposit->update(['status' => 'approved']);
        $deposit->user->increment('balance', $deposit->amount);

        // Send deposit approved email
        Mail::to($deposit->user->email)->send(new DepositApprovedMail($deposit));

        return response()->json(['success' => true, 'message' => 'Deposit approved successfully!']);
    }

    public function allDeposits()
    {
        $deposits = Deposit::with('user')->latest()->paginate(10);
        return view('dashboard.admin.deposit_history', compact('deposits'));
    }

    public function sendMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::to($request->email)->send(new AdminToUserMail($request->subject, $request->message));

            return response()->json([
                'success' => true,
                'message' => 'Mail sent successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send mail.',
            ]);
        }
    }

    public function createMail()
    {
        return view('dashboard.admin.send_mail');
    }

    public function topUpProfit(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = $request->amount;

        Profit::create([
            'user_id' => $user->id,
            'amount' => $amount,
        ]);

        $user->increment('balance', $amount);

        return response()->json([
            'success' => true,
            'message' => 'Profit topped up successfully.',
            'new_balance' => $user->balance,
        ]);
    }

    public function debitProfit(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = $request->amount;

        // Calculate total profit balance for user
        $profitBalance = Profit::where('user_id', $user->id)->sum('amount');

        if ($profitBalance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient profit balance to debit.',
            ], 400);
        }

        // Record the debit as a negative profit entry
        Profit::create([
            'user_id' => $user->id,
            'amount' => -$amount,
        ]);

        // Also reduce the user’s overall balance
        $user->decrement('balance', $amount);

        return response()->json([
            'success' => true,
            'message' => 'Profit debited successfully.',
            'new_profit_balance' => Profit::where('user_id', $user->id)->sum('amount'),
            'new_user_balance' => $user->balance,
        ]);
    }

    public function generateOtp(Request $request, User $user)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        $user->otp = $request->otp;
        $user->save();

        Mail::to($user->email)->send(new OtpGeneratedMail($user->otp));

        return response()->json([
            'success' => true,
            'message' => 'OTP has been generated successfully.'
        ]);
    }

    public function showWalletForm()
    {
       return view('dashboard.admin.wallet');
    }

    public function saveWallet(Request $request)
    {
        $request->validate([
            'wallet_name' => 'required|string|max:255',
            'wallet_address' => 'required|string|max:255',
        ]);

        Wallet::create([
            'wallet_name' => $request->wallet_name,
            'wallet_address' => $request->wallet_address,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wallet saved successfully.'
        ]);
    }

    public function showWallets()
    {
        $wallets = Wallet::latest()->get();
        return view('dashboard.admin.show_wallets', compact('wallets'));
    }

    public function deleteWallet($id)
    {
        $wallet = Wallet::find($id);
        if (!$wallet) {
            return response()->json(['success' => false, 'message' => 'Wallet not found.'], 404);
        }

        $wallet->delete();
        return response()->json(['success' => true]);
    }

    public function debitBalance(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        if ($user->balance < $request->amount) {
            return response()->json(['success' => false, 'message' => 'User has insufficient balance.'], 400);
        }

        $user->balance -= $request->amount;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User balance debited successfully.',
            'new_balance' => $user->balance
        ]);
    }

    public function updateUserBalance(Request $request, User $user)
    {
        $request->validate([
            'type' => 'required|in:add,debit',
            'amount' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            $amount = $request->amount;
            $type = $request->type;

            if ($type === 'debit') {
                // Check sufficient balance
                if ($user->balance < $amount) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User has insufficient balance.'
                    ], 400);
                }

                $user->decrement('balance', $amount);
                $message = 'User balance debited successfully.';
            } else {
                $user->increment('balance', $amount);
                $message = 'User balance added successfully.';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_balance' => $user->balance,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update user balance. Please try again later.',
            ], 500);
        }
    }

}
