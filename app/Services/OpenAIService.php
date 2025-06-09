<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    public function generateQuestions(string $content, int $questionCount = 10): array
    {
        $prompt = $this->buildPrompt($content, $questionCount);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post($this->baseUrl . '/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert quiz generator. Generate multiple choice questions with exactly 4 options each.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 3000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $generatedText = $data['choices'][0]['message']['content'] ?? '';
                
                return $this->parseGeneratedQuestions($generatedText);
            } else {
                Log::error('OpenAI API Error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception('Failed to generate questions. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('OpenAI Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Error connecting to AI service: ' . $e->getMessage());
        }
    }

    private function buildPrompt(string $content, int $questionCount): string
    {
        return "From the following content, generate exactly {$questionCount} multiple choice questions. 

For each question, provide:
1. The question text
2. Exactly 4 answer options (A, B, C, D)
3. The correct answer (A, B, C, or D)
4. A brief explanation of why the answer is correct

Format your response as JSON with this structure:
{
  \"questions\": [
    {
      \"question\": \"Question text here?\",
      \"options\": {
        \"A\": \"First option\",
        \"B\": \"Second option\",
        \"C\": \"Third option\",
        \"D\": \"Fourth option\"
      },
      \"correct_answer\": \"A\",
      \"explanation\": \"Explanation text here\"
    }
  ]
}

Content to generate questions from:
{$content}";
    }

    private function parseGeneratedQuestions(string $generatedText): array
    {
        // Try to extract JSON from the response
        $jsonStart = strpos($generatedText, '{');
        $jsonEnd = strrpos($generatedText, '}');
        
        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonText = substr($generatedText, $jsonStart, $jsonEnd - $jsonStart + 1);
            $decoded = json_decode($jsonText, true);
            
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded['questions'])) {
                return $this->formatQuestions($decoded['questions']);
            }
        }

        // Fallback: Try to parse line by line
        return $this->parseFallbackFormat($generatedText);
    }

    private function formatQuestions(array $questions): array
    {
        $formatted = [];
        
        foreach ($questions as $index => $question) {
            if (!isset($question['question'], $question['options'], $question['correct_answer'])) {
                continue;
            }

            $options = $question['options'];
            $correctLetter = $question['correct_answer'];
            
            // Convert letter to number (A=1, B=2, C=3, D=4)
            $correctNumber = match(strtoupper($correctLetter)) {
                'A' => 1,
                'B' => 2,
                'C' => 3,
                'D' => 4,
                default => 1
            };

            $formatted[] = [
                'question' => $question['question'],
                'option_1' => $options['A'] ?? $options['a'] ?? '',
                'option_2' => $options['B'] ?? $options['b'] ?? '',
                'option_3' => $options['C'] ?? $options['c'] ?? '',
                'option_4' => $options['D'] ?? $options['d'] ?? '',
                'correct_answer' => $correctNumber,
                'explanation' => $question['explanation'] ?? '',
                'order' => $index + 1,
            ];
        }

        return $formatted;
    }

    private function parseFallbackFormat(string $text): array
    {
        // Simple fallback parsing logic
        $questions = [];
        $lines = explode("\n", $text);
        
        // This is a basic fallback - in production you might want more sophisticated parsing
        for ($i = 0; $i < min(10, count($lines)); $i++) {
            $questions[] = [
                'question' => "Question " . ($i + 1) . " from uploaded content",
                'option_1' => "Option A",
                'option_2' => "Option B", 
                'option_3' => "Option C",
                'option_4' => "Option D",
                'correct_answer' => 1,
                'explanation' => "This is a generated explanation.",
                'order' => $i + 1,
            ];
        }

        return $questions;
    }
}