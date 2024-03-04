<?php

namespace App\Helps;

function curryHandler($callback, $args, $length)
{
    if ($length <= 0) return call_user_func_array($callback, $args);
    return function () use ($callback, $args, $length) {
        $newArgs = array_merge($args, func_get_args());
        return curryHandler($callback, $newArgs, $length - count($newArgs));
    };
}


class ArrayHelpers
{

    public function __construct(public array $value = [])
    {
    }

    public function map($func)
    {
        $this->value = array_map(fn ($item) => $func($item), $this->value);
        return $this;
    }
    public function reduce($func, $init = null)
    {
        $this->value = $init
            ? [array_reduce($this->value, fn ($p, $n) => $func($p, $n), $init)]
            : [array_reduce($this->value, fn ($p, $n) => $func($p, $n))];
        return $this;
    }
    public function filter($func)
    {
        $this->value = array_filter($this->value, fn ($item) => $func($item));
        return $this;
    }
}
class Functools
{



    public static function arr($arr = [])
    {
        return  new ArrayHelpers($arr);
    }

    public static function of($arr = [])
    {
        return  new ArrayHelpers($arr);
    }

    public static function curry($callback)
    {
        $args = array_slice(func_get_args(), 1);
        $refl = new \ReflectionFunction($callback);
        $length = $refl->getNumberOfRequiredParameters();
        return curryHandler($callback, $args, $length);
    }


    public static function compose()
    {
        $args = array_reverse(func_get_args());
        return function ($input) use ($args) {
            return array_reduce($args, function ($pre, $next) {
                return $next($pre);
            }, $input);
        };
    }
}
