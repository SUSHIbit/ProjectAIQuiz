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
        // Validate content before processing
        if (empty(trim($content))) {
            throw new \Exception('No content provided for question generation');
        }

        if (strlen($content) < 100) {
            throw new \Exception('Content too short for meaningful question generation');
        }

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
                        'content' => 'You are an expert quiz generator. You MUST generate questions ONLY based on the provided content. Do not use external knowledge. Generate multiple choice questions with exactly 4 options each. Always respond with valid JSON format.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 3000,
                'temperature' => 0.3, // Lower temperature for more focused responses
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $generatedText = $data['choices'][0]['message']['content'] ?? '';
                
                if (empty($generatedText)) {
                    throw new \Exception('OpenAI returned empty response');
                }
                
                $questions = $this->parseGeneratedQuestions($generatedText);
                
                // Validate that we got meaningful questions
                if (empty($questions)) {
                    throw new \Exception('Failed to generate valid questions from the content');
                }

                if (count($questions) < min(3, $questionCount)) {
                    throw new \Exception('Generated fewer questions than expected. The content might not be suitable for quiz generation.');
                }
                
                return $questions;
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
        // Truncate content if too long to fit in context window
        $maxContentLength = 8000; // Leave room for prompt and response
        if (strlen($content) > $maxContentLength) {
            $content = substr($content, 0, $maxContentLength) . '...';
        }

        return "Based EXCLUSIVELY on the following content, generate exactly {$questionCount} multiple choice questions. 

CRITICAL INSTRUCTIONS:
- Use ONLY the information provided in the content below
- Do NOT use external knowledge or information not present in the content
- Each question MUST be answerable from the provided content
- Generate questions that test understanding of the key concepts in the content

CONTENT TO ANALYZE:
\"{$content}\"

REQUIRED OUTPUT FORMAT - Respond ONLY with valid JSON in this exact format:
{
  \"questions\": [
    {
      \"question\": \"Question text based on the content?\",
      \"options\": {
        \"A\": \"First option from content\",
        \"B\": \"Second option from content\",
        \"C\": \"Third option from content\",
        \"D\": \"Fourth option from content\"
      },
      \"correct_answer\": \"A\",
      \"explanation\": \"Brief explanation based on the content provided\"
    }
  ]
}

