<?php

namespace App\Services;


class CustomizedErrorService
{
    public $messages = [];

    public function __construct(String $message = '')
    {
        $this->messages[] = $message;
        return $this;
    }

    public function info(String $message)
    {
        $this->messages[] = $message;
        return $this;
    }

    public function print()
    {
        print('-------------------');
        print(implode(PHP_EOL, $this->messages));
        print(PHP_EOL . '-------------------' . PHP_EOL);
    }
    public function begin($fn)
    {
        $this->info('begin.');
        try {
            return $fn();
        } finally {
            $this->print();
        }
    }
}
