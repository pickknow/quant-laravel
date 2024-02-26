<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Decorator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'decorate';
    }
}

