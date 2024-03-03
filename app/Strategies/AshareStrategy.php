<?php

namespace App\Strategies;

use App\Models\Industry;
use App\Interfaces\AshareInterface;
use App\Exceptions\AshareException;
use APP\Services\AkshareService;

class AshareStrategy implements AshareInterface
{

    //[after, before], if a function doesn't them, can be omited.
    public $call_handlers = [
        "industries" => [
            "industyStore",
            "industyBefore",
        ],
    ];


    public function __construct(protected AkshareService $aShare)
    {
    }

    public function __call(string $name,  array | null $data = null)
    {
        $call_actions = data_get($this->call_handlers, $name);
        throw_if(
            !$call_actions,
            AshareException::class,
            "This function is now allowed!"
        );
        [$after, $before] = $call_actions + ["", ""];
        $data = method_exists($this, $before) ? $this->$before($data) : $data;
        $data = $this->aShare->$name(...$data);
        $data = method_exists($this, $after) ? $this->$after($data) : $data;
        return "all done";
    }
    public function test()
    {
        print "this is a default test in AshareStrategy.";
    }

    public function industyStore($data)
    {
        return Industry::zipCreate($data);
    }

    public function industyBefore($data)
    {
        return Industry::preventDouble($data);
    }
}
