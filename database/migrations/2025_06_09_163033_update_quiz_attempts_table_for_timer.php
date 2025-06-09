<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            // Ensure we have all necessary timer-related columns
            if (!Schema::hasColumn('quiz_attempts', 'time_limit')) {
                $table->integer('time_limit')->nullable()->after('time_taken'); // in seconds
            }
            if (!Schema::hasColumn('quiz_attempts', 'settings')) {
                $table->json('settings')->nullable()->after('time_limit'); // Store timer and other settings
            }
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn(['time_limit', 'settings']);
        });
    }
};