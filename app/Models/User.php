<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'tier',
        'question_attempts',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role checking methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // Tier checking methods (updated with subscription logic)
    public function isPremium(): bool
    {
        // Check if user has premium tier AND active subscription
        if ($this->tier !== 'premium') {
            return false;
        }

        $activeSubscription = $this->getActiveSubscription();
        return $activeSubscription && $activeSubscription->isSubscriptionActive();
    }

    public function isFree(): bool
    {
        return !$this->isPremium();
    }

    // Subscription methods
    public function getActiveSubscription(): ?Payment
    {
        return $this->payments()
            ->where('status', 'success')
            ->where('subscription_status', 'active')
            ->where('subscription_expires_at', '>', now())
            ->orderBy('subscription_expires_at', 'desc')
            ->first();
    }

    public function getLatestSubscription(): ?Payment
    {
        return $this->payments()
            ->where('status', 'success')
            ->whereNotNull('subscription_expires_at')
            ->orderBy('subscription_expires_at', 'desc')
            ->first();
    }

    public function hasExpiredSubscription(): bool
    {
        $latestSubscription = $this->getLatestSubscription();
        return $latestSubscription && $latestSubscription->isSubscriptionExpired();
    }

    public function getSubscriptionStatus(): string
    {
        $activeSubscription = $this->getActiveSubscription();
        
        if ($activeSubscription) {
            $daysUntilExpiry = $activeSubscription->days_until_expiry;
            
            if ($daysUntilExpiry <= 7) {
                return 'expiring_soon';
            }
            
            return 'active';
        }

        if ($this->hasExpiredSubscription()) {
            return 'expired';
        }

        return 'none';
    }

    public function getSubscriptionExpiryDate(): ?Carbon
    {
        $activeSubscription = $this->getActiveSubscription();
        return $activeSubscription?->subscription_expires_at;
    }

    public function getDaysUntilExpiry(): ?int
    {
        $activeSubscription = $this->getActiveSubscription();
        return $activeSubscription?->days_until_expiry;
    }

    public function getCurrentPlanType(): ?string
    {
        $activeSubscription = $this->getActiveSubscription();
        return $activeSubscription?->plan_type;
    }

    // Force expire user subscription (for admin use or cron jobs)
    public function expireSubscription(): void
    {
        $activeSubscription = $this->getActiveSubscription();
        
        if ($activeSubscription) {
            $activeSubscription->markAsExpired();
        }
        
        $this->update([
            'tier' => 'free',
            'question_attempts' => 3,
        ]);
    }

    // Attempt management methods (updated)
    public function canGenerateQuestions(): bool
    {
        return $this->isPremium() || $this->question_attempts > 0;
    }

    public function decrementAttempts(): void
    {
        if ($this->isFree() && $this->question_attempts > 0) {
            $this->decrement('question_attempts');
        }
    }

    public function hasUnlimitedAccess(): bool
    {
        return $this->isPremium();
    }

    public function getRemainingAttemptsAttribute(): int
    {
        return $this->isPremium() ? 999 : $this->question_attempts;
    }

    public function getAttemptsDisplayAttribute(): string
    {
        return $this->isPremium() ? 'Unlimited' : (string) $this->question_attempts;
    }

    // Tier badge color for UI (updated)
    public function getTierBadgeColorAttribute(): string
    {
        if ($this->isPremium()) {
            $status = $this->getSubscriptionStatus();
            
            return match($status) {
                'active' => 'bg-green-100 text-green-800',
                'expiring_soon' => 'bg-yellow-100 text-yellow-800',
                'expired' => 'bg-red-100 text-red-800',
                default => 'bg-gray-100 text-gray-800'
            };
        }
        
        return 'bg-gray-100 text-gray-800';
    }

    public function getTierDisplayAttribute(): string
    {
        if ($this->isPremium()) {
            $subscription = $this->getActiveSubscription();
            $planType = $subscription?->plan_display_name ?? 'Premium';
            
            $status = $this->getSubscriptionStatus();
            if ($status === 'expiring_soon') {
                $daysLeft = $this->getDaysUntilExpiry();
                return $planType . " (expires in {$daysLeft} days)";
            }
            
            return $planType;
        }
        
        return 'Free';
    }

    // Relationships
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function flashcards()
    {
        return $this->hasMany(Flashcard::class);
    }

    // Analytics and stats methods remain the same...
    public function getAnalyticsData()
    {
        $completedAttempts = $this->quizAttempts()
            ->where('status', 'completed')
            ->with('quiz')
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'total_attempts' => $completedAttempts->count(),
            'average_score' => round($completedAttempts->avg('score'), 1),
            'best_score' => $completedAttempts->max('score'),
            'total_time_spent' => $completedAttempts->sum('time_taken'),
            'subjects_studied' => $completedAttempts->pluck('quiz.subject')->unique()->count(),
            'improvement_rate' => $this->calculateImprovementRate($completedAttempts),
        ];
    }

    public function getSubjectPerformance()
    {
        return $this->quizAttempts()
            ->where('status', 'completed')
            ->with('quiz')
            ->get()
            ->groupBy('quiz.subject')
            ->map(function ($attempts) {
                return [
                    'subject' => $attempts->first()->quiz->subject,
                    'attempts' => $attempts->count(),
                    'average_score' => round($attempts->avg('score'), 1),
                    'best_score' => $attempts->max('score'),
                    'latest_attempt' => $attempts->sortByDesc('created_at')->first()->created_at,
                ];
            })
            ->values();
    }

    public function getRecentPerformanceTrend($days = 30)
    {
        return $this->quizAttempts()
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($attempt) {
                return $attempt->created_at->format('Y-m-d');
            })
            ->map(function ($dayAttempts, $date) {
                return [
                    'date' => $date,
                    'average_score' => round($dayAttempts->avg('score'), 1),
                    'attempts' => $dayAttempts->count(),
                ];
            })
            ->values();
    }

    private function calculateImprovementRate($attempts)
    {
        if ($attempts->count() < 2) return 0;
        
        $firstHalf = $attempts->reverse()->take(ceil($attempts->count() / 2));
        $secondHalf = $attempts->take(floor($attempts->count() / 2));
        
        $firstAvg = $firstHalf->avg('score');
        $secondAvg = $secondHalf->avg('score');
        
        return round($secondAvg - $firstAvg, 1);
    }

    public function getFlashcardStats()
    {
        return [
            'total_flashcards' => $this->flashcards()->count(),
            'ai_generated' => $this->flashcards()->where('source_type', 'ai')->count(),
            'manual_created' => $this->flashcards()->where('source_type', 'manual')->count(),
            'categories' => $this->flashcards()->whereNotNull('category')->distinct('category')->count(),
            'total_studies' => $this->flashcards()->sum('study_count'),
            'last_activity' => $this->flashcards()->max('last_studied_at'),
        ];
    }
}