<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expired';
    protected $description = 'Check for expired subscriptions and downgrade users';

    public function handle()
    {
        $this->info('Checking for expired subscriptions...');
        
        // Find all expired subscriptions that are still marked as active
        $expiredPayments = Payment::expiredSubscriptions()->get();
        
        $downgraded = 0;
        
        foreach ($expiredPayments as $payment) {
            $this->info("Processing expired subscription for user {$payment->user_id} (Payment ID: {$payment->id})");
            
            // Mark payment as expired
            $payment->markAsExpired();
            
            $downgraded++;
        }
        
        $this->info("Processed {$expiredPayments->count()} expired subscriptions.");
        $this->info("Downgraded {$downgraded} users to free tier.");
        
        // Also check for users who still have premium tier but no active subscription
        $orphanedPremiumUsers = User::where('tier', 'premium')
            ->whereDoesntHave('payments', function ($query) {
                $query->where('status', 'success')
                      ->where('subscription_status', 'active')
                      ->where('subscription_expires_at', '>', now());
            })
            ->get();
        
        foreach ($orphanedPremiumUsers as $user) {
            $this->info("Found orphaned premium user {$user->id}, downgrading to free");
            $user->update([
                'tier' => 'free',
                'question_attempts' => 3
            ]);
            $downgraded++;
        }
        
        $this->info("Found and fixed {$orphanedPremiumUsers->count()} orphaned premium users.");
        $this->info('Subscription check completed successfully.');
        
        return 0;
    }
}