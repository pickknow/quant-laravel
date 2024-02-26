<?php
namespace App\Helps;


class Decorator 
{
    public static function cache($key)
    {
        global $cache;

        // Return a parameterized decorator function
        $decorator = function (callable $fn) use ($cache, $key) {
            $wrapper = function () use ($fn, $cache, $key) {
                if ($cache->contains($key)) {
                    return $cache->fetch($key);
                } else {
                    $data = call_user_func_array($fn, func_get_args());

                    $cache->save($key, $data);

                    return $data;
                }
            };

            return $wrapper;
        };

        return $decorator;
    }
}
