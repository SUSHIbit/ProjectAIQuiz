<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PremiumMiddleware
{
    public function handle(Request $request, Closure $next, string $feature = null): Response
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

            // Determine redirect and message based on feature
            $redirectRoute = 'tier.upgrade';
            $errorMessage = 'This feature requires a Premium subscription. Please upgrade to access premium features.';

            if ($feature === 'flashcards') {
                $redirectRoute = 'flashcards.upgrade';
                $errorMessage = 'Flashcards feature requires a Premium subscription. Please upgrade to access AI-powered flashcards.';
            } elseif ($feature === 'analytics') {
                $redirectRoute = 'analytics.upgrade';
                $errorMessage = 'Analytics feature requires a Premium subscription. Please upgrade to access detailed performance insights.';
            }

            return redirect()->route($redirectRoute)
                ->with('error', $errorMessage);
        }

        return $next($request);
    }
}