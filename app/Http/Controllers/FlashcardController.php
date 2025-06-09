<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Services\OpenAIService;
use App\Services\FileProcessorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FlashcardController extends Controller
{
    private OpenAIService $openAIService;
    private FileProcessorService $fileProcessor;

    public function __construct(OpenAIService $openAIService, FileProcessorService $fileProcessor)
    {
        $this->openAIService = $openAIService;
        $this->fileProcessor = $fileProcessor;
    }

    public function index()
    {
        $user = auth()->user();
        
        $flashcards = Flashcard::forUser($user->id)
            ->latest()
            ->paginate(12);

        $categories = Flashcard::getUserCategories($user->id);
        $stats = Flashcard::getStudyStats($user->id);

        return view('flashcards.index', compact('flashcards', 'categories', 'stats'));
    }

    public function create()
    {
        $user = auth()->user();
        $categories = Flashcard::getUserCategories($user->id);
        
        return view('flashcards.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'flashcards' => 'required|array|min:1|max:50',
            'flashcards.*.title' => 'required|string|max:255',
            'flashcards.*.front_text' => 'required|string|max:1000',
            'flashcards.*.back_text' => 'required|string|max:1000',
            'flashcards.*.category' => 'nullable|string|max:100',
            'flashcards.*.tags' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $createdCount = 0;

            foreach ($request->flashcards as $flashcardData) {
                Flashcard::create([
                    'user_id' => $user->id,
                    'title' => $flashcardData['title'],
                    'front_text' => $flashcardData['front_text'],
                    'back_text' => $flashcardData['back_text'],
                    'source_type' => 'manual',
                    'category' => $flashcardData['category'] ?? null,
                    'tags' => $flashcardData['tags'] ?? null,
                ]);
                $createdCount++;
            }

            DB::commit();

            return redirect()->route('flashcards.index')
                ->with('success', "Successfully created {$createdCount} flashcard(s)!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Manual flashcard creation error', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create flashcards. Please try again.');
        }
    }

    public function show(Flashcard $flashcard)
    {
        // Ensure user can only view their own flashcards
        if ($flashcard->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to flashcard.');
        }

        return view('flashcards.show', compact('flashcard'));
    }

    public function edit(Flashcard $flashcard)
    {
        // Ensure user can only edit their own flashcards
        if ($flashcard->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to flashcard.');
        }

        $categories = Flashcard::getUserCategories(auth()->id());
        
        return view('flashcards.edit', compact('flashcard', 'categories'));
    }

    public function update(Request $request, Flashcard $flashcard)
    {
        // Ensure user can only update their own flashcards
        if ($flashcard->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to flashcard.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'front_text' => 'required|string|max:1000',
            'back_text' => 'required|string|max:1000',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:255',
        ]);

        try {
            $flashcard->update([
                'title' => $request->title,
                'front_text' => $request->front_text,
                'back_text' => $request->back_text,
                'category' => $request->category,
                'tags' => $request->tags,
            ]);

            return redirect()->route('flashcards.show', $flashcard)
                ->with('success', 'Flashcard updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Flashcard update error', [
                'user_id' => auth()->id(),
                'flashcard_id' => $flashcard->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update flashcard. Please try again.');
        }
    }

    public function destroy(Flashcard $flashcard)
    {
        // Ensure user can only delete their own flashcards
        if ($flashcard->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to flashcard.');
        }

        try {
            $flashcard->delete();

            return redirect()->route('flashcards.index')
                ->with('success', 'Flashcard deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Flashcard deletion error', [
                'user_id' => auth()->id(),
                'flashcard_id' => $flashcard->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete flashcard. Please try again.');
        }
    }

    public function study(Request $request)
    {
        $user = auth()->user();
        $category = $request->get('category');
        
        $query = Flashcard::forUser($user->id);
        
        if ($category) {
            $query->byCategory($category);
        }
        
        $flashcards = $query->inRandomOrder()->get();
        
        if ($flashcards->isEmpty()) {
            return redirect()->route('flashcards.index')
                ->with('info', 'No flashcards available for study. Create some flashcards first!');
        }

        return view('flashcards.study', compact('flashcards', 'category'));
    }

    public function markStudied(Flashcard $flashcard)
    {
        // Ensure user can only mark their own flashcards as studied
        if ($flashcard->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to flashcard.');
        }

        $flashcard->markAsStudied();

        return response()->json([
            'success' => true,
            'study_count' => $flashcard->study_count,
            'last_studied' => $flashcard->last_studied_display,
        ]);
    }

    public function aiGenerator()
    {
        $user = auth()->user();
        $uploadedFile = session('uploaded_file');

        if (!$uploadedFile) {
            return redirect()->route('welcome')
                ->with('error', 'Please upload a file first to generate AI flashcards.');
        }

        return view('flashcards.ai-generator', compact('user', 'uploadedFile'));
    }

    public function generateAI(Request $request)
    {
        $request->validate([
            'flashcard_count' => ['required', 'integer', 'in:10,20,30'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $user = auth()->user();
        $uploadedFile = session('uploaded_file');

        if (!$uploadedFile) {
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded. Please upload a file first.',
            ], 400);
        }

        try {
            // Validate and process file
            $filePath = $uploadedFile['path'];
            
            if (!$this->fileProcessor->validateFile($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file format or file is corrupted.',
                ], 400);
            }

            // Extract text from file
            $extractedText = $this->fileProcessor->extractTextFromFile($filePath);
            
            // Validate extracted content
            $contentValidation = $this->fileProcessor->validateExtractedContent($extractedText);
            
            if (!$contentValidation['isValid']) {
                $errorMessage = 'Unable to extract sufficient content from file: ' . implode(', ', $contentValidation['errors']);
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 400);
            }

            // Generate flashcards using OpenAI
            $flashcardCount = $request->flashcard_count;
            $generatedFlashcards = $this->openAIService->generateFlashcards($extractedText, $flashcardCount);

            if (empty($generatedFlashcards)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate flashcards from the uploaded content.',
                ], 500);
            }

            // Store generated flashcards in database
            DB::beginTransaction();
            
            $savedFlashcards = [];
            $category = $request->category ?: 'AI Generated';
            
            foreach ($generatedFlashcards as $flashcardData) {
                $flashcard = Flashcard::create([
                    'user_id' => $user->id,
                    'title' => $flashcardData['title'],
                    'front_text' => $flashcardData['front_text'],
                    'back_text' => $flashcardData['back_text'],
                    'source_type' => 'ai',
                    'category' => $category,
                    'tags' => 'ai-generated',
                ]);
                
                $savedFlashcards[] = $flashcard;
            }

            DB::commit();

            // Clean up session data
            session()->forget(['uploaded_file']);
            
            // Clean up uploaded file
            if (Storage::exists($uploadedFile['path'])) {
                Storage::delete($uploadedFile['path']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Flashcards generated successfully!',
                'flashcards_count' => count($savedFlashcards),
                'redirect' => route('flashcards.index'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('AI flashcard generation error', [
                'user_id' => $user->id,
                'file' => $uploadedFile['original_name'] ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate flashcards. Please try again.',
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'flashcard_ids' => 'required|array',
            'flashcard_ids.*' => 'exists:flashcards,id',
        ]);

        $user = auth()->user();
        
        try {
            $deletedCount = Flashcard::whereIn('id', $request->flashcard_ids)
                ->where('user_id', $user->id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} flashcard(s).",
            ]);

        } catch (\Exception $e) {
            \Log::error('Bulk flashcard deletion error', [
                'user_id' => $user->id,
                'flashcard_ids' => $request->flashcard_ids,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete flashcards. Please try again.',
            ], 500);
        }
    }
}