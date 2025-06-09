<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            // Add indexes for better analytics performance
            $table->index(['user_id', 'status', 'created_at'], 'user_status_created_idx');
            $table->index(['status', 'score', 'created_at'], 'status_score_created_idx');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            // Add indexes for subject/topic analytics
            $table->index(['user_id', 'subject', 'created_at'], 'user_subject_created_idx');
            $table->index(['subject', 'topic', 'created_at'], 'subject_topic_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropIndex('user_status_created_idx');
            $table->dropIndex('status_score_created_idx');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropIndex('user_subject_created_idx');
            $table->dropIndex('subject_topic_created_idx');
        });
    }
};