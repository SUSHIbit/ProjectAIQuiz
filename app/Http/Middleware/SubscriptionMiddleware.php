<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionMiddleware
{
    public function handle(Request $request, Closure $next, string $feature = null): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has active premium subscription
        if (!$user->isPremium()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Active premium subscription required',
                    'message' => 'This feature requires an active premium subscription.',
                    'subscription_status' => $user->getSubscriptionStatus()
                ], 403);
            }

            // Handle expired vs no subscription differently
            $subscriptionStatus = $user->getSubscriptionStatus();
            
            if ($subscriptionStatus === 'expired') {
                return redirect()->route('tier.renewal')
                    ->with('error', 'Your subscription has expired. Please renew to continue using premium features.');
            }

            // Determine redirect and message based on feature
            $redirectRoute = 'tier.upgrade';
            $errorMessage = 'This feature requires an active premium subscription. Please subscribe to access premium features.';

            if ($feature === 'flashcards') {
                $redirectRoute = 'flashcards.upgrade';
                $errorMessage = 'Flashcards require an active premium subscription. Please subscribe to access AI-powered flashcards.';
            } elseif ($feature === 'analytics') {
                $redirectRoute = 'analytics.upgrade';
                $errorMessage = 'Analytics require an active premium subscription. Please subscribe to access detailed performance insights.';
            }

            return redirect()->route($redirectRoute)
                ->with('error', $errorMessage);
        }

        // Check if subscription is expiring soon (within 7 days)
        $daysUntilExpiry = $user->getDaysUntilExpiry();
        if ($daysUntilExpiry !== null && $daysUntilExpiry <= 7) {
            session()->flash('warning', "Your subscription expires in {$daysUntilExpiry} days. Renew now to avoid interruption.");
        }

        return $next($request);
    }
}