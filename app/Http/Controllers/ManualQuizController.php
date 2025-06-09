<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManualQuizRequest;
use App\Models\Quiz;
use App\Models\QuizItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManualQuizController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        
        return view('manual-quiz.create', compact('user'));
    }

    public function store(ManualQuizRequest $request)
    {
        $user = auth()->user();

        try {
            DB::beginTransaction();

            // Create the quiz
            $quiz = Quiz::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'subject' => $request->subject,
                'topic' => $request->topic,
                'description' => $request->description,
                'source_type' => 'manual',
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

            return redirect()->route('quiz.show', $quiz->id)
                ->with('success', 'Manual quiz created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Manual quiz creation error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create quiz. Please try again.');
        }
    }

    public function edit(Quiz $quiz)
    {
        // Ensure user can only edit their own quizzes
        if ($quiz->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to quiz.');
        }

        // Only allow editing of manual quizzes
        if (!$quiz->isManual()) {
            return redirect()->route('quiz.show', $quiz->id)
                ->with('error', 'Only manual quizzes can be edited.');
        }

        $quiz->load('quizItems');
        
        return view('manual-quiz.edit', compact('quiz'));
    }

    public function update(ManualQuizRequest $request, Quiz $quiz)
    {
        // Ensure user can only edit their own quizzes
        if ($quiz->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to quiz.');
        }

        // Only allow editing of manual quizzes
        if (!$quiz->isManual()) {
            return redirect()->route('quiz.show', $quiz->id)
                ->with('error', 'Only manual quizzes can be edited.');
        }

        try {
            DB::beginTransaction();

            // Update quiz metadata
            $quiz->update([
                'title' => $request->title,
                'subject' => $request->subject,
                'topic' => $request->topic,
                'description' => $request->description,
                'total_questions' => count($request->questions),
            ]);

            // Delete existing quiz items
            $quiz->quizItems()->delete();

            // Create new quiz items
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

            return redirect()->route('quiz.show', $quiz->id)
                ->with('success', 'Quiz updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Manual quiz update error', [
                'user_id' => auth()->id(),
                'quiz_id' => $quiz->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update quiz. Please try again.');
        }
    }

    public function destroy(Quiz $quiz)
    {
        // Ensure user can only delete their own quizzes
        if ($quiz->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to quiz.');
        }

        try {
            $quiz->delete();

            return redirect()->route('quiz.index')
                ->with('success', 'Quiz deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Quiz deletion error', [
                'user_id' => auth()->id(),
                'quiz_id' => $quiz->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete quiz. Please try again.');
        }
    }
}