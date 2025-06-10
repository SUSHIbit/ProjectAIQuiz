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
                case 'ppt':
                case 'pptx':
                    return $this->extractFromPowerPoint($fullPath);
                default:
                    throw new \Exception('Unsupported file type: ' . $extension);
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
            if (!is_readable($filePath)) {
                throw new \Exception('PDF file is not readable or accessible');
            }

            $fileSize = filesize($filePath);
            if ($fileSize === false || $fileSize === 0) {
                throw new \Exception('PDF file is empty or corrupted');
            }

            $parser = new Parser();
            $pdf = $parser->parseFile($filePath);
            
            $text = $pdf->getText();
            
            if (empty(trim($text))) {
                $text = $this->extractPdfAlternative($pdf);
            }
            
            if (empty(trim($text))) {
                throw new \Exception('No readable text content found in PDF. The PDF might contain only images, scanned content, or be password-protected. Please try uploading a text-based PDF document.');
            }

            $cleanedText = $this->cleanExtractedText($text);
            
            if (strlen($cleanedText) < 50) {
                throw new \Exception('PDF contains very little readable text. Please ensure your PDF has substantial text content for quiz generation.');
            }

            return $cleanedText;

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            
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

            $content = '';
            
            $documentXml = $zip->getFromName('word/document.xml');
            if ($documentXml !== false) {
                $content .= $this->extractTextFromXml($documentXml);
            }
            
            for ($i = 1; $i <= 3; $i++) {
                $headerXml = $zip->getFromName("word/header{$i}.xml");
                if ($headerXml !== false) {
                    $content .= ' ' . $this->extractTextFromXml($headerXml);
                }
            }
            
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

    private function extractFromPowerPoint(string $filePath): string
    {
        if (!is_readable($filePath)) {
            throw new \Exception('PowerPoint file is not readable or accessible');
        }

        try {
            $zip = new \ZipArchive();
            $result = $zip->open($filePath);
            
            if ($result !== TRUE) {
                throw new \Exception('Cannot open PowerPoint file. The file might be corrupted or use an unsupported format.');
            }

            $content = '';
            
            // Extract text from all slides
            for ($i = 1; $i <= 100; $i++) { // Check up to 100 slides
                $slideXml = $zip->getFromName("ppt/slides/slide{$i}.xml");
                if ($slideXml !== false) {
                    $slideText = $this->extractTextFromPowerPointXml($slideXml);
                    if (!empty(trim($slideText))) {
                        $content .= $slideText . "\n";
                    }
                } else {
                    break; // No more slides
                }
            }
            
            // Extract from slide masters if available
            for ($i = 1; $i <= 10; $i++) {
                $masterXml = $zip->getFromName("ppt/slideMasters/slideMaster{$i}.xml");
                if ($masterXml !== false) {
                    $masterText = $this->extractTextFromPowerPointXml($masterXml);
                    if (!empty(trim($masterText))) {
                        $content .= $masterText . "\n";
                    }
                } else {
                    break;
                }
            }
            
            $zip->close();

            if (empty(trim($content))) {
                throw new \Exception('No readable text content found in PowerPoint file. The presentation might contain only images or have no text content.');
            }

            $cleanedText = $this->cleanExtractedText($content);
            
            if (strlen($cleanedText) < 50) {
                throw new \Exception('PowerPoint contains very little readable text. Please ensure your presentation has substantial text content for quiz generation.');
            }

            return $cleanedText;

        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'Cannot open PowerPoint') !== false) {
                throw $e;
            }
            throw new \Exception('Error processing PowerPoint file: ' . $e->getMessage());
        }
    }

    private function extractTextFromXml(string $xml): string
    {
        try {
            $dom = new \DOMDocument();
            $dom->loadXML($xml);
            
            $xpath = new \DOMXPath($dom);
            $textNodes = $xpath->query('//w:t');
            
            $text = '';
            foreach ($textNodes as $node) {
                $text .= $node->nodeValue . ' ';
            }
            
            return $text;
        } catch (\Exception $e) {
            $text = strip_tags($xml);
            $text = html_entity_decode($text);
            return $text;
        }
    }

    private function extractTextFromPowerPointXml(string $xml): string
    {
        try {
            $dom = new \DOMDocument();
            $dom->loadXML($xml);
            
            $xpath = new \DOMXPath($dom);
            
            // PowerPoint uses a:t for text nodes
            $textNodes = $xpath->query('//a:t');
            
            $text = '';
            foreach ($textNodes as $node) {
                $nodeText = trim($node->nodeValue);
                if (!empty($nodeText)) {
                    $text .= $nodeText . ' ';
                }
            }
            
            return $text;
        } catch (\Exception $e) {
            // Fallback: simple text extraction
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
        $allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx'];

        if (!in_array(strtolower($extension), $allowedExtensions)) {
            return false;
        }

        // Check file size (max 15MB for PowerPoint files)
        $fileSize = Storage::size($filePath);
        if ($fileSize > 15 * 1024 * 1024) {
            return false;
        }

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

        if ($wordCount < 15) {
            $validation['isValid'] = false;
            $validation['errors'][] = "Content too short - extracted only {$wordCount} words. Need at least 15 words for meaningful quiz generation.";
        } elseif ($wordCount < 30) {
            $validation['warnings'][] = "Content is quite short ({$wordCount} words). Consider uploading a document with more content for better quiz generation.";
        }

        if ($characterCount < 75) {
            $validation['isValid'] = false;
            $validation['errors'][] = "Content too brief - extracted only {$characterCount} characters. Need substantial text content.";
        }

        $alphaNumericCount = preg_match_all('/[a-zA-Z0-9]/', $content);
        $readableRatio = $characterCount > 0 ? $alphaNumericCount / $characterCount : 0;
        $validation['readableRatio'] = $readableRatio;

        if ($readableRatio < 0.25) {
            $validation['isValid'] = false;
            $validation['errors'][] = "Content appears to contain mostly non-readable characters. The file might be corrupted, password-protected, or contain mostly images.";
        } elseif ($readableRatio < 0.4) {
            $validation['warnings'][] = "Content has a low ratio of readable text. Quiz quality might be affected.";
        }

        $sentences = preg_split('/[.!?]+/', $content);
        $meaningfulSentences = array_filter($sentences, function($sentence) {
            $sentence = trim($sentence);
            return strlen($sentence) > 10 && str_word_count($sentence) >= 3;
        });

        if (count($meaningfulSentences) < 3) {
            $validation['isValid'] = false;
            $validation['errors'][] = "Content contains very few complete sentences. The file might not be suitable for quiz generation.";
        }

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