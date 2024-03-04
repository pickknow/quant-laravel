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

    public function __call(string $name, $arr)
    {
        $call = "_" . $name;
        $this->value = method_exists($this, $call) ? $this->$call(...$arr) : $this->value;
        return $this;
    }

    public function _map($func)
    {
        $this->value = array_map(fn ($item) => $func($item), $this->value);
    }
    public function _reduce($func, $init = null)
    {
        $this->value = $init
            ? [array_reduce($this->value, fn ($p, $n) => $func($p, $n), $init)]
            : [array_reduce($this->value, fn ($p, $n) => $func($p, $n))];
    }
    public function _filter($func)
    {
        $this->value = array_filter($this->value, fn ($item) => $func($item));
    }
    public function value()
    {
        return $this->value;
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
