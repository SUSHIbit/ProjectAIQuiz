<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->integer('total_questions');
            $table->integer('correct_answers')->default(0);
            $table->integer('time_taken')->nullable(); // in seconds
            $table->integer('time_limit')->nullable(); // in seconds
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->json('settings')->nullable(); // Store quiz settings like timer, etc.
            $table->timestamps();

            $table->index(['user_id', 'quiz_id']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};