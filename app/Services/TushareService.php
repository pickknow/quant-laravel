<?php
namespace App\Services;

class TushareService 
{

    public function fetchData()
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, "http://127.0.0.1:5000/");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($c);
        return $result;
    }
}
