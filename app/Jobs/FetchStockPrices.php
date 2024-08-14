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
use Illuminate\Queue\Middleware\RateLimitedWithRedis;

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
     * Consider retrying jobs after failure with a brief delay in case the API is unstable.
     */
//    public int $tries = 3;
//    public int $backoff = 5;

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

    /**
     * @return array
     */
    public function middleware(): array
    {
        /**
         * If the rate limit exceeds we don't release back to the queue.
         * Alternatively, we could create a custom middleware to fail the job with an exception,
         * but that would bloat the database given the rate limit of 25 requests per day
         * and having jobs scheduled every minute.
         */
        return [(new RateLimitedWithRedis('fetch_stock_prices'))->dontRelease()];
    }
}
