<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizItem;
use App\Services\OpenAIService;
use App\Services\FileProcessorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class QuizController extends Controller
{
    private OpenAIService $openAIService;
    private FileProcessorService $fileProcessor;

    public function __construct(OpenAIService $openAIService, FileProcessorService $fileProcessor)
    {
        $this->openAIService = $openAIService;
        $this->fileProcessor = $fileProcessor;
    }

    public function generator()
    {
        $user = auth()->user();
        $uploadedFile = session('uploaded_file');

        if (!$uploadedFile) {
            return redirect()->route('welcome')
                ->with('error', 'Please upload a file first.');
        }

        return view('quiz.generator', compact('user', 'uploadedFile'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'question_count' => ['required', 'integer', 'min:5', 'max:30'],
        ]);

        $user = auth()->user();
        $uploadedFile = session('uploaded_file');

        if (!$uploadedFile) {
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded. Please upload a file first.',
            ], 400);
        }

        // Check if user can generate questions
        if (!$user->canGenerateQuestions()) {
            return response()->json([
                'success' => false,
                'message' => 'You have no AI generation attempts remaining. Please upgrade to Premium.',
            ], 403);
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
            
            if (empty(trim($extractedText))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not extract text from the uploaded file.',
                ], 400);
            }

            // Get question count based on user tier
            $questionCount = $this->getQuestionCountForUser($user, $request->question_count);

            // Generate questions using OpenAI
            $generatedQuestions = $this->openAIService->generateQuestions($extractedText, $questionCount);

            if (empty($generatedQuestions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate questions. Please try again.',
                ], 500);
            }

            // Decrement user attempts if they're on free tier
            if ($user->isFree()) {
                $user->decrementAttempts();
            }

            // Store generated questions in session for editing
            session(['generated_questions' => $generatedQuestions]);
            session(['file_info' => $uploadedFile]);

            return response()->json([
                'success' => true,
                'message' => 'Questions generated successfully!',
                'questions_count' => count($generatedQuestions),
                'redirect' => route('quiz.edit'),
            ]);

        } catch (\Exception $e) {
            \Log::error('Quiz generation error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating questions: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit()
    {
        $generatedQuestions = session('generated_questions');
        $fileInfo = session('file_info');

        if (!$generatedQuestions) {
            return redirect()->route('quiz.generator')
                ->with('error', 'No generated questions found. Please generate questions first.');
        }

        return view('quiz.edit', compact('generatedQuestions', 'fileInfo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'topic' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.option_1' => ['required', 'string'],
            'questions.*.option_2' => ['required', 'string'],
            'questions.*.option_3' => ['required', 'string'],
            'questions.*.option_4' => ['required', 'string'],
            'questions.*.correct_answer' => ['required', 'integer', 'min:1', 'max:4'],
            'questions.*.explanation' => ['nullable', 'string'],
        ]);

        try {
            DB::beginTransaction();

            // Create the quiz
            $quiz = Quiz::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'subject' => $request->subject,
                'topic' => $request->topic,
                'description' => $request->description,
                'source_type' => 'ai',
                'total_questions' => count($request->questions),
            ]);

            // Create quiz items
            foreach ($request->questions as $index => $questionData) {
                QuizItem::create([
                    'quiz_id' => $quiz->id,
                    'question' => $questionData['question'],
                    'option_1' => $questionData['option_1'],
                    'option_2' => $questionData['option_2'],
                    'option_3' => $questionData['option_3'],
                    'option_4' => $questionData['option_4'],
                    'correct_answer' => $questionData['correct_answer'],
                    'explanation' => $questionData['explanation'] ?? '',
                    'order' => $index + 1,
                ]);
            }

            DB::commit();

            // Clean up session data
            session()->forget(['generated_questions', 'file_info', 'uploaded_file']);

            // Clean up uploaded file
            $uploadedFile = session('uploaded_file');
            if ($uploadedFile && Storage::exists($uploadedFile['path'])) {
                Storage::delete($uploadedFile['path']);
            }

            return redirect()->route('quiz.show', $quiz->id)
                ->with('success', 'Quiz saved successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Quiz save error', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save quiz. Please try again.');
        }
    }

    public function show(Quiz $quiz)
    {
        // Ensure user can only view their own quizzes
        if ($quiz->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to quiz.');
        }

        $quiz->load('quizItems');
        
        return view('quiz.show', compact('quiz'));
    }

    public function index()
    {
        $user = auth()->user();
        $quizzes = Quiz::forUser($user->id)
            ->with('quizItems')
            ->latest()
            ->paginate(10);

        return view('quiz.index', compact('quizzes', 'user'));
    }

    private function getQuestionCountForUser($user, int $requestedCount): int
    {
        if ($user->isPremium()) {
            // Premium users can choose 10, 20, or 30
            return in_array($requestedCount, [10, 20, 30]) ? $requestedCount : 10;
        } else {
            // Free users get exactly 10 questions
            return 10;
        }
    }
}