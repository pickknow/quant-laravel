<?php

namespace App\Helps;

use Illuminate\Support\Collection;

function curryHandler($callback, $args, $length)
{
    if ($length <= 0) {
        return call_user_func_array($callback, $args);
    }
    return function () use ($callback, $args, $length) {
        $newArgs = array_merge($args, func_get_args());
        return curryHandler($callback, $newArgs, $length - count($newArgs));
    };
}

class ArrayHelpers
{
    protected $fns = [];

    public function __construct(public $value = [])
    {
    }

    public static function of($arr = [])
    {
        return new ArrayHelpers($arr);
    }

    public function __call(string $name, $arr): ArrayHelpers
    {
        $call = "_" . $name;
        $this->value = method_exists($this, $call)
            ? $this->$call(...$arr)
            : $this->value;
        return $this;
    }

    public function _map($func): array
    {
        return gettype($this->value) == "array"
            ? array_map(fn ($item) => $func($item), $this->value)
            : $func($this->value);
    }
    /**
     *   @return a array with one element array[any]
     */
    public function _reduce($func, $init = null): array
    {
        if (count($this->value) < 1) {
            return $this;
        }
        return $init
            ? [array_reduce($this->value, fn ($p, $n) => $func($p, $n), $init)]
            : [
                array_reduce(
                    array_slice($this->value, 1),
                    fn ($p, $n) => $func($p, $n),
                    $this->value[0]
                ),
            ];
    }
    public function _filter($func): array
    {
        return array_filter($this->value, fn ($item) => $func($item));
    }

    // this function holds a function set, when receives 'donw', apply the functions to every $this->value
    public function _each($func)
    {
        if ($func == "done" && count($this->fns) > 0) {
            $this->value = array_map(
                fn ($item) => array_reduce(
                    $this->fns,
                    fn ($p, $n) => $n($p),
                    $item
                ),
                $this->value
            );
        } else {
            $this->fns[] = $func;
        }
    }
    // apply some functions to each $this->value;
    public function _through($funcs)
    {
        return array_map(
            fn ($item) => array_reduce($funcs, fn ($p, $n) => $n($p), $item),
            $this->value
        );
    }
    public function _tap($func)
    {
        $func($this->value);
        return $this->value;
    }
    public function _apply($func)
    {
        return $func($this->value);
    }

    public function console()
    {
        env("APP_DEBUG") && dump($this->value); #only dump when debuging.
        return $this;
    }
    public function dd()
    {
        dd($this->value);
    }

    public function get()
    {
        return $this->value;
    }
}

class CollectHelpers
{
    public function __construct(public $value)
    {
    }
    public function __call(string $name, $arr) 
    {
        $call = "_" . $name;
        $this->value = method_exists($this, $call)
            ? $this->$call(...$arr)
            : $this->value->$name(...$arr);
        return $this;
    }
    public function reduce($fn){
        $this->value = collect([$this->value->reduce($fn)]);
        return $this;
    }

    public function dd()
    {
        dd($this->value);
    }

    public function get()
    {
        return $this->value;
    }
}


class Functools
{
    public static function of($value)
    {
        switch (gettype($value)) {
            case 'array':
                return new ArrayHelpers($value);
            case 'object':
                return new CollectHelpers($value);
        }
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
            return array_reduce(
                $args,
                function ($pre, $next) {
                    return $next($pre);
                },
                $input
            );
        };
    }
}
