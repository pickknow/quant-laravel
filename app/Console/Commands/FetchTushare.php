<?php

namespace App\Console\Commands;

use App\Events\AshareEvent;
use App\Interfaces\AshareInterface;
use Illuminate\Console\Command;

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

    /**
     * Execute the console command.
     */
    public function handle(AshareInterface $aShare)
    {
        $action = $this->argument('action');
        return $aShare->$action();
    }
}
