<?php

namespace APP\Services;

use App\Exceptions\AshareException;
use App\Interfaces\AshareInterface;
use App\Services\CurlService;
use App\Models\Industry;
use DateTime;
// use App\Facades\Functional;

class AkshareService implements AshareInterface
{
    /* [call_func, after, before] */
    public $name_func = [
        "industries" => [
            "stock_board_industry_name_em",
            "industyStore",
            "industyBefore",
        ],
    ];

    public function __construct(protected CurlService $ch)
    {
    }

    public function __call(string $name, array $data = [])
    {
        $call_actions = data_get($this->name_func, $name);
        throw_if(
            !$call_actions,
            AshareException::class,
            "This function is now allowed!"
        );
        [$fetch, $after, $before] = $call_actions + ["", "", ""];
        $data = method_exists($this, $before) ? $this->$before($data) : $data;
        $data = $this->postData($fetch, $data);
        $data = method_exists($this, $after) ? $this->$after($data['data']) : $data;
        return "all done";
    }

    public function postData($ask_func, $ask_args = [])
    {
        $post = ["call_func" => $ask_func, "data" => $ask_args];
        ["status" => $status, "data" => $data] = $this->ch->postData($post);
        throw_if(
            $status !== 200,
            AshareException::class,
            $ask_func . "::" . $status
        );
        return $data;
    }

    public function test()
    {
        print "this is a default test.";
    }


    public function industyStore($data)
    {
        return Industry::zipCreate($data);
    }

    public function industyBefore()
    {
        return Industry::preventDoubleDay();
    }
}
