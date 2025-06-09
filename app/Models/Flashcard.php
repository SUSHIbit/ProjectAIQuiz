<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'front_text',
        'back_text',
        'source_type',
        'category',
        'tags',
        'study_count',
        'last_studied_at',
    ];

    protected $casts = [
        'last_studied_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeAiGenerated($query)
    {
        return $query->where('source_type', 'ai');
    }

    public function scopeManual($query)
    {
        return $query->where('source_type', 'manual');
    }

    public function scopeRecentlyStudied($query)
    {
        return $query->whereNotNull('last_studied_at')
            ->orderBy('last_studied_at', 'desc');
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

    public function getTagsArrayAttribute(): array
    {
        return $this->tags ? explode(',', $this->tags) : [];
    }

    public function setTagsArrayAttribute(array $tags): void
    {
        $this->tags = implode(',', array_filter($tags));
    }

    public function markAsStudied(): void
    {
        $this->increment('study_count');
        $this->update(['last_studied_at' => Carbon::now()]);
    }

    public function getLastStudiedDisplayAttribute(): string
    {
        if (!$this->last_studied_at) {
            return 'Never studied';
        }

        return $this->last_studied_at->diffForHumans();
    }

    public function getShortFrontTextAttribute(): string
    {
        return strlen($this->front_text) > 100 
            ? substr($this->front_text, 0, 100) . '...' 
            : $this->front_text;
    }

    public function getShortBackTextAttribute(): string
    {
        return strlen($this->back_text) > 100 
            ? substr($this->back_text, 0, 100) . '...' 
            : $this->back_text;
    }

    // Static helper methods
    public static function getUserCategories($userId): array
    {
        return self::where('user_id', $userId)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    public static function getStudyStats($userId): array
    {
        $flashcards = self::where('user_id', $userId)->get();
        
        return [
            'total_flashcards' => $flashcards->count(),
            'studied_flashcards' => $flashcards->where('study_count', '>', 0)->count(),
            'total_study_sessions' => $flashcards->sum('study_count'),
            'categories' => $flashcards->whereNotNull('category')->pluck('category')->unique()->count(),
            'ai_generated' => $flashcards->where('source_type', 'ai')->count(),
            'manual_created' => $flashcards->where('source_type', 'manual')->count(),
            'last_study_session' => $flashcards->whereNotNull('last_studied_at')->max('last_studied_at'),
        ];
    }
}