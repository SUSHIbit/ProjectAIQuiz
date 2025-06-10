<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('toyyibpay_bill_code')->nullable()->after('payment_ref');
            $table->string('toyyibpay_bill_external_ref')->nullable()->after('toyyibpay_bill_code');
            $table->string('toyyibpay_category_code')->nullable()->after('toyyibpay_bill_external_ref');
            $table->text('toyyibpay_response')->nullable()->after('toyyibpay_category_code');
            $table->string('payment_method')->nullable()->after('toyyibpay_response');
            $table->timestamp('paid_at')->nullable()->after('payment_method');
            $table->json('callback_data')->nullable()->after('paid_at');
            
            $table->index(['toyyibpay_bill_code']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['toyyibpay_bill_code']);
            $table->dropIndex(['status', 'created_at']);
            
            $table->dropColumn([
                'toyyibpay_bill_code',
                'toyyibpay_bill_external_ref', 
                'toyyibpay_category_code',
                'toyyibpay_response',
                'payment_method',
                'paid_at',
                'callback_data'
            ]);
        });
    }
};