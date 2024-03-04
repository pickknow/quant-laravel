<?php

namespace App\Strategies;

use App\Models\Industry;
use App\Interfaces\AshareInterface;
use App\Services\AkshareService;
use App\Helps\Functools;

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

    public function _stocksOfIndustry(String $name, $data)
    {
        Functools::of(Industry::all())
            ->map(function ($i) use ($name) {
                Functools::of($this->aShare->$name(symbol: $i->name))
                    ->reduce(fn ($p, $n) => [[...$p[0], $n[1]], [...$p[1], $n[1] . $n[2]]], [[], []])
                    ->map(fn ($r) => Industry::where('id', $i->id)->update([
                        'nums' => implode(',', $r[0]),
                        'nums_names' => implode(',', $r[1])
                    ]));
            });
    }
}
