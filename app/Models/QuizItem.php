<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'correct_answer',
        'explanation',
        'order',
    ];

    protected $casts = [
        'correct_answer' => 'integer',
        'order' => 'integer',
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Helper methods
    public function getOptionsAttribute(): array
    {
        return [
            1 => $this->option_1,
            2 => $this->option_2,
            3 => $this->option_3,
            4 => $this->option_4,
        ];
    }

    public function getCorrectOptionTextAttribute(): string
    {
        return $this->options[$this->correct_answer] ?? '';
    }

    public function getCorrectOptionLabelAttribute(): string
    {
        return chr(64 + $this->correct_answer); // A, B, C, D
    }

    public function isCorrectAnswer(int $answerNumber): bool
    {
        return $this->correct_answer === $answerNumber;
    }

    public function getFormattedQuestionAttribute(): string
    {
        return ucfirst(trim($this->question));
    }

    public function hasExplanation(): bool
    {
        return !empty($this->explanation);
    }
}