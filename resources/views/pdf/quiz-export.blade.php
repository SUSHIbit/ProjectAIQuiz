// resources/views/pdf/quiz-export.blade.php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $quiz->title }} - Quiz Export</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0 0 10px 0;
            color: #2563eb;
        }

        .header .subtitle {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }

        .quiz-info {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }

        .quiz-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .quiz-info-item {
            font-size: 11px;
        }

        .quiz-info-label {
            font-weight: bold;
            color: #374151;
        }

        .question-container {
            margin-bottom: 25px;
            page-break-inside: avoid;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            background-color: #ffffff;
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #d1d5db;
        }

        .question-number {
            font-weight: bold;
            font-size: 14px;
            color: #1f2937;
        }

        .question-text {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 15px;
            line-height: 1.5;
            color: #111827;
        }

        .options-container {
            margin-bottom: 15px;
        }

        .option {
            margin-bottom: 8px;
            display: flex;
            align-items: flex-start;
            padding: 8px;
            border-radius: 4px;
            font-size: 11px;
        }

        .option.correct {
            background-color: #dcfce7;
            border: 1px solid #16a34a;
        }

        .option.incorrect {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .option-label {
            font-weight: bold;
            margin-right: 10px;
            min-width: 20px;
            color: #374151;
        }

        .option.correct .option-label {
            color: #15803d;
        }

        .option-text {
            flex: 1;
            line-height: 1.4;
        }

        .correct-indicator {
            margin-left: auto;
            font-weight: bold;
            color: #15803d;
            font-size: 10px;
        }

        .explanation {
            background-color: #eff6ff;
            border: 1px solid #3b82f6;
            border-radius: 4px;
            padding: 10px;
            margin-top: 10px;
            font-size: 11px;
        }

        .explanation-header {
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
            font-size: 10px;
            text-transform: uppercase;
        }

        .explanation-text {
            color: #1e3a8a;
            line-height: 1.4;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }

        .page-break {
            page-break-before: always;
        }

        .answer-key {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .answer-key h3 {
            margin: 0 0 10px 0;
            color: #92400e;
            font-size: 14px;
        }

        .answer-key-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            font-size: 10px;
        }

        .answer-key-item {
            text-align: center;
            padding: 4px;
            background-color: #fbbf24;
            border-radius: 3px;
            color: #92400e;
            font-weight: bold;
        }

        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .question-container {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $quiz->title }}</h1>
        <div class="subtitle">{{ $quiz->subject }} - {{ $quiz->topic }}</div>
        <div class="subtitle">{{ $quiz->total_questions }} Questions</div>
    </div>

    <!-- Quiz Information -->
    <div class="quiz-info">
        <div class="quiz-info-grid">
            <div class="quiz-info-item">
                <span class="quiz-info-label">Subject:</span> {{ $quiz->subject }}
            </div>
            <div class="quiz-info-item">
                <span class="quiz-info-label">Topic:</span> {{ $quiz->topic }}
            </div>
            <div class="quiz-info-item">
                <span class="quiz-info-label">Questions:</span> {{ $quiz->total_questions }}
            </div>
            <div class="quiz-info-item">
                <span class="quiz-info-label">Source:</span> {{ $quiz->isAiGenerated() ? 'AI Generated' : 'Manual' }}
            </div>
            <div class="quiz-info-item">
                <span class="quiz-info-label">Created:</span> {{ $quiz->created_at->format('M d, Y') }}
            </div>
            <div class="quiz-info-item">
                <span class="quiz-info-label">Exported:</span> {{ $generated_at->format('M d, Y H:i') }}
            </div>
        </div>
        @if($quiz->description)
        <div style="margin-top: 10px;">
            <span class="quiz-info-label">Description:</span> {{ $quiz->description }}
        </div>
        @endif
    </div>

    <!-- Answer Key (if answers are included) -->
    @if($options['include_answers'])
    <div class="answer-key">
        <h3>Answer Key</h3>
        <div class="answer-key-grid">
            @foreach($quiz->quizItems as $index => $item)
            <div class="answer-key-item">
                Q{{ $index + 1 }}: {{ chr(64 + $item->correct_answer) }}
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Questions -->
    @foreach($quiz->quizItems as $index => $item)
    <div class="question-container">
        <div class="question-header">
            <div class="question-number">Question {{ $index + 1 }}</div>
            @if($options['include_answers'])
            <div style="font-size: 11px; color: #16a34a; font-weight: bold;">
                Correct Answer: {{ chr(64 + $item->correct_answer) }}
            </div>
            @endif
        </div>

        <div class="question-text">{{ $item->question }}</div>

        <div class="options-container">
            @foreach($item->options as $optionNumber => $optionText)
            <div class="option {{ $options['include_answers'] && $item->correct_answer === $optionNumber ? 'correct' : 'incorrect' }}">
                <div class="option-label">{{ chr(64 + $optionNumber) }}.</div>
                <div class="option-text">{{ $optionText }}</div>
                @if($options['include_answers'] && $item->correct_answer === $optionNumber)
                <div class="correct-indicator">✓ CORRECT</div>
                @endif
            </div>
            @endforeach
        </div>

        @if($options['include_explanations'] && $item->explanation)
        <div class="explanation">
            <div class="explanation-header">Explanation</div>
            <div class="explanation-text">{{ $item->explanation }}</div>
        </div>
        @endif
    </div>
    @endforeach

    <!-- Footer -->
    <div class="footer">
        <div>Generated by AI Quiz Generator on {{ $generated_at->format('F j, Y \a\t g:i A') }}</div>
        <div>Created by: {{ $user->name }}</div>
    </div>
</body>
</html>