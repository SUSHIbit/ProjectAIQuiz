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

    // NEW: Timer-related methods
    public function hasTimer(): bool
    {
        return !is_null($this->time_limit);
    }

    public function getTimerDurationInMinutesAttribute(): ?int
    {
        return $this->time_limit ? floor($this->time_limit / 60) : null;
    }

    public function getFormattedTimeRemainingAttribute(): ?string
    {
        $remaining = $this->time_remaining;
        
        if (is_null($remaining)) {
            return null;
        }

        $minutes = floor($remaining / 60);
        $seconds = $remaining % 60;
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function autoSubmitIfTimedOut(): bool
    {
        if ($this->hasTimedOut() && $this->isInProgress()) {
            $this->markAsAbandoned();
            return true;
        }
        
        return false;
    }

    public function getPerformanceInsights()
    {
        return [
            'speed_rating' => $this->getSpeedRating(),
            'accuracy_rating' => $this->getAccuracyRating(),
            'difficulty_assessment' => $this->getDifficultyAssessment(),
            'improvement_suggestions' => $this->getImprovementSuggestions(),
        ];
    }

    public function getSpeedRating()
    {
        if (!$this->time_taken || !$this->total_questions) return 'N/A';
        
        $averageTimePerQuestion = $this->time_taken / $this->total_questions;
        
        if ($averageTimePerQuestion < 30) return 'Very Fast';
        if ($averageTimePerQuestion < 60) return 'Fast';
        if ($averageTimePerQuestion < 120) return 'Normal';
        if ($averageTimePerQuestion < 180) return 'Slow';
        return 'Very Slow';
    }

    public function getAccuracyRating()
    {
        $percentage = $this->score_percentage;
        
        if ($percentage >= 90) return 'Excellent';
        if ($percentage >= 80) return 'Good';
        if ($percentage >= 70) return 'Average';
        if ($percentage >= 60) return 'Below Average';
        return 'Needs Improvement';
    }

    public function getDifficultyAssessment()
    {
        // Compare with other users' performance on the same quiz
        $averageScore = QuizAttempt::where('quiz_id', $this->quiz_id)
            ->where('status', 'completed')
            ->where('id', '!=', $this->id)
            ->avg('score');
        
        if (!$averageScore) return 'Insufficient Data';
        
        $difference = $this->score - $averageScore;
        
        if ($difference > 15) return 'Easy for You';
        if ($difference > 5) return 'Slightly Easy';
        if ($difference > -5) return 'Average Difficulty';
        if ($difference > -15) return 'Challenging';
        return 'Very Challenging';
    }

    public function getImprovementSuggestions()
    {
        $suggestions = [];
        
        if ($this->score_percentage < 70) {
            $suggestions[] = 'Review the topic materials before retaking';
            $suggestions[] = 'Focus on understanding key concepts';
        }
        
        if ($this->time_taken && $this->time_taken > ($this->total_questions * 120)) {
            $suggestions[] = 'Practice time management during quizzes';
            $suggestions[] = 'Try using the timer feature to improve speed';
        }
        
        if ($this->score_percentage >= 90) {
            $suggestions[] = 'Excellent work! Try more challenging topics';
            $suggestions[] = 'Consider helping others or creating your own quizzes';
        }
        
        return $suggestions;
    }

    // Scope for analytics queries
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeWithGoodScore($query, $minScore = 70)
    {
        return $query->where('score', '>=', $minScore);
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->whereHas('quiz', function ($q) use ($subject) {
            $q->where('subject', $subject);
        });
    }
}