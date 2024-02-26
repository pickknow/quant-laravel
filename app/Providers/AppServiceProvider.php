<?php

namespace App\Providers;

use App\Helps\Decorator;
use Illuminate\Support\ServiceProvider;
use App\Services\CurlService;
use App\Services\CustomizedErrorService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('CES', function() {
            return new CustomizedErrorService();
        });

        $this->app->bind('decorate', function() {
            return new Decorator();
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
