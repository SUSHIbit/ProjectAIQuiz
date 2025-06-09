<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_item_id')->constrained()->onDelete('cascade');
            $table->integer('selected_answer'); // 1, 2, 3, or 4
            $table->boolean('is_correct');
            $table->integer('time_spent')->nullable(); // seconds spent on this question
            $table->timestamps();

            $table->unique(['quiz_attempt_id', 'quiz_item_id']);
            $table->index(['quiz_attempt_id', 'is_correct']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_answers');
    }
};