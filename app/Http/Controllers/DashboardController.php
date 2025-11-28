<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalBonus = $user->bonuses()->sum('amount');

        //Trade Stats
        $totalTrades = $user->trades()->count();

        $openTrades = $user->trades()->where('status', 'open')->count();

        $closedTrades = $user->trades()->where('status', 'closed')->count();

        // Wins / Losses based on profit_loss
        $wins = $user->trades()->where('profit_loss', '>', 0)->count();
        $losses = $user->trades()->where('profit_loss', '<', 0)->count();

        // Win/Loss Ratio
        $winLossRatio = ($wins + $losses) === 0 
            ? '0' 
            : round(($wins / ($wins + $losses)) * 100, 2);

        return view('dashboard.user.index', compact(
            'user',
            'totalBonus',
            'totalTrades',
            'openTrades',
            'closedTrades',
            'wins',
            'losses',
            'winLossRatio'
        ));
    }


    public function showProfile()
    {
        return view('dashboard.user.profile');
    }
    
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
        ]);

        return response()->json(['success' => true, 'message' => 'Profile updated successfully.']);
    }

    Public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'currentPassword' => 'required|string',
                'newPassword' => 'required|string|min:6|confirmed',
            ]);

            $user = Auth::user();

            if (!Hash::check($request->currentPassword, $user->password)) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'currentPassword' => ['The current password is incorrect.']
                    ]
                ], 422);
            }

            $user->password = Hash::make($request->newPassword);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /* ===========================================
     | LIVE ANALYSIS PAGES (3 PAGES ONLY)
     =========================================== */

    // Page 1: Technical Analysis Charts
    public function technicalAnalysis()
    {
        $user = Auth::user();

        return view('dashboard.user.technical', compact('user'));
    }

    // Page 2: Live Market Charts
    public function liveMarketCharts()
    {
        $user = Auth::user();
        return view('dashboard.user.livecharts', compact('user'));
    }

    // Page 3: Economic Calendar
    public function marketCalendar()
    {
        $user = Auth::user();
        return view('dashboard.user.market_calendar', compact('user'));
    }
}
