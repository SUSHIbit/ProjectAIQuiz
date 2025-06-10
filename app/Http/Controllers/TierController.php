<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TierController extends Controller
{
    /**
     * Show the tier upgrade page
     */
    public function upgrade()
    {
        $user = Auth::user();
        
        if ($user->isPremium()) {
            return redirect()->route('dashboard')
                ->with('info', 'You already have Premium access!');
        }

        return view('tier.upgrade', compact('user'));
    }

    /**
     * Show tier comparison page
     */
    public function compare()
    {
        return view('tier.compare');
    }

    /**
     * Process upgrade - redirect to payment
     */
    public function processUpgrade(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isPremium()) {
            return redirect()->route('dashboard')
                ->with('info', 'You already have Premium access!');
        }

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
            'message' => 'No attempts available or user is Premium'
        ]);
    }
}