<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id',
        'quiz_item_id',
        'selected_answer',
        'is_correct',
        'time_spent',
    ];

    protected $casts = [
        'selected_answer' => 'integer',
        'is_correct' => 'boolean',
        'time_spent' => 'integer',
    ];

    // Relationships
    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function quizItem()
    {
        return $this->belongsTo(QuizItem::class);
    }

    // Helper methods
    public function getSelectedOptionTextAttribute(): string
    {
        $options = $this->quizItem->options;
        return $options[$this->selected_answer] ?? '';
    }

    public function getSelectedOptionLabelAttribute(): string
    {
        return chr(64 + $this->selected_answer); // A, B, C, D
    }

    public function getCorrectOptionTextAttribute(): string
    {
        return $this->quizItem->correct_option_text;
    }

    public function getCorrectOptionLabelAttribute(): string
    {
        return $this->quizItem->correct_option_label;
    }
}