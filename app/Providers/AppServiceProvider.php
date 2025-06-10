<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ToyyibpayService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register ToyyibpayService as singleton
        $this->app->singleton(ToyyibpayService::class, function ($app) {
            return new ToyyibpayService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}