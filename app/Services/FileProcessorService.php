<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;

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
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function extractFromPdf(string $filePath): string
    {
        try {
            // Validate file exists and is readable
            if (!is_readable($filePath)) {
                throw new \Exception('PDF file is not readable or accessible');
            }

            $fileSize = filesize($filePath);
            if ($fileSize === false || $fileSize === 0) {
                throw new \Exception('PDF file is empty or corrupted');
            }

            // Use smalot/pdfparser for better PDF text extraction
            $parser = new Parser();
            $pdf = $parser->parseFile($filePath);
            
            // Extract text from all pages
            $text = $pdf->getText();
            
            if (empty(trim($text))) {
                // Try alternative extraction method
                $text = $this->extractPdfAlternative($pdf);
            }
            
            if (empty(trim($text))) {
                throw new \Exception('No readable text content found in PDF. The PDF might contain only images, scanned content, or be password-protected. Please try uploading a text-based PDF document.');
            }

            // Clean up the extracted text
            $cleanedText = $this->cleanExtractedText($text);
            
            if (strlen($cleanedText) < 50) {
                throw new \Exception('PDF contains very little readable text. Please ensure your PDF has substantial text content for quiz generation.');
            }

            return $cleanedText;

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            
            // Provide more specific error messages
            if (strpos($errorMessage, 'Unable to find') !== false || strpos($errorMessage, 'Cannot open') !== false) {
                throw new \Exception('Cannot open PDF file. The file might be corrupted, password-protected, or use an unsupported PDF format.');
            }
            
            if (strpos($errorMessage, 'No readable text') !== false) {
                throw new \Exception('This PDF appears to contain only images or scanned content. Please upload a text-based PDF document.');
            }
            
            throw new \Exception('Error processing PDF: ' . $errorMessage);
        }
    }

    private function extractPdfAlternative($pdf): string
    {
        try {
            $text = '';
            $pages = $pdf->getPages();
            
            foreach ($pages as $page) {
                $pageText = $page->getText();
                if (!empty(trim($pageText))) {
                    $text .= $pageText . "\n";
                }
            }
            
            return $text;
        } catch (\Exception $e) {
            Log::warning('Alternative PDF extraction failed', ['error' => $e->getMessage()]);
            return '';
        }
    }

    private function extractFromDocx(string $filePath): string
    {
        if (!is_readable($filePath)) {
            throw new \Exception('DOCX file is not readable or accessible');
        }

        try {
            $zip = new \ZipArchive();
            $result = $zip->open($filePath);
            
            if ($result !== TRUE) {
                throw new \Exception('Cannot open DOCX file. The file might be corrupted or use an unsupported format.');
            }

            // Extract document content
            $content = '';
            
            // Try to get document.xml
            $documentXml = $zip->getFromName('word/document.xml');
            if ($documentXml !== false) {
                $content .= $this->extractTextFromXml($documentXml);
            }
            
            // Try to get header files
            for ($i = 1; $i <= 3; $i++) {
                $headerXml = $zip->getFromName("word/header{$i}.xml");
                if ($headerXml !== false) {
                    $content .= ' ' . $this->extractTextFromXml($headerXml);
                }
            }
            
            // Try to get footer files
            for ($i = 1; $i <= 3; $i++) {
                $footerXml = $zip->getFromName("word/footer{$i}.xml");
                if ($footerXml !== false) {
                    $content .= ' ' . $this->extractTextFromXml($footerXml);
                }
            }
            
            $zip->close();

            if (empty(trim($content))) {
                throw new \Exception('No readable text content found in DOCX file');
            }

            // Clean up the extracted text
            $cleanedText = $this->cleanExtractedText($content);
            
            if (strlen($cleanedText) < 50) {
                throw new \Exception('DOCX contains very little readable text. Please ensure your document has substantial text content for quiz generation.');
            }

            return $cleanedText;

        } catch (\Exception $e) {
            if ($e->getMessage() === 'Cannot open DOCX file. The file might be corrupted or use an unsupported format.') {
                throw $e;
            }
            throw new \Exception('Error processing DOCX file: ' . $e->getMessage());
        }
    }

    private function extractTextFromXml(string $xml): string
    {
        try {
            // Load XML and extract text content
            $dom = new \DOMDocument();
            $dom->loadXML($xml);
            
            // Get all text nodes
            $xpath = new \DOMXPath($dom);
            $textNodes = $xpath->query('//w:t');
            
            $text = '';
            foreach ($textNodes as $node) {
                $text .= $node->nodeValue . ' ';
            }
            
            return $text;
        } catch (\Exception $e) {
            // Fallback to simple strip_tags
            $text = strip_tags($xml);
            $text = html_entity_decode($text);
            return $text;
        }
    }

    private function cleanExtractedText(string $text): string
    {
        // Remove excessive whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Remove non-printable characters but keep basic punctuation
        $text = preg_replace('/[^\x20-\x7E\x0A\x0D]/', ' ', $text);
        
        // Clean up multiple spaces again
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Remove very short "words" that are likely artifacts
        $words = explode(' ', $text);
        $cleanWords = array_filter($words, function($word) {
            $word = trim($word);
            // Keep words that are at least 2 characters or are single letters/numbers
            return strlen($word) >= 2 || ctype_alnum($word);
        });
        
        return trim(implode(' ', $cleanWords));
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

        // Count words and characters
        $wordCount = str_word_count($content);
        $characterCount = strlen($content);

        $validation['wordCount'] = $wordCount;
        $validation['characterCount'] = $characterCount;

        // More lenient requirements for word count
        if ($wordCount < 15) {
            $validation['isValid'] = false;
            $validation['errors'][] = "Content too short - extracted only {$wordCount} words. Need at least 15 words for meaningful quiz generation.";
        } elseif ($wordCount < 30) {
            $validation['warnings'][] = "Content is quite short ({$wordCount} words). Consider uploading a document with more content for better quiz generation.";
        }

        // Character count validation
        if ($characterCount < 75) {
            $validation['isValid'] = false;
            $validation['errors'][] = "Content too brief - extracted only {$characterCount} characters. Need substantial text content.";
        }

        // Calculate readable character ratio
        $alphaNumericCount = preg_match_all('/[a-zA-Z0-9]/', $content);
        $readableRatio = $characterCount > 0 ? $alphaNumericCount / $characterCount : 0;
        $validation['readableRatio'] = $readableRatio;

        // More lenient readable ratio requirements
        if ($readableRatio < 0.25) {
            $validation['isValid'] = false;
            $validation['errors'][] = "Content appears to contain mostly non-readable characters. The file might be corrupted, password-protected, or contain mostly images.";
        } elseif ($readableRatio < 0.4) {
            $validation['warnings'][] = "Content has a low ratio of readable text. Quiz quality might be affected.";
        }

        // Check for meaningful sentences
        $sentences = preg_split('/[.!?]+/', $content);
        $meaningfulSentences = array_filter($sentences, function($sentence) {
            $sentence = trim($sentence);
            return strlen($sentence) > 10 && str_word_count($sentence) >= 3;
        });

        if (count($meaningfulSentences) < 3) {
            $validation['isValid'] = false;
            $validation['errors'][] = "Content contains very few complete sentences. The file might not be suitable for quiz generation.";
        }

        // Check for repeated patterns that might indicate extraction errors
        $words = str_word_count($content, 1);
        if (count($words) > 0) {
            $wordCounts = array_count_values($words);
            $maxRepeats = max($wordCounts);
            $totalWords = count($words);
            
            if ($maxRepeats > ($totalWords * 0.3) && $maxRepeats > 10) {
                $validation['warnings'][] = "Content contains many repeated words, which might indicate extraction errors.";
            }
        }

        return $validation;
    }
}