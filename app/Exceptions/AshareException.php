<?php

namespace App\Exceptions;

use Exception;

class AshareException extends Exception
{
    //
    public string $name;

    public function __construct(string $name = '')
    {
       $this->name = $name; 
    }

    public function context() : array
    {
        return ['message' => $this->name];
    }
}
