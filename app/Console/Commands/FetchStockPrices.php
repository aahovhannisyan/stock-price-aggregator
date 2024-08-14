<?php

namespace App\Console\Commands;

use App\Enum\Stock;
use Illuminate\Console\Command;
use App\Jobs\FetchStockPrices as FetchStockPricesJob;

class FetchStockPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prices:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store stock prices';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        foreach (Stock::cases() as $stock) {
            FetchStockPricesJob::dispatch($stock);
        }
    }
}
