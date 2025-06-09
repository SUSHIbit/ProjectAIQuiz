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
        
        if (empty($this->apiKey)) {
            throw new \Exception('OpenAI API key is not configured. Please set OPENAI_API_KEY in your .env file.');
        }
    }

    public function generateQuestions(string $content, int $questionCount = 10): array
    {
        $prompt = $this->buildPrompt($content, $questionCount);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(120)
            ->withOptions([
                'verify' => false, // Disable SSL verification for development
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                ]
            ])
            ->post($this->baseUrl . '/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert quiz generator. Generate multiple choice questions with exactly 4 options each. Always respond with valid JSON format.'
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
                
                if (empty($generatedText)) {
                    throw new \Exception('OpenAI returned empty response');
                }
                
                return $this->parseGeneratedQuestions($generatedText);
            } else {
                $errorMessage = $response->json()['error']['message'] ?? 'Unknown OpenAI API error';
                
                Log::error('OpenAI API Error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'error' => $errorMessage
                ]);
                
                throw new \Exception('OpenAI API Error: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('OpenAI Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // If it's an SSL issue, provide a more helpful error message
            if (strpos($e->getMessage(), 'SSL') !== false || strpos($e->getMessage(), 'certificate') !== false) {
                throw new \Exception('SSL Certificate issue detected. Please check your server configuration or use a development environment with proper SSL setup.');
            }
            
            throw new \Exception('Error connecting to AI service: ' . $e->getMessage());
        }
    }

    private function buildPrompt(string $content, int $questionCount): string
    {
        return "From the following content, generate exactly {$questionCount} multiple choice questions. 

IMPORTANT: Respond ONLY with valid JSON in this exact format:
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
      \"explanation\": \"Brief explanation of why this answer is correct\"
    }
  ]
}

Rules:
- Generate exactly {$questionCount} questions
- Each question must have exactly 4 options (A, B, C, D)
- Provide one correct answer (A, B, C, or D)
- Include a brief explanation for each correct answer
- Make questions clear and unambiguous
- Ensure answers are factually correct based on the content

Content to generate questions from:
{$content}";
    }

    private function parseGeneratedQuestions(string $generatedText): array
    {
        // Clean up the text first
        $generatedText = trim($generatedText);
        
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

        // Log the raw response for debugging
        Log::warning('Failed to parse OpenAI JSON response', [
            'raw_response' => $generatedText,
            'json_error' => json_last_error_msg()
        ]);

        // Fallback: generate default questions
        return $this->generateFallbackQuestions();
    }

    private function formatQuestions(array $questions): array
    {
        $formatted = [];
        
        foreach ($questions as $index => $question) {
            if (!isset($question['question'], $question['options'], $question['correct_answer'])) {
                continue;
            }

            $options = $question['options'];
            $correctLetter = strtoupper($question['correct_answer']);
            
            // Convert letter to number (A=1, B=2, C=3, D=4)
            $correctNumber = match($correctLetter) {
                'A' => 1,
                'B' => 2,
                'C' => 3,
                'D' => 4,
                default => 1
            };

            $formatted[] = [
                'question' => $question['question'],
                'option_1' => $options['A'] ?? $options['a'] ?? 'Option A',
                'option_2' => $options['B'] ?? $options['b'] ?? 'Option B',
                'option_3' => $options['C'] ?? $options['c'] ?? 'Option C',
                'option_4' => $options['D'] ?? $options['d'] ?? 'Option D',
                'correct_answer' => $correctNumber,
                'explanation' => $question['explanation'] ?? 'This is the correct answer based on the content.',
                'order' => $index + 1,
            ];
        }

        return $formatted;
    }

    private function generateFallbackQuestions(): array
    {
        // Fallback questions when API fails
        return [
            [
                'question' => 'Based on the uploaded content, which of the following is a key concept?',
                'option_1' => 'Concept A from the document',
                'option_2' => 'Concept B from the document',
                'option_3' => 'Concept C from the document',
                'option_4' => 'Concept D from the document',
                'correct_answer' => 1,
                'explanation' => 'This is a sample question generated when AI service is unavailable.',
                'order' => 1,
            ],
        ];
    }
}