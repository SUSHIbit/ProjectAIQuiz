<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PremiumMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->isPremium()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Premium subscription required',
                    'message' => 'This feature is only available to Premium users.'
                ], 403);
            }

            return redirect()->route('tier.upgrade')
                ->with('error', 'This feature requires a Premium subscription. Please upgrade to access flashcards.');
        }

        return $next($request);
    }
}