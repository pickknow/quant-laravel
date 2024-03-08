<?php

namespace App\Providers;

use App\Helps\Decorator;
use Illuminate\Support\ServiceProvider;
// use App\Services\CurlService;
use App\Services\CustomizedErrorService;
use App\Helps\Functools;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('CES', function () {
            return new CustomizedErrorService();
        });

        $this->app->bind('decorate', function () {
            return new Decorator();
        });

        $this->app->bind('functools', function () {
            return new Functools();
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
