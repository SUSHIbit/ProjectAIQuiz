<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('plan_type', ['monthly', 'yearly'])->default('monthly')->after('amount');
            $table->timestamp('subscription_starts_at')->nullable()->after('paid_at');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_starts_at');
            $table->boolean('is_renewal')->default(false)->after('subscription_expires_at');
            $table->string('subscription_status')->default('active')->after('is_renewal'); // active, expired, cancelled
            
            $table->index(['subscription_status', 'subscription_expires_at']);
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['subscription_status', 'subscription_expires_at']);
            $table->dropColumn([
                'plan_type',
                'subscription_starts_at', 
                'subscription_expires_at',
                'is_renewal',
                'subscription_status'
            ]);
        });
    }
};