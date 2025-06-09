<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TierMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $requiredTier = null): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has required tier
        if ($requiredTier === 'premium' && !$user->isPremium()) {
            return redirect()->route('tier.upgrade')
                ->with('error', 'This feature requires a Premium subscription.');
        }

        // Check if free user has attempts remaining for AI features
        if ($user->isFree() && $request->is('quiz/generator*', 'ai/*') && $user->question_attempts <= 0) {
            return redirect()->route('tier.upgrade')
                ->with('error', 'You have no AI generation attempts remaining. Upgrade to Premium for unlimited access.');
        }

        // Validate question count limits for quiz generation
        if ($request->is('quiz/generate') && $request->isMethod('post')) {
            $questionCount = $request->input('question_count', 10);
            
            if ($user->isFree() && $questionCount > 10) {
                return redirect()->back()
                    ->with('error', 'Free users are limited to 10 questions per quiz. Upgrade to Premium for more questions.');
            }
            
            if ($user->isPremium() && !in_array($questionCount, [10, 20, 30])) {
                return redirect()->back()
                    ->with('error', 'Invalid question count. Please choose 10, 20, or 30 questions.');
            }
        }

        return $next($request);
    }
}