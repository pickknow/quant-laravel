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
        "stocksOfIndustry" =>["stock_board_industry_cons_em",], //(symbol='小金属')
        "diaryHistory" =>["stock_zh_a_hist",], //(symbol="000001", period="daily", start_date="20170301", end_date='20210907', adjust="qfq")
        "stockInfo" =>["stock_individual_info_em",], //(symbol="000001")
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
        [$call_name, ] = $call_actions;
        $post = ["call_func" => $call_name , "data" => $data];
        ["status" => $status, "data" => $data] = $this->ch->postData($post);
        throw_if(
            $status !== 200,
            AshareException::class,
            $ask_func . "::" . $status
        );
        return $data['data']; //return data, because if didn't get data, then throw an Error.
    }
}
