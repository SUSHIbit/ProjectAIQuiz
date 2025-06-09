<?php

namespace App\Services;

use App\Models\Quiz;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class PdfExportService
{
    public function exportQuizToPdf(Quiz $quiz, array $options = [])
    {
        // Load quiz with items
        $quiz->load('quizItems');

        // Default options
        $defaultOptions = [
            'include_answers' => true,
            'include_explanations' => true,
            'paper_size' => 'a4',
            'orientation' => 'portrait',
        ];

        $options = array_merge($defaultOptions, $options);

        // Prepare data for the PDF view
        $data = [
            'quiz' => $quiz,
            'options' => $options,
            'generated_at' => now(),
            'user' => $quiz->user,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.quiz-export', $data)
            ->setPaper($options['paper_size'], $options['orientation'])
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf;
    }

    public function exportQuizWithAnswers(Quiz $quiz)
    {
        return $this->exportQuizToPdf($quiz, [
            'include_answers' => true,
            'include_explanations' => true,
        ]);
    }

    public function exportQuizWithoutAnswers(Quiz $quiz)
    {
        return $this->exportQuizToPdf($quiz, [
            'include_answers' => false,
            'include_explanations' => false,
        ]);
    }

    public function generateFileName(Quiz $quiz): string
    {
        $safeName = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $quiz->title);
        $safeName = substr($safeName, 0, 50); // Limit length
        
        return "quiz_{$safeName}_" . now()->format('Y-m-d_His') . '.pdf';
    }
}