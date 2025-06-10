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
        'payment_ref',
        'toyyibpay_bill_code',
        'toyyibpay_bill_external_ref',
        'toyyibpay_category_code',
        'toyyibpay_response',
        'payment_method',
        'status',
        'paid_at',
        'callback_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
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

    public function getPaymentUrlAttribute(): ?string
    {
        if ($this->toyyibpay_bill_code && $this->isPending()) {
            return "https://toyyibpay.com/{$this->toyyibpay_bill_code}";
        }
        return null;
    }

    public function markAsSuccess(array $callbackData = []): void
    {
        $this->update([
            'status' => 'success',
            'paid_at' => Carbon::now(),
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
            'callback_data' => $callbackData,
        ]);
    }

    public function markAsExpired(): void
    {
        $this->update([
            'status' => 'expired',
        ]);
    }
}