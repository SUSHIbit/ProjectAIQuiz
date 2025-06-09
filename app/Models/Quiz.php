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
}