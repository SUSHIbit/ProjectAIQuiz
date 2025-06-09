<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'total_questions',
        'correct_answers',
        'time_taken',
        'time_limit',
        'status',
        'started_at',
        'completed_at',
        'settings',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isAbandoned(): bool
    {
        return $this->status === 'abandoned';
    }

    public function getScorePercentageAttribute(): float
    {
        return $this->total_questions > 0 
            ? round(($this->correct_answers / $this->total_questions) * 100, 1)
            : 0;
    }

    public function getFormattedTimeAttribute(): string
    {
        if (!$this->time_taken) {
            return 'N/A';
        }

        $minutes = floor($this->time_taken / 60);
        $seconds = $this->time_taken % 60;
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getTimeRemainingAttribute(): ?int
    {
        if (!$this->time_limit || $this->isCompleted()) {
            return null;
        }

        $elapsed = Carbon::now()->diffInSeconds($this->started_at);
        $remaining = $this->time_limit - $elapsed;
        
        return max(0, $remaining);
    }

    public function hasTimedOut(): bool
    {
        if (!$this->time_limit || $this->isCompleted()) {
            return false;
        }

        $elapsed = Carbon::now()->diffInSeconds($this->started_at);
        return $elapsed >= $this->time_limit;
    }

    public function getGradeAttribute(): string
    {
        $percentage = $this->score_percentage;
        
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }

    public function calculateScore(): void
    {
        $correctAnswers = $this->answers()->where('is_correct', true)->count();
        $totalQuestions = $this->quiz->total_questions;
        
        $this->update([
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'score' => round(($correctAnswers / $totalQuestions) * 100, 1),
        ]);
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
            'time_taken' => Carbon::now()->diffInSeconds($this->started_at),
        ]);
        
        $this->calculateScore();
    }

    public function markAsAbandoned(): void
    {
        $this->update([
            'status' => 'abandoned',
            'completed_at' => Carbon::now(),
            'time_taken' => Carbon::now()->diffInSeconds($this->started_at),
        ]);
        
        $this->calculateScore();
    }
}