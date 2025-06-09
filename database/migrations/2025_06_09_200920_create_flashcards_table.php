<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flashcards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('front_text');
            $table->text('back_text');
            $table->enum('source_type', ['manual', 'ai'])->default('manual');
            $table->string('category')->nullable();
            $table->string('tags')->nullable(); // JSON string for tags
            $table->integer('study_count')->default(0);
            $table->timestamp('last_studied_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'category']);
            $table->index('source_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flashcards');
    }
};