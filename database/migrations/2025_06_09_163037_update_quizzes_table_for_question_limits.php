<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Add max questions allowed for this quiz (based on user tier when created)
            if (!Schema::hasColumn('quizzes', 'max_questions_allowed')) {
                $table->integer('max_questions_allowed')->default(10)->after('total_questions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('max_questions_allowed');
        });
    }
};