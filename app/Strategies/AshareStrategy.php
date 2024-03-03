<?php

namespace App\Strategies;

use App\Models\Industry;
use App\Interfaces\AshareInterface;
use APP\Services\AkshareService;

class AshareStrategy implements AshareInterface
{

    public function __construct(protected AkshareService $aShare)
    {
    }

    public function __call(string $name,  array | null $data = null)
    {
        [$func, $after, $before] = ["_" . $name, $name . "_after", $name . "_before"];
        $data = method_exists($this, $before) ? $this->$before($data) : $data;
        $data = method_exists($this, $func) ? $this->$func($name, $data) : $data;
        $data = method_exists($this, $after) ? $this->$after($data) : $data;
        return $data;
    }
    public function test()
    {
        print "this is a default test in AshareStrategy.";
    }

    // get all industries, this only run once.
    public function _industries(String $name, $data)
    {
        return $this->aShare->$name(...$data);
    }
    public function industries_after($data)
    {
        return Industry::zipCreate($data);
    }

    public function industries_before($data)
    {
        return Industry::preventDouble($data);
    }

    // fetch all industries' stocks and save them.
    public function _stocksOfIndustry(String $name, $data)
    {
        array_map(function ($industry) use ($name) {
            $result = array_reduce(
                $this->aShare->$name(symbol: $industry->name),
                fn ($p, $n) => [[...$p[0], $n[1]], [...$p[1], $n[1] . $n[2]]],
                [[], []]
            );
            $industry->nums = implode(',', $result[0]);
            $industry->nums_names = implode(',', $result[0]);
            $industry->save();
        }, Industry::all() ?: []);
    }
}
