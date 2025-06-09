<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TierController;
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

// Quiz generator route (now protected by tier middleware)
Route::get('/quiz/generator', function () {
    return view('quiz.generator');
})->middleware(['auth', 'tier'])->name('quiz.generator');

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