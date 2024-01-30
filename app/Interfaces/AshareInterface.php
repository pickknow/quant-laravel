<?php
namespace App\Interfaces;

interface AshareInterface
{
    public function regesiter();
    public function __call(String $name, Array $data);
}
