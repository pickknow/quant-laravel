<?php

namespace APP\Services;

use App\Exceptions\AshareException;
use App\Interfaces\AshareInterface;

class AkshareService implements AshareInterface
{
    protected $url = "http://127.0.0.1:5000/ak_fetch";

    public function regesiter() { }

    public function __call(String $name, array $data = null) : array | null
    {
        $post = [
            'call_func' => $name,
            'data' => $data,
        ];
        return $this->postData($post);
    }

    public function fetchData()
    {
        $c = curl_init($this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($c);
        return $result;
    }

    public function postData($post):array | null
    {
        $post_json = json_encode($post);
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_status == 200) return json_decode($result);

        report(new AshareException($post_json . '::' . $http_status));
        return null;
    }

}
