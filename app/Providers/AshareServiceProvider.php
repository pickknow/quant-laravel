<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AkshareService;
use Illuminate\Contracts\Foundation\Application;
use App\Interfaces\AshareInterface;
use App\Services\CurlService;
use App\Strategies\AshareStrategy;
use Illuminate\Support\Collection;

class AshareServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

        $this->app->singleton(CurlService::class, function () {
            return new CurlService(env('PYTHON_URL'));
        });

        $this->app->singleton(AshareInterface::class, function (Application $app) {
            return new AshareStrategy($app->make(AkshareService::class));
        });

        $this->app->singleton(AkshareService::class, function (Application $app) {
            return new AkshareService($app->make(CurlService::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        Collection::macro('eachThrough', function ($fns) {
            return $this->map(fn ($value) => array_reduce($fns, fn ($p, $n) => $n($p), $value));
        });

        Collection::macro('reduceToOne', function ($fn, $init) {
            return [$this->reduce($fn, $init)];
        });
    }
}
