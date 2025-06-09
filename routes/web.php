<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TierController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\ManualQuizController;
use App\Http\Controllers\QuizAttemptController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// File upload routes
Route::post('/upload-file', [WelcomeController::class, 'uploadFile'])->name('file.upload');
Route::delete('/remove-file', [WelcomeController::class, 'removeFile'])->name('file.remove');

// Tier management routes
Route::middleware('auth')->group(function () {
    Route::get('/tier/upgrade', [TierController::class, 'upgrade'])->name('tier.upgrade');
    Route::get('/tier/compare', [TierController::class, 'compare'])->name('tier.compare');
    Route::post('/tier/upgrade', [TierController::class, 'processUpgrade'])->name('tier.process-upgrade');
    Route::post('/tier/decrement-attempts', [TierController::class, 'decrementAttempts'])->name('tier.decrement');
});

// Quiz routes (protected by auth and tier middleware)
Route::middleware(['auth', 'tier'])->prefix('quiz')->name('quiz.')->group(function () {
    Route::get('/generator', [QuizController::class, 'generator'])->name('generator');
    Route::post('/generate', [QuizController::class, 'generate'])->name('generate');
    Route::get('/edit', [QuizController::class, 'edit'])->name('edit');
    Route::post('/store', [QuizController::class, 'store'])->name('store');
});

// Quiz management routes (no tier restriction needed for viewing saved quizzes)
Route::middleware('auth')->prefix('quiz')->name('quiz.')->group(function () {
    Route::get('/', [QuizController::class, 'index'])->name('index');
    Route::get('/{quiz}', [QuizController::class, 'show'])->name('show');
    
    // PDF Export routes (PHASE 9)
    Route::get('/{quiz}/export-pdf', [QuizController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/{quiz}/export-pdf-blank', [QuizController::class, 'exportPdfBlank'])->name('export.pdf-blank');
    Route::get('/{quiz}/preview-pdf', [QuizController::class, 'previewPdf'])->name('preview.pdf');
});

// Manual Quiz Creator routes (available to all authenticated users)
Route::middleware('auth')->prefix('manual-quiz')->name('manual-quiz.')->group(function () {
    Route::get('/create', [ManualQuizController::class, 'create'])->name('create');
    Route::post('/store', [ManualQuizController::class, 'store'])->name('store');
    Route::get('/{quiz}/edit', [ManualQuizController::class, 'edit'])->name('edit');
    Route::put('/{quiz}', [ManualQuizController::class, 'update'])->name('update');
    Route::delete('/{quiz}', [ManualQuizController::class, 'destroy'])->name('destroy');
});

// Quiz Attempt routes
Route::middleware('auth')->prefix('quiz-attempt')->name('quiz.attempt.')->group(function () {
    Route::get('/{quiz}/start', [QuizAttemptController::class, 'start'])->name('start');
    Route::post('/{quiz}/create', [QuizAttemptController::class, 'create'])->name('create');
    Route::get('/{attempt}/take', [QuizAttemptController::class, 'take'])->name('take');
    Route::post('/{attempt}/answer', [QuizAttemptController::class, 'answer'])->name('answer');
    Route::post('/{attempt}/submit', [QuizAttemptController::class, 'submit'])->name('submit');
    Route::post('/{attempt}/abandon', [QuizAttemptController::class, 'abandon'])->name('abandon');
    Route::get('/{attempt}/result', [QuizAttemptController::class, 'result'])->name('result');
    Route::get('/{quiz}/history', [QuizAttemptController::class, 'history'])->name('history');
    Route::get('/{attempt}/timer-status', [QuizAttemptController::class, 'checkTimer'])->name('timer-status');
});

// User Dashboard (protected by auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes (protected by admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
});

Route::middleware(['auth'])->group(function () {
    // User Analytics
    Route::get('/analytics', [AnalyticsController::class, 'userDashboard'])->name('analytics.dashboard');
    Route::get('/analytics/subject', [AnalyticsController::class, 'subjectAnalytics'])->name('analytics.subject');
    Route::get('/analytics/export', [AnalyticsController::class, 'exportUserData'])->name('analytics.export');
    
    // Admin Analytics (Protected by admin middleware)
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/analytics', [AnalyticsController::class, 'adminAnalytics'])->name('admin.analytics');
    });
});

Route::middleware(['auth', 'premium'])->prefix('flashcards')->name('flashcards.')->group(function () {
    Route::get('/', [FlashcardController::class, 'index'])->name('index');
    Route::get('/create', [FlashcardController::class, 'create'])->name('create');
    Route::post('/store', [FlashcardController::class, 'store'])->name('store');
    Route::get('/{flashcard}', [FlashcardController::class, 'show'])->name('show');
    Route::get('/{flashcard}/edit', [FlashcardController::class, 'edit'])->name('edit');
    Route::put('/{flashcard}', [FlashcardController::class, 'update'])->name('update');
    Route::delete('/{flashcard}', [FlashcardController::class, 'destroy'])->name('destroy');
    
    // Study routes
    Route::get('/study/session', [FlashcardController::class, 'study'])->name('study');
    Route::post('/{flashcard}/mark-studied', [FlashcardController::class, 'markStudied'])->name('mark-studied');
    
    // AI Generation routes
    Route::get('/ai/generator', [FlashcardController::class, 'aiGenerator'])->name('ai.generator');
    Route::post('/ai/generate', [FlashcardController::class, 'generateAI'])->name('ai.generate');
    
    // Bulk operations
    Route::delete('/bulk/delete', [FlashcardController::class, 'bulkDelete'])->name('bulk.delete');
});

require __DIR__.'/auth.php';