REQUIREMENTS:
- Generate exactly {$questionCount} questions
- Each question must have exactly 4 options (A, B, C, D)
- Provide one correct answer (A, B, C, or D)
- Include a brief explanation for each correct answer
- Make questions clear and unambiguous
- Ensure all answers are factually correct based ONLY on the provided content
- Focus on key concepts, definitions, processes, or facts mentioned in the content
- Avoid questions that require knowledge not present in the content";
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
                $formattedQuestions = $this->formatQuestions($decoded['questions']);
                
                // Validate that questions are meaningful
                if (!empty($formattedQuestions)) {
                    return $formattedQuestions;
                }
            }
        }

        // Log the raw response for debugging
        Log::warning('Failed to parse OpenAI JSON response', [
            'raw_response' => $generatedText,
            'json_error' => json_last_error_msg()
        ]);

        // If JSON parsing fails, try to parse the response differently
        $fallbackQuestions = $this->tryFallbackParsing($generatedText);
        
        if (!empty($fallbackQuestions)) {
            return $fallbackQuestions;
        }

        throw new \Exception('Failed to parse AI response into valid questions. The content might not be suitable for quiz generation.');
    }

    private function formatQuestions(array $questions): array
    {
        $formatted = [];
        
        foreach ($questions as $index => $question) {
            if (!isset($question['question'], $question['options'], $question['correct_answer'])) {
                Log::warning('Skipping malformed question', ['question' => $question]);
                continue;
            }

            $options = $question['options'];
            $correctLetter = strtoupper($question['correct_answer']);
            
            // Validate that all options exist
            if (!isset($options['A'], $options['B'], $options['C'], $options['D'])) {
                Log::warning('Question missing required options', ['question' => $question]);
                continue;
            }

            // Convert letter to number (A=1, B=2, C=3, D=4)
            $correctNumber = match($correctLetter) {
                'A' => 1,
                'B' => 2,
                'C' => 3,
                'D' => 4,
                default => 1
            };

            // Validate question quality
            $questionText = trim($question['question']);
            if (strlen($questionText) < 10) {
                Log::warning('Question too short', ['question' => $questionText]);
                continue;
            }

            $formatted[] = [
                'question' => $questionText,
                'option_1' => trim($options['A']),
                'option_2' => trim($options['B']),
                'option_3' => trim($options['C']),
                'option_4' => trim($options['D']),
                'correct_answer' => $correctNumber,
                'explanation' => trim($question['explanation'] ?? 'This is the correct answer based on the content.'),
                'order' => $index + 1,
            ];
        }

        return $formatted;
    }

    private function tryFallbackParsing(string $text): array
    {
        // Try to extract questions using regex patterns
        $questions = [];
        
        // Pattern to match question blocks
        if (preg_match_all('/Question:?\s*(.+?)\n.*?A[:\)]?\s*(.+?)\n.*?B[:\)]?\s*(.+?)\n.*?C[:\)]?\s*(.+?)\n.*?D[:\)]?\s*(.+?)\n.*?Answer:?\s*([A-D])/is', $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $index => $match) {
                $questions[] = [
                    'question' => trim($match[1]),
                    'option_1' => trim($match[2]),
                    'option_2' => trim($match[3]),
                    'option_3' => trim($match[4]),
                    'option_4' => trim($match[5]),
                    'correct_answer' => match(strtoupper($match[6])) {
                        'A' => 1,
                        'B' => 2,
                        'C' => 3,
                        'D' => 4,
                        default => 1
                    },
                    'explanation' => 'This is the correct answer based on the content.',
                    'order' => $index + 1,
                ];
            }
        }

        return $questions;
    }

    

    public function generateFlashcards(string $content, int $flashcardCount = 10): array
    {
        // Validate content before processing
        if (empty(trim($content))) {
            throw new \Exception('No content provided for flashcard generation');
        }

        if (strlen($content) < 100) {
            throw new \Exception('Content too short for meaningful flashcard generation');
        }

        $prompt = $this->buildFlashcardPrompt($content, $flashcardCount);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(120)
            ->withOptions([
                'verify' => false,
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
                        'content' => 'You are an expert flashcard creator. Create educational flashcards ONLY based on the provided content. Always respond with valid JSON format.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 3000,
                'temperature' => 0.3,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $generatedText = $data['choices'][0]['message']['content'] ?? '';
                
                if (empty($generatedText)) {
                    throw new \Exception('OpenAI returned empty response');
                }
                
                $flashcards = $this->parseGeneratedFlashcards($generatedText);
                
                if (empty($flashcards)) {
                    throw new \Exception('Failed to generate valid flashcards from the content');
                }

                if (count($flashcards) < min(3, $flashcardCount)) {
                    throw new \Exception('Generated fewer flashcards than expected');
                }
                
                return $flashcards;
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
            Log::error('OpenAI Flashcard Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Error connecting to AI service: ' . $e->getMessage());
        }
    }

    private function buildFlashcardPrompt(string $content, int $flashcardCount): string
    {
        // Truncate content if too long
        $maxContentLength = 8000;
        if (strlen($content) > $maxContentLength) {
            $content = substr($content, 0, $maxContentLength) . '...';
        }

        return "Based EXCLUSIVELY on the following content, generate exactly {$flashcardCount} educational flashcards.

    CRITICAL INSTRUCTIONS:
    - Use ONLY the information provided in the content below
    - Do NOT use external knowledge or information not present in the content
    - Each flashcard must be answerable from the provided content
    - Focus on key concepts, definitions, processes, or important facts

    CONTENT TO ANALYZE:
    \"{$content}\"

    REQUIRED OUTPUT FORMAT - Respond ONLY with valid JSON in this exact format:
    {
    \"flashcards\": [
        {
        \"title\": \"Brief title for the flashcard\",
        \"front_text\": \"Question or concept to learn (from content)\",
        \"back_text\": \"Answer or explanation (from content)\"
        }
    ]
    }

    REQUIREMENTS:
    - Generate exactly {$flashcardCount} flashcards
    - Each flashcard must have title, front_text, and back_text
    - Front text should be a question, term, or concept
    - Back text should be the answer, definition, or explanation
    - Keep front text concise (under 100 characters when possible)
    - Keep back text informative but not too long (under 300 characters)
    - Base all content strictly on the provided material
    - Make flashcards suitable for studying and memorization";
    }

    private function parseGeneratedFlashcards(string $generatedText): array
    {
        // Clean up the text first
        $generatedText = trim($generatedText);
        
        // Try to extract JSON from the response
        $jsonStart = strpos($generatedText, '{');
        $jsonEnd = strrpos($generatedText, '}');
        
        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonText = substr($generatedText, $jsonStart, $jsonEnd - $jsonStart + 1);
            $decoded = json_decode($jsonText, true);
            
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded['flashcards'])) {
                $formattedFlashcards = $this->formatFlashcards($decoded['flashcards']);
                
                if (!empty($formattedFlashcards)) {
                    return $formattedFlashcards;
                }
            }
        }

        // Log the raw response for debugging
        Log::warning('Failed to parse OpenAI flashcard JSON response', [
            'raw_response' => $generatedText,
            'json_error' => json_last_error_msg()
        ]);

        throw new \Exception('Failed to parse AI response into valid flashcards');
    }

    private function formatFlashcards(array $flashcards): array
    {
        $formatted = [];
        
        foreach ($flashcards as $index => $flashcard) {
            if (!isset($flashcard['title'], $flashcard['front_text'], $flashcard['back_text'])) {
                Log::warning('Skipping malformed flashcard', ['flashcard' => $flashcard]);
                continue;
            }

            // Validate flashcard quality
            $title = trim($flashcard['title']);
            $frontText = trim($flashcard['front_text']);
            $backText = trim($flashcard['back_text']);
            
            if (strlen($title) < 3 || strlen($frontText) < 5 || strlen($backText) < 5) {
                Log::warning('Flashcard too short', ['flashcard' => $flashcard]);
                continue;
            }

            $formatted[] = [
                'title' => $title,
                'front_text' => $frontText,
                'back_text' => $backText,
            ];
        }

        return $formatted;
    }
}