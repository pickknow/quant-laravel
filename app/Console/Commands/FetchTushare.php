<?php

namespace App\Console\Commands;

// use App\Events\AshareEvent;
use App\Interfaces\AshareInterface;
// use App\Services\CustomizedErrorService;
use Illuminate\Console\Command;
use App\Http\Middleware\CustomizedErrorMessage;
// use Exception;

class FetchTushare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ashare:fetch {action=test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from a flask app';


    public function middleware()
    {
        return [new CustomizedErrorMessage];
    }

    /**
     * Execute the console command.
     */
    public function handle(AshareInterface $aShare)
    {
        app()->make("CES")->begin(function () use ($aShare) {
            $action = $this->argument('action');
            $aShare->$action();
        });
    }
}
