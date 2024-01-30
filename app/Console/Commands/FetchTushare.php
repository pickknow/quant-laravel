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
    protected $signature = 'app:fetch-ashare';

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
        $post = ['symbol' => '当年', 'date' => '202204'];
        $result = $aShare->stock_szse_sector_summary($post);
        dd($result);
        // $this->info($result);
    }
}
