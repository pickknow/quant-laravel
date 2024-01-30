<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AkshareService;
use Illuminate\Contracts\Foundation\Application;
use App\Interfaces\AshareInterface;

class AshareServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(AshareInterface::class, function (Application $app) {
            return new AkshareService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    
}
