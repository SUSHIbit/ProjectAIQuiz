<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Add 'expired' status if not exists
            $table->dropColumn('status');
        });
        
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending')->after('payment_ref');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending')->after('payment_ref');
        });
    }
};