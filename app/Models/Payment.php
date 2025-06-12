<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'plan_type',
        'payment_ref',
        'toyyibpay_bill_code',
        'toyyibpay_bill_external_ref',
        'toyyibpay_category_code',
        'toyyibpay_response',
        'payment_method',
        'status',
        'paid_at',
        'subscription_starts_at',
        'subscription_expires_at',
        'is_renewal',
        'subscription_status',
        'callback_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'subscription_starts_at' => 'datetime',
        'subscription_expires_at' => 'datetime',
        'is_renewal' => 'boolean',
        'callback_data' => 'array',
        'toyyibpay_response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Status methods
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    // Subscription methods
    public function isMonthlyPlan(): bool
    {
        return $this->plan_type === 'monthly';
    }

    public function isYearlyPlan(): bool
    {
        return $this->plan_type === 'yearly';
    }

    public function isSubscriptionActive(): bool
    {
        return $this->subscription_status === 'active' && 
               $this->subscription_expires_at && 
               $this->subscription_expires_at->isFuture();
    }

    public function isSubscriptionExpired(): bool
    {
        return $this->subscription_expires_at && 
               $this->subscription_expires_at->isPast();
    }

    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->subscription_expires_at) {
            return null;
        }
        
        return max(0, now()->diffInDays($this->subscription_expires_at, false));
    }

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActiveSubscriptions($query)
    {
        return $query->where('subscription_status', 'active')
                    ->where('subscription_expires_at', '>', now());
    }

    public function scopeExpiredSubscriptions($query)
    {
        return $query->where('subscription_status', 'active')
                    ->where('subscription_expires_at', '<=', now());
    }

    public function scopeMonthlyPlans($query)
    {
        return $query->where('plan_type', 'monthly');
    }

    public function scopeYearlyPlans($query)
    {
        return $query->where('plan_type', 'yearly');
    }

    // Helper methods
    public function getFormattedAmountAttribute(): string
    {
        return 'RM ' . number_format($this->amount, 2);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'success' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed' => 'bg-red-100 text-red-800',
            'expired' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getSubscriptionStatusBadgeColorAttribute(): string
    {
        return match($this->subscription_status) {
            'active' => 'bg-green-100 text-green-800',
            'expired' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getPlanDisplayNameAttribute(): string
    {
        return match($this->plan_type) {
            'monthly' => 'Monthly Plan',
            'yearly' => 'Yearly Plan',
            default => 'Unknown Plan'
        };
    }

    public function getPaymentUrlAttribute(): ?string
    {
        if ($this->toyyibpay_bill_code && $this->isPending()) {
            return config('services.toyyibpay.base_url') . '/' . $this->toyyibpay_bill_code;
        }
        
        if (isset($this->toyyibpay_response['payment_url'])) {
            return $this->toyyibpay_response['payment_url'];
        }
        
        return null;
    }

    public function markAsSuccess(array $callbackData = []): void
    {
        $startDate = Carbon::now();
        $expiryDate = $this->isMonthlyPlan() 
            ? $startDate->copy()->addMonth() 
            : $startDate->copy()->addYear();

        $this->update([
            'status' => 'success',
            'paid_at' => $startDate,
            'subscription_starts_at' => $startDate,
            'subscription_expires_at' => $expiryDate,
            'subscription_status' => 'active',
            'callback_data' => $callbackData,
        ]);

        // Upgrade user to premium
        $this->user->update([
            'tier' => 'premium',
            'question_attempts' => 999,
        ]);
    }

    public function markAsFailed(array $callbackData = []): void
    {
        $this->update([
            'status' => 'failed',
            'subscription_status' => 'cancelled',
            'callback_data' => $callbackData,
        ]);
    }

    public function markAsExpired(): void
    {
        $this->update([
            'status' => 'expired',
            'subscription_status' => 'expired',
        ]);

        // Downgrade user if this was their active subscription
        if ($this->user->getActiveSubscription()?->id === $this->id) {
            $this->user->update([
                'tier' => 'free',
                'question_attempts' => 5, // Updated from 3 to 5
            ]);
        }
    }

    // Calculate renewal price (with potential discounts)
    public function getRenewalPrice(): float
    {
        return $this->isMonthlyPlan() ? 15.00 : 120.00;
    }

    // Get plan amount based on type - UPDATED PRICING
    public static function getPlanAmount(string $planType): float
    {
        return match($planType) {
            'monthly' => 15.00,  // RM15/month
            'yearly' => 120.00,  // RM120/year (save RM60)
            default => 15.00
        };
    }
}