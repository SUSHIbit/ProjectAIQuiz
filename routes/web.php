<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TierController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\ManualQuizController;
use App\Http\Controllers\QuizAttemptController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// File upload routes
Route::post('/upload-file', [WelcomeController::class, 'uploadFile'])->name('file.upload');
Route::delete('/remove-file', [WelcomeController::class, 'removeFile'])->name('file.remove');

// Public payment callback routes (no auth required for ToyyibPay callbacks)
Route::prefix('payment')->name('payment.')->group(function () {
    Route::post('/callback', [PaymentController::class, 'callback'])->name('callback');
    Route::get('/return', [PaymentController::class, 'return'])->name('return');
});

// Tier management routes
Route::middleware('auth')->group(function () {
    Route::get('/tier/upgrade', [TierController::class, 'upgrade'])->name('tier.upgrade');
    Route::get('/tier/compare', [TierController::class, 'compare'])->name('tier.compare');
    Route::post('/tier/upgrade', [TierController::class, 'processUpgrade'])->name('tier.process-upgrade');
    Route::post('/tier/decrement-attempts', [TierController::class, 'decrementAttempts'])->name('tier.decrement');
});

// Payment routes (protected by auth)
Route::middleware('auth')->prefix('payment')->name('payment.')->group(function () {
    Route::post('/initiate', [PaymentController::class, 'initiate'])->name('initiate');
    Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
    Route::get('/{payment}/status', [PaymentController::class, 'status'])->name('status');
    Route::post('/{payment}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    Route::get('/{payment}/success', [PaymentController::class, 'success'])->name('success');
    Route::get('/{payment}/failed', [PaymentController::class, 'failed'])->name('failed');
    Route::get('/history/list', [PaymentController::class, 'history'])->name('history');
    
    // Debug route (remove in production)
    Route::get('/debug/config', [PaymentController::class, 'debug'])->name('debug');
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

// Analytics Routes
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

// Flashcard Routes (Premium only)
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

// Test Routes (ONLY for development/testing - remove in production)
Route::middleware('auth')->prefix('test')->name('test.')->group(function () {
    Route::get('/payment', function () {
        return view('test-payment');
    })->name('payment');
    
    Route::get('/payment/config', function () {
        return response()->json([
            'api_key' => config('services.toyyibpay.api_key') ? 'Set ✅' : 'Not Set ❌',
            'category_code' => config('services.toyyibpay.category_code') ? 'Set ✅' : 'Not Set ❌',
            'base_url' => config('services.toyyibpay.base_url'),
            'sandbox' => config('services.toyyibpay.sandbox') ? 'Enabled ✅' : 'Disabled',
            'callback_url' => route('payment.callback'),
            'return_url' => route('payment.return'),
        ]);
    })->name('payment.config');
});

// Debug route (temporary - remove in production)
Route::middleware('auth')->get('/debug/toyyibpay', function() {
    try {
        $config = [
            'api_key' => config('services.toyyibpay.api_key') ? 'SET (length: ' . strlen(config('services.toyyibpay.api_key')) . ')' : 'NOT SET',
            'category_code' => config('services.toyyibpay.category_code') ?: 'NOT SET',
            'base_url' => config('services.toyyibpay.base_url'),
            'sandbox' => config('services.toyyibpay.sandbox') ? 'true' : 'false',
            'callback_url' => route('payment.callback'),
            'return_url' => route('payment.return'),
        ];
        
        return response()->json($config);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
})->name('debug.toyyibpay');

require __DIR__.'/auth.php';