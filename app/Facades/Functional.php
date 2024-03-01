<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Functional extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'functional';
    }
}
