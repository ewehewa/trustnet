<?php

namespace App\Http\Controllers;

use App\Models\Signal;
use App\Models\UserSignal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSignalController extends Controller
{
    public function index()
    {
        $signals = Signal::where('status', 1)->get();

        return view('dashboard.user.signals_index', compact('signals'));
    }

    public function subscribe(Signal $signal)
    {
        $user = Auth::user();

        // Check balance
        if ($user->balance < $signal->price) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance.'
            ]);
        }

        // Deduct balance
        $user->balance -= $signal->price;
        $user->save();

        // Save subscription
        $subscription = UserSignal::create([
            'user_id' => $user->id,
            'signal_id' => $signal->id,
            'status' => 'active',
            'expires_at' => now()->addWeeks($signal->duration ?? 1)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription successful!',
        ]);
    }

    public function myPlans()
    {
        $subscriptions = UserSignal::where('user_id', Auth::id())
            ->with('signal')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.user.my_signal', compact('subscriptions'));
    }
}

