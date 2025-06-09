<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileProcessorService
{
    public function extractTextFromFile(string $filePath): string
    {
        if (!Storage::exists($filePath)) {
            throw new \Exception('File not found');
        }

        $fullPath = Storage::path($filePath);
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);

        try {
            switch (strtolower($extension)) {
                case 'pdf':
                    return $this->extractFromPdf($fullPath);
                case 'doc':
                case 'docx':
                    return $this->extractFromDocx($fullPath);
                default:
                    throw new \Exception('Unsupported file type');
            }
        } catch (\Exception $e) {
            Log::error('File processing error', [
                'file' => $filePath,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function extractFromPdf(string $filePath): string
    {
        // First try to read the file and validate it exists and is readable
        if (!is_readable($filePath)) {
            throw new \Exception('PDF file is not readable');
        }

        $fileSize = filesize($filePath);
        if ($fileSize === false || $fileSize === 0) {
            throw new \Exception('PDF file is empty or corrupted');
        }

        // Try multiple extraction methods
        $content = '';
        
        // Method 1: Try advanced PDF text extraction
        try {
            $content = $this->extractPdfContentAdvanced($filePath);
        } catch (\Exception $e) {
            Log::warning('Advanced PDF extraction failed', ['error' => $e->getMessage()]);
        }

        // Method 2: If advanced method fails, try basic extraction
        if (empty(trim($content))) {
            try {
                $content = $this->extractPdfContentBasic($filePath);
            } catch (\Exception $e) {
                Log::warning('Basic PDF extraction failed', ['error' => $e->getMessage()]);
            }
        }

        // Method 3: If both fail, try simple text search
        if (empty(trim($content))) {
            $content = $this->extractPdfContentSimple($filePath);
        }
        
        if (empty(trim($content))) {
            throw new \Exception('No readable text content found in PDF file. The PDF might contain only images or scanned content.');
        }

        return $content;
    }

    private function extractPdfContentAdvanced(string $filePath): string
    {
        $handle = fopen($filePath, 'rb');
        if (!$handle) {
            throw new \Exception('Cannot open PDF file');
        }

        $content = '';
        $text = fread($handle, filesize($filePath));
        fclose($handle);

        // Look for text streams in PDF
        $streamPattern = '/stream\s*\n(.*?)\nendstream/s';
        if (preg_match_all($streamPattern, $text, $streamMatches)) {
            foreach ($streamMatches[1] as $stream) {
                // Try to decode the stream
                $decodedStream = $this->decodePdfStream($stream);
                $extractedText = $this->extractTextFromStream($decodedStream);
                if (!empty($extractedText)) {
                    $content .= $extractedText . ' ';
                }
            }
        }

        return trim($content);
    }

    private function extractPdfContentBasic(string $filePath): string
    {
        $handle = fopen($filePath, 'rb');
        if (!$handle) {
            throw new \Exception('Cannot open PDF file');
        }

        $content = '';
        $text = fread($handle, filesize($filePath));
        fclose($handle);

        // Look for text between BT and ET operators
        if (preg_match_all('/BT\s*(.*?)\s*ET/s', $text, $matches)) {
            foreach ($matches[1] as $match) {
                // Extract text from PDF text showing operators
                if (preg_match_all('/\[(.*?)\]\s*TJ/s', $match, $textMatches)) {
                    foreach ($textMatches[1] as $textMatch) {
                        $content .= $this->cleanPdfText($textMatch) . ' ';
                    }
                }
                // Also try Tj operator
                if (preg_match_all('/\((.*?)\)\s*Tj/s', $match, $textMatches)) {
                    foreach ($textMatches[1] as $textMatch) {
                        $content .= $this->cleanPdfText($textMatch) . ' ';
                    }
                }
                // Try TD and Td operators with text
                if (preg_match_all('/\((.*?)\)\s*T[dD]/s', $match, $textMatches)) {
                    foreach ($textMatches[1] as $textMatch) {
                        $content .= $this->cleanPdfText($textMatch) . ' ';
                    }
                }
            }
        }

        return trim($content);
    }

    private function extractPdfContentSimple(string $filePath): string
    {
        $handle = fopen($filePath, 'rb');
        if (!$handle) {
            throw new \Exception('Cannot open PDF file');
        }

        $text = fread($handle, filesize($filePath));
        fclose($handle);

        // Remove binary data and extract readable text
        $content = '';
        
        // Split by common PDF delimiters and extract readable text
        $chunks = preg_split('/[\x00-\x1F\x7F-\xFF]+/', $text);
        
        foreach ($chunks as $chunk) {
            $chunk = trim($chunk);
            
            // Filter out PDF commands and keep actual content
            if (strlen($chunk) > 3 && 
                !preg_match('/^(obj|endobj|stream|endstream|xref|trailer|%%PDF|startxref|\/[A-Za-z]+|[0-9\s]+R|<[<>]+>)/', $chunk) &&
                preg_match('/[a-zA-Z]{2,}/', $chunk)) {
                
                // Clean up the text
                $cleanChunk = preg_replace('/[^\w\s\.,;:!?\-()\'\"]+/', ' ', $chunk);
                $cleanChunk = preg_replace('/\s+/', ' ', $cleanChunk);
                $cleanChunk = trim($cleanChunk);
                
                if (strlen($cleanChunk) > 3) {
                    $content .= $cleanChunk . ' ';
                }
            }
        }

        return trim($content);
    }

    private function decodePdfStream(string $stream): string
    {
        // Try to decompress if it's compressed
        if (function_exists('gzuncompress')) {
            try {
                $decompressed = gzuncompress($stream);
                if ($decompressed !== false) {
                    return $decompressed;
                }
            } catch (\Exception $e) {
                // Compression failed, continue with original
            }
        }

        return $stream;
    }

    private function extractTextFromStream(string $stream): string
    {
        $text = '';
        
        // Look for text showing operations in the stream
        if (preg_match_all('/\((.*?)\)\s*T[jd]/s', $stream, $matches)) {
            foreach ($matches[1] as $match) {
                $text .= $this->cleanPdfText($match) . ' ';
            }
        }

        // Look for array text showing operations
        if (preg_match_all('/\[(.*?)\]\s*TJ/s', $stream, $matches)) {
            foreach ($matches[1] as $match) {
                $text .= $this->cleanPdfText($match) . ' ';
            }
        }

        return trim($text);
    }

    private function cleanPdfText(string $text): string
    {
        // Remove PDF escape sequences
        $text = str_replace(['\\(', '\\)', '\\\\'], ['(', ')', '\\'], $text);
        
        // Remove non-printable characters but keep spaces and common punctuation
        $text = preg_replace('/[^\x20-\x7E]/', ' ', $text);
        
        // Clean up multiple spaces
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }

    private function extractFromDocx(string $filePath): string
    {
        if (!is_readable($filePath)) {
            throw new \Exception('DOCX file is not readable');
        }

        // Basic DOCX text extraction
        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== TRUE) {
            throw new \Exception('Cannot open DOCX file - file may be corrupted');
        }

        $content = '';
        $documentXml = $zip->getFromName('word/document.xml');
        
        if ($documentXml !== false) {
            // Remove XML tags and extract text
            $content = strip_tags($documentXml);
            $content = html_entity_decode($content);
            $content = preg_replace('/\s+/', ' ', $content);
        } else {
            $zip->close();
            throw new \Exception('Cannot read document content from DOCX file');
        }
        
        $zip->close();

        $content = trim($content);
        
        if (empty($content)) {
            throw new \Exception('No readable text content found in DOCX file');
        }

        return $content;
    }

    public function validateFile(string $filePath): bool
    {
        if (!Storage::exists($filePath)) {
            return false;
        }

        $fullPath = Storage::path($filePath);
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $allowedExtensions = ['pdf', 'doc', 'docx'];

        if (!in_array(strtolower($extension), $allowedExtensions)) {
            return false;
        }

        // Check file size (max 10MB)
        $fileSize = Storage::size($filePath);
        if ($fileSize > 10 * 1024 * 1024) {
            return false;
        }

        // Check if file is actually readable
        if (!is_readable($fullPath)) {
            return false;
        }

        return true;
    }

    public function validateExtractedContent(string $content): array
    {
        $content = trim($content);
        
        $validation = [
            'isValid' => true,
            'errors' => [],
            'warnings' => [],
            'wordCount' => 0,
            'characterCount' => 0,
            'readableRatio' => 0
        ];

        if (empty($content)) {
            $validation['isValid'] = false;
            $validation['errors'][] = 'No content extracted from file';
            return $validation;
        }

        $wordCount = str_word_count($content);
        $characterCount = strlen($content);

        $validation['wordCount'] = $wordCount;
        $validation['characterCount'] = $characterCount;

        // More lenient word count requirement
        if ($wordCount < 20) {
            $validation['isValid'] = false;
            $validation['errors'][] = "Content too short - extracted only {$wordCount} words. Need at least 20 words for meaningful quiz generation.";
        } elseif ($wordCount < 50) {
            $validation['warnings'][] = "Content is quite short ({$wordCount} words). Consider uploading a document with more content for better quiz generation.";
        }

        // More lenient character count requirement
        if ($characterCount < 100) {
            $validation['isValid'] = false;
            $validation['errors'][] = "Content too brief - extracted only {$characterCount} characters. Need substantial text content.";
        }

        // More lenient readable character ratio
        $alphaNumericCount = preg_match_all('/[a-zA-Z0-9]/', $content);
        $readableRatio = $characterCount > 0 ? $alphaNumericCount / $characterCount : 0;
        $validation['readableRatio'] = $readableRatio;

        if ($readableRatio < 0.3) {
            $validation['isValid'] = false;
            $validation['errors'][] = "Content appears to contain mostly non-readable characters (only {$alphaNumericCount} readable characters out of {$characterCount} total). The file might be corrupted, password-protected, or contain mostly images.";
        } elseif ($readableRatio < 0.5) {
            $validation['warnings'][] = "Content has a low ratio of readable text. Quiz quality might be affected.";
        }

        // Check for meaningful words
        $words = str_word_count($content, 1);
        $meaningfulWords = array_filter($words, function($word) {
            return strlen($word) > 2 && ctype_alpha($word);
        });

        if (count($meaningfulWords) < 10) {
            $validation['isValid'] = false;
            $validation['errors'][] = "Content contains very few meaningful words. The file might not be suitable for quiz generation.";
        }

        return $validation;
    }
}