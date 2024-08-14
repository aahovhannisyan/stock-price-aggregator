<?php

namespace App\Providers;

use App\Gateways\AlphaVantageGateway;
use Illuminate\Support\ServiceProvider;
use App\Contracts\StockPricesGatewayContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(StockPricesGatewayContract::class, AlphaVantageGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
