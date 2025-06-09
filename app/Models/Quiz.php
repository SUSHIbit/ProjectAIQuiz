<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'subject',
        'topic',
        'description',
        'source_type',
        'total_questions',
        'max_questions_allowed',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quizItems()
    {
        return $this->hasMany(QuizItem::class)->orderBy('order');
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function userAttempts()
    {
        return $this->hasMany(QuizAttempt::class)->where('user_id', auth()->id());
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeAiGenerated($query)
    {
        return $query->where('source_type', 'ai');
    }

    public function scopeManual($query)
    {
        return $query->where('source_type', 'manual');
    }

    // Helper methods
    public function isAiGenerated(): bool
    {
        return $this->source_type === 'ai';
    }

    public function isManual(): bool
    {
        return $this->source_type === 'manual';
    }

    public function getSourceBadgeColorAttribute(): string
    {
        return $this->isAiGenerated() 
            ? 'bg-blue-100 text-blue-800' 
            : 'bg-green-100 text-green-800';
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('M d, Y \a\t H:i');
    }

    public function getShortDescriptionAttribute(): string
    {
        if (!$this->description) {
            return '';
        }
        
        return strlen($this->description) > 100 
            ? substr($this->description, 0, 100) . '...' 
            : $this->description;
    }

    public function canBeTakenBy(User $user): bool
    {
        // Users can take their own quizzes multiple times
        // Later we can add sharing/public quiz features
        return $this->user_id === $user->id;
    }

    public function hasBeenTakenBy(User $user): bool
    {
        return $this->quizAttempts()->where('user_id', $user->id)->exists();
    }

    public function getBestAttemptFor(User $user): ?QuizAttempt
    {
        return $this->quizAttempts()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('score', 'desc')
            ->first();
    }

    public function getLatestAttemptFor(User $user): ?QuizAttempt
    {
        return $this->quizAttempts()
            ->where('user_id', $user->id)
            ->latest()
            ->first();
    }

    public function getAttemptCountFor(User $user): int
    {
        return $this->quizAttempts()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
    }

    // NEW: Question count and tier-related methods
    public function getMaxQuestionsForUser(User $user): int
    {
        if ($user->isPremium()) {
            return min($this->max_questions_allowed ?? 30, 30);
        }
        
        return min($this->max_questions_allowed ?? 10, 10);
    }

    public function supportsTimer(): bool
    {
        // All quizzes support timer, but only premium users can use it
        return true;
    }

    public function getQuestionCountDisplayAttribute(): string
    {
        if ($this->max_questions_allowed && $this->max_questions_allowed !== $this->total_questions) {
            return "{$this->total_questions} (max: {$this->max_questions_allowed})";
        }
        
        return (string) $this->total_questions;
    }

    /**
     * Check if quiz can be exported by user
     */
    public function canBeExportedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * Get export filename
     */
    public function getExportFilename(): string
    {
        $safeName = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $this->title);
        $safeName = substr($safeName, 0, 50);
        
        return "quiz_{$safeName}_" . now()->format('Y-m-d_His');
    }

    /**
     * Get quiz statistics for export
     */
    public function getExportStats(): array
    {
        return [
            'total_questions' => $this->total_questions,
            'source_type' => $this->source_type,
            'created_date' => $this->created_at->format('M d, Y'),
            'has_explanations' => $this->quizItems->whereNotNull('explanation')->count() > 0,
        ];
    }
}