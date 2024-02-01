<?php

namespace App\Services;


class CurlService
{

    public string $url;
    public function __construct($url = null)
    {
        $this->url = $url;
    }

    public function setUrl($url): CurlService
    {
        $this->url = $url;
        return $this;
    }

    public function fetchData()
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return $this->returnResult($ch);
    }

    public function postData($post): array | null
    {
        $ch = curl_init($this->url);
        $post_json = json_encode($post);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        return $this->returnResult($ch, true);
    }

    public function returnResult($ch, $need_to_json = false): array | null
    {
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_status == 200) {
            $result = $need_to_json ? json_decode($result, true) : null;
        }
        return ['status' => $http_status, 'data' => $result];
    }
}
