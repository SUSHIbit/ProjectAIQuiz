<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManualQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'topic' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'questions' => ['required', 'array', 'min:5', 'max:50'],
            'questions.*.question' => ['required', 'string', 'max:1000'],
            'questions.*.option_1' => ['required', 'string', 'max:255'],
            'questions.*.option_2' => ['required', 'string', 'max:255'],
            'questions.*.option_3' => ['required', 'string', 'max:255'],
            'questions.*.option_4' => ['required', 'string', 'max:255'],
            'questions.*.correct_answer' => ['required', 'integer', 'min:1', 'max:4'],
            'questions.*.explanation' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Quiz title is required.',
            'subject.required' => 'Subject is required.',
            'topic.required' => 'Topic is required.',
            'questions.required' => 'At least one question is required.',
            'questions.min' => 'At least 5 questions are required.',
            'questions.max' => 'Maximum 50 questions allowed.',
            'questions.*.question.required' => 'Question text is required.',
            'questions.*.option_1.required' => 'Option A is required.',
            'questions.*.option_2.required' => 'Option B is required.',
            'questions.*.option_3.required' => 'Option C is required.',
            'questions.*.option_4.required' => 'Option D is required.',
            'questions.*.correct_answer.required' => 'Correct answer must be selected.',
            'questions.*.correct_answer.min' => 'Correct answer must be between 1-4.',
            'questions.*.correct_answer.max' => 'Correct answer must be between 1-4.',
        ];
    }

    public function attributes(): array
    {
        $attributes = [];
        
        if ($this->has('questions')) {
            foreach ($this->questions as $index => $question) {
                $questionNum = $index + 1;
                $attributes["questions.{$index}.question"] = "Question {$questionNum}";
                $attributes["questions.{$index}.option_1"] = "Question {$questionNum} Option A";
                $attributes["questions.{$index}.option_2"] = "Question {$questionNum} Option B";
                $attributes["questions.{$index}.option_3"] = "Question {$questionNum} Option C";
                $attributes["questions.{$index}.option_4"] = "Question {$questionNum} Option D";
                $attributes["questions.{$index}.correct_answer"] = "Question {$questionNum} Correct Answer";
                $attributes["questions.{$index}.explanation"] = "Question {$questionNum} Explanation";
            }
        }
        
        return $attributes;
    }
}