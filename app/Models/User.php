<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    // Tier checking methods
    public function isPremium(): bool
    {
        return $this->tier === 'premium';
    }

    public function isFree(): bool
    {
        return $this->tier === 'free';
    }

    // Attempt management methods
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

    // Tier badge color for UI
    public function getTierBadgeColorAttribute(): string
    {
        return $this->isPremium() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
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

    // NEW: Add this relationship
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}