<?php

namespace App\Jobs;

use Exception;
use App\Enum\Stock;
use App\Services\StockPriceService;
use Illuminate\Support\Facades\App;
use App\Gateways\AlphaVantageGateway;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contracts\StockPricesGatewayContract;

class FetchStockPrices implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Stock $stock)
    {
        //
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        /** @var AlphaVantageGateway $gateway */
        $gateway = App::make(StockPricesGatewayContract::class);
        $prices = $gateway->getStockPrices($this->stock->symbol());
        /** @var StockPriceService $stockPriceService */
        $stockPriceService = App::make(StockPriceService::class);
        $stockPriceService->updateStockPrices($this->stock, $prices);
    }
}
