<?php

namespace APP\Services;

use App\Exceptions\AshareException;
use App\Interfaces\AshareInterface;
use App\Services\CurlService;
use App\Models\Industry;

class AkshareService implements AshareInterface
{

    // public $name_func = [
    //     'industries' => [
    //         'before' =>'test',
    //         'fetch' => 'stock_board_industry_name_em',
    //         'after' => 'industryStore',
    //     ]
    // ];
    // $call_actions = array_merge(['before' => '', 'after' => ''], $call_actions);
    //     ['before' => $before, 'fetch' => $fetch, 'after' => $after] = $call_actions;
    //     // $data = $before && isset($this->$before) ? $this->$before($data) : $data;
    //     $data = method_exists($this, $before) ? $this->$before($data) : $data;
        

    /* [call_func, after, before] */
    public $name_func = [
        'industries' => ['stock_board_industry_name_em', 'industryStore'],
    ];

    public function __construct(protected CurlService $ch)
    {
    }


    public function __call(String $name, array $data = [])
    {
        $call_actions = data_get($this->name_func, $name);
        throw_if(
            !$call_actions,
            AshareException::class,
            'This function is now allowed!',
        );
        [$fetch, $after, $before] = $call_actions + ['', '', ''];
        $data = method_exists($this, $before) ? $this->$before($data) : $data;
        $data = $this->postData($fetch, $data);
        $data = method_exists($this, $after) ? $this->$after($data) : $data;
        dump('all donw');
        return 'all done';
    }


    public function postData($ask_func, $ask_args = [])
    {
        $post = ['call_func' => $ask_func, 'data' => $ask_args,];
        ['status' => $status, 'data' => $data] = $this->ch->postData($post);
        throw_if(
            $status !== 200,
            AshareException::class,
            $ask_func . '::' . $status,
        );
        return $data;
    }

    public function test()
    {
        print('this is a default test.');
        return null;
        // $post = ['symbol' => '当年', 'date' => '202204'];
        // $result = $this->stock_szse_sector_summary($post);
    }

    public function industryStore($ask_func, $datas)
    {
        list('columns' => $columns, 'data' => $data) = $datas;
        Industry::zipCreate($data);
        return $this;
    }
}
