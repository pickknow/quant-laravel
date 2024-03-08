<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Functools extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'functools';
    }
}
