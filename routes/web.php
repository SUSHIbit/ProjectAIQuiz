<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TierController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ManualQuizController;
use App\Http\Controllers\QuizAttemptController;
use Illuminate\Support\Facades\Route;

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
});

// Manual Quiz Creator routes (available to all authenticated users)
Route::middleware('auth')->prefix('manual-quiz')->name('manual-quiz.')->group(function () {
    Route::get('/create', [ManualQuizController::class, 'create'])->name('create');
    Route::post('/store', [ManualQuizController::class, 'store'])->name('store');
    Route::get('/{quiz}/edit', [ManualQuizController::class, 'edit'])->name('edit');
    Route::put('/{quiz}', [ManualQuizController::class, 'update'])->name('update');
    Route::delete('/{quiz}', [ManualQuizController::class, 'destroy'])->name('destroy');
});

// Quiz Attempt routes (PHASE 7 ENHANCED)
Route::middleware('auth')->prefix('quiz-attempt')->name('quiz.attempt.')->group(function () {
    Route::get('/{quiz}/start', [QuizAttemptController::class, 'start'])->name('start');
    Route::post('/{quiz}/create', [QuizAttemptController::class, 'create'])->name('create');
    Route::get('/{attempt}/take', [QuizAttemptController::class, 'take'])->name('take');
    Route::post('/{attempt}/answer', [QuizAttemptController::class, 'answer'])->name('answer');
    Route::post('/{attempt}/submit', [QuizAttemptController::class, 'submit'])->name('submit');
    Route::post('/{attempt}/abandon', [QuizAttemptController::class, 'abandon'])->name('abandon');
    Route::get('/{attempt}/result', [QuizAttemptController::class, 'result'])->name('result');
    Route::get('/{quiz}/history', [QuizAttemptController::class, 'history'])->name('history');
    // NEW: Timer status check route
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

require __DIR__.'/auth.php';