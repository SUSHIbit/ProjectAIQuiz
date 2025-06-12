<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update the default value for new users
        Schema::table('users', function (Blueprint $table) {
            $table->integer('question_attempts')->default(5)->change();
        });
        
        // Update existing free users who still have the old default (3)
        DB::table('users')
            ->where('tier', 'free')
            ->where('question_attempts', 3)
            ->update(['question_attempts' => 5]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('question_attempts')->default(3)->change();
        });
        
        // Revert existing free users back to 3
        DB::table('users')
            ->where('tier', 'free')
            ->where('question_attempts', 5)
            ->update(['question_attempts' => 3]);
    }
};