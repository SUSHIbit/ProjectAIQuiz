<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuizAttemptController extends Controller
{
    public function start(Quiz $quiz)
    {
        $user = auth()->user();

        // Check if user can take this quiz
        if (!$quiz->canBeTakenBy($user)) {
            abort(403, 'You do not have permission to take this quiz.');
        }

        // Check for existing in-progress attempt
        $existingAttempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existingAttempt) {
            // Check if it has timed out
            if ($existingAttempt->hasTimedOut()) {
                $existingAttempt->markAsAbandoned();
                return redirect()->route('quiz.attempt.result', $existingAttempt->id)
                    ->with('warning', 'Your previous attempt timed out.');
            }

            // Resume existing attempt
            return redirect()->route('quiz.attempt.take', $existingAttempt->id);
        }

        $quiz->load('quizItems');

        return view('quiz.attempt.start', compact('quiz'));
    }

    public function create(Request $request, Quiz $quiz)
    {
        $request->validate([
            'enable_timer' => 'boolean',
            'timer_duration' => 'integer|min:1|max:120',
        ]);

        $user = auth()->user();

        // Check if user can take this quiz
        if (!$quiz->canBeTakenBy($user)) {
            abort(403, 'You do not have permission to take this quiz.');
        }

        // Check for existing in-progress attempt
        $existingAttempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existingAttempt) {
            return redirect()->route('quiz.attempt.take', $existingAttempt->id);
        }

        try {
            DB::beginTransaction();

            // Create new attempt
            $settings = [];
            $timeLimit = null;

            // Timer settings (Premium feature)
            if ($user->isPremium() && $request->enable_timer) {
                $timeLimit = $request->timer_duration * 60; // Convert to seconds
                $settings['timer_enabled'] = true;
                $settings['timer_duration'] = $request->timer_duration;
            }

            $attempt = QuizAttempt::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'total_questions' => $quiz->total_questions,
                'time_limit' => $timeLimit,
                'started_at' => Carbon::now(),
                'settings' => $settings,
            ]);

            DB::commit();

            return redirect()->route('quiz.attempt.take', $attempt->id);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Quiz attempt creation error', [
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to start quiz. Please try again.');
        }
    }

    public function take(QuizAttempt $attempt)
    {
        $user = auth()->user();

        // Check permission
        if ($attempt->user_id !== $user->id) {
            abort(403, 'Unauthorized access to quiz attempt.');
        }

        // Check if already completed
        if ($attempt->isCompleted()) {
            return redirect()->route('quiz.attempt.result', $attempt->id);
        }

        // Check if timed out
        if ($attempt->hasTimedOut()) {
            $attempt->markAsAbandoned();
            return redirect()->route('quiz.attempt.result', $attempt->id)
                ->with('warning', 'Quiz time limit exceeded.');
        }

        $attempt->load(['quiz.quizItems', 'answers']);
        
        // Get current question (first unanswered or first question)
        $answeredQuestionIds = $attempt->answers->pluck('quiz_item_id')->toArray();
        $currentQuestion = $attempt->quiz->quizItems->firstWhere(function ($item) use ($answeredQuestionIds) {
            return !in_array($item->id, $answeredQuestionIds);
        }) ?? $attempt->quiz->quizItems->first();

        $currentIndex = $attempt->quiz->quizItems->search(function ($item) use ($currentQuestion) {
            return $item->id === $currentQuestion->id;
        });

        return view('quiz.attempt.take', compact('attempt', 'currentQuestion', 'currentIndex'));
    }

    public function answer(Request $request, QuizAttempt $attempt)
    {
        $request->validate([
            'quiz_item_id' => 'required|exists:quiz_items,id',
            'answer' => 'required|integer|min:1|max:4',
            'time_spent' => 'integer|min:0',
        ]);

        $user = auth()->user();

        // Check permission
        if ($attempt->user_id !== $user->id) {
            abort(403, 'Unauthorized access to quiz attempt.');
        }

        // Check if already completed or timed out
        if ($attempt->isCompleted() || $attempt->hasTimedOut()) {
            return response()->json(['success' => false, 'message' => 'Quiz is no longer active.'], 400);
        }

        try {
            $quizItemId = $request->quiz_item_id;
            $selectedAnswer = $request->answer;
            $timeSpent = $request->time_spent ?? 0;

            // Get the quiz item
            $quizItem = $attempt->quiz->quizItems()->findOrFail($quizItemId);
            
            // Check if already answered
            $existingAnswer = $attempt->answers()->where('quiz_item_id', $quizItemId)->first();
            if ($existingAnswer) {
                return response()->json(['success' => false, 'message' => 'Question already answered.'], 400);
            }

            // Create answer record
            $isCorrect = $quizItem->correct_answer === $selectedAnswer;
            
            QuizAttemptAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'quiz_item_id' => $quizItemId,
                'selected_answer' => $selectedAnswer,
                'is_correct' => $isCorrect,
                'time_spent' => $timeSpent,
            ]);

            // Check if all questions answered
            $totalAnswered = $attempt->answers()->count();
            $isComplete = $totalAnswered >= $attempt->quiz->total_questions;

            if ($isComplete) {
                $attempt->markAsCompleted();
                return response()->json([
                    'success' => true,
                    'completed' => true,
                    'redirect' => route('quiz.attempt.result', $attempt->id)
                ]);
            }

            // Get next question
            $answeredQuestionIds = $attempt->answers()->pluck('quiz_item_id')->toArray();
            $nextQuestion = $attempt->quiz->quizItems->firstWhere(function ($item) use ($answeredQuestionIds) {
                return !in_array($item->id, $answeredQuestionIds);
            });

            return response()->json([
                'success' => true,
                'completed' => false,
                'next_question_id' => $nextQuestion->id ?? null,
                'questions_remaining' => $attempt->quiz->total_questions - $totalAnswered,
            ]);

        } catch (\Exception $e) {
            \Log::error('Quiz answer submission error', [
                'attempt_id' => $attempt->id,
                'quiz_item_id' => $request->quiz_item_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to submit answer.'], 500);
        }
    }

    public function submit(QuizAttempt $attempt)
    {
        $user = auth()->user();

        // Check permission
        if ($attempt->user_id !== $user->id) {
            abort(403, 'Unauthorized access to quiz attempt.');
        }

        // Check if already completed
        if ($attempt->isCompleted()) {
            return redirect()->route('quiz.attempt.result', $attempt->id);
        }

        $attempt->markAsCompleted();

        return redirect()->route('quiz.attempt.result', $attempt->id)
            ->with('success', 'Quiz submitted successfully!');
    }

    public function result(QuizAttempt $attempt)
    {
        $user = auth()->user();

        // Check permission
        if ($attempt->user_id !== $user->id) {
            abort(403, 'Unauthorized access to quiz attempt.');
        }

        // Ensure attempt is completed or abandoned
        if ($attempt->isInProgress()) {
            return redirect()->route('quiz.attempt.take', $attempt->id);
        }

        $attempt->load(['quiz', 'answers.quizItem']);

        // Get previous attempts for comparison
        $previousAttempts = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $attempt->quiz_id)
            ->where('id', '!=', $attempt->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('quiz.attempt.result', compact('attempt', 'previousAttempts'));
    }

    public function abandon(QuizAttempt $attempt)
    {
        $user = auth()->user();

        // Check permission
        if ($attempt->user_id !== $user->id) {
            abort(403, 'Unauthorized access to quiz attempt.');
        }

        // Check if already completed
        if ($attempt->isCompleted()) {
            return redirect()->route('quiz.attempt.result', $attempt->id);
        }

        $attempt->markAsAbandoned();

        return redirect()->route('quiz.show', $attempt->quiz_id)
            ->with('info', 'Quiz attempt abandoned.');
    }

    public function history(Quiz $quiz)
    {
        $user = auth()->user();

        // Check if user can view this quiz
        if (!$quiz->canBeTakenBy($user)) {
            abort(403, 'You do not have permission to view this quiz history.');
        }

        $attempts = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('quiz.attempt.history', compact('quiz', 'attempts'));
    }
}