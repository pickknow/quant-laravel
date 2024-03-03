<?php

namespace APP\Services;

use App\Exceptions\AshareException;
// use App\Interfaces\AshareInterface;
use App\Services\CurlService;
// use App\Facades\Functional;

class AkshareService
{
    //[action, after, before], if a function doesn't them, can be omited.
    public $name_func = [
        "industries" => ["stock_board_industry_name_em",],
        "stocksOfIndustry" =>["stock_board_industry_cons_em"] //(symbol='小金属')
    ];



    public function __construct(protected CurlService $ch)
    {
    }

    public function __call(string $ask_func, array $data = [])
    {
        $call_actions = data_get($this->name_func, $ask_func);
        throw_if(
            !$call_actions,
            AshareException::class,
            "Call is not found!"
        );
        [$call_actions, ] = $call_actions;
        $post = ["call_func" => $call_actions , "data" => $data];
        ["status" => $status, "data" => $data] = $this->ch->postData($post);
        throw_if(
            $status !== 200,
            AshareException::class,
            $ask_func . "::" . $status
        );
        return $data['data'];
    }
}
