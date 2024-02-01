<?php

namespace APP\Services;

use App\Exceptions\AshareException;
use App\Interfaces\AshareInterface;
use App\Services\CurlService;
use App\Models\Industry;

class AkshareService implements AshareInterface
{

    public $name_func = [
        'industries' => 'stock_board_industry_name_em',
    ];

    public function __construct(protected CurlService $ch) { }


    public function __call(String $name, array $data = null) 
    {
        $name = data_get($this->name_func, $name);
        if ($name) return $this->postData($name, $data);
        dump('this function is now allowed!');
        return null;
    }

    public function postData($ask_func, $ask_args = '') 
    {
        $post = ['call_func' => $ask_func, 'data' => $ask_args, ];
        list('status' => $status, 'data' => $data) = $this->ch->postData($post);
        if ($status == 200) return $this->store($ask_func, $data);

        report(new AshareException($ask_func . '::' . $status));
        return null;
    }

    public function test() {
        print('this is a default test.');
        return null;
        // $post = ['symbol' => '当年', 'date' => '202204'];
        // $result = $this->stock_szse_sector_summary($post);
    }

    public function store($name, $datas) {
        list('columns' => $columns, 'data' => $data) = $datas;
        Industry::zipCreate($data);
        return null;

    }



}
