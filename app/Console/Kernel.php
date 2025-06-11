<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check for expired subscriptions daily at 3 AM
        $schedule->command('subscriptions:check-expired')
                 ->dailyAt('03:00')
                 ->emailOutputOnFailure('admin@example.com');

        // You can also run it more frequently during testing
        // $schedule->command('subscriptions:check-expired')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}