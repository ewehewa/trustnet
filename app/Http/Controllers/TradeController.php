<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TradeController extends Controller
{
    // Show Trade Page
    public function index()
    {
        $user = Auth::user();
        $openTrades = $user->trades()->where('status', 'open')->get();
        $closedTrades = $user->trades()->where('status', 'closed')->get();

        return view('dashboard.user.trade', compact('user', 'openTrades', 'closedTrades'));
    }

    // Handle Buy/Sell trade
    // Handle Buy/Sell trade
    public function placeTrade(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'asset_type' => 'required|string',
            'asset_name' => 'required|string',
            'trade_type' => 'required|in:buy,sell',
            'amount' => 'required|numeric|min:1',
            'leverage' => 'required|string',
            'duration' => 'required|integer|min:1',
            'take_profit' => 'nullable|numeric|min:0',
            'stop_loss' => 'nullable|numeric|min:0',
        ]);

        // Check balance
        if ($request->amount > $user->balance) {
            return back()->withErrors(['amount' => 'Insufficient balance to place this trade']);
        }

        // Deduct amount first
        $user->balance -= $request->amount;
        $user->save();

        // Create trade record
        $trade = Trade::create([
            'user_id' => $user->id,
            'asset_type' => $request->asset_type,
            'asset_name' => $request->asset_name,
            'trade_type' => $request->trade_type,
            'amount' => $request->amount,
            'leverage' => $request->leverage,
            'duration' => $request->duration,
            'take_profit' => $request->take_profit ?? 0,
            'stop_loss' => $request->stop_loss ?? 0,
            'expires_at' => now()->addMinutes((int)$request->duration),
        ]);

        // Notify admin
        Mail::raw("User {$user->username} placed a new trade ({$trade->trade_type} {$trade->asset_name}) that expires at {$trade->expires_at}.", function($message) {
            $message->to('support@trustnetx.com')->subject('New Trade Placed');
        });

        return redirect()
            ->route('trade.index')
            ->with('success', 'Trade placed successfully!')
            ->with('show_history', true);
    }
}
