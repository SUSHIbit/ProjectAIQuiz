<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TierController extends Controller
{
    /**
     * Show the tier upgrade page with plan selection
     */
    public function upgrade()
    {
        $user = Auth::user();
        
        if ($user->isPremium()) {
            return redirect()->route('dashboard')
                ->with('info', 'You already have an active Premium subscription!');
        }

        // Check if user has expired subscription
        $subscriptionStatus = $user->getSubscriptionStatus();
        $latestSubscription = $user->getLatestSubscription();

        return view('tier.upgrade', compact('user', 'subscriptionStatus', 'latestSubscription'));
    }

    /**
     * Show renewal page for expired subscriptions
     */
    public function renewal()
    {
        $user = Auth::user();
        
        if ($user->isPremium()) {
            return redirect()->route('dashboard')
                ->with('info', 'Your subscription is still active!');
        }

        $latestSubscription = $user->getLatestSubscription();
        
        if (!$latestSubscription) {
            return redirect()->route('tier.upgrade')
                ->with('info', 'No previous subscription found. Please subscribe to get premium features.');
        }

        return view('tier.renewal', compact('user', 'latestSubscription'));
    }

    /**
     * Show tier comparison page
     */
    public function compare()
    {
        return view('tier.compare');
    }

    /**
     * Process upgrade with plan selection
     */
    public function processUpgrade(Request $request)
    {
        $request->validate([
            'plan_type' => 'required|in:monthly,yearly'
        ]);

        $user = Auth::user();
        
        if ($user->isPremium()) {
            return redirect()->route('dashboard')
                ->with('info', 'You already have an active Premium subscription!');
        }

        // Store selected plan in session for payment process
        session(['selected_plan' => $request->plan_type]);

        return redirect()->route('payment.initiate');
    }

    /**
     * Decrement user's question attempts (for free users)
     */
    public function decrementAttempts()
    {
        $user = Auth::user();
        
        if ($user->isFree() && $user->question_attempts > 0) {
            $user->decrement('question_attempts');
            return response()->json([
                'success' => true,
                'remaining_attempts' => $user->fresh()->question_attempts
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No attempts available or user has active subscription'
        ]);
    }
}