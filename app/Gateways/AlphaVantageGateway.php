<?php

namespace App\Gateways;

use Exception;
use Illuminate\Support\Facades\Http;
use App\Contracts\StockPricesGatewayContract;
use Symfony\Component\HttpFoundation\Response;

class AlphaVantageGateway implements StockPricesGatewayContract
{
    private string $apiBase;
    private string $apiKey;

    public function __construct()
    {
        $this->setConfigs();
    }

    private function setConfigs(): void
    {
        $this->apiBase = config('services.alpha_vantage.api_base');
        $this->apiKey = config('services.alpha_vantage.api_key');
    }

    /**
     * @throws Exception
     */
    public function getStockPrices($symbol)
    {
        $response = Http::get($this->apiBase, [
            'apikey' => $this->apiKey,
            'interval' => '1min',
            'function' => 'TIME_SERIES_INTRADAY',
            'symbol' => $symbol,
        ]);

        if ($response->status() !== Response::HTTP_OK) {
            throw new Exception($response->json('message'), $response->status());
        }

        $prices = $response->json('Time Series (1min)');

        if (is_null($prices)) {
            $message = $response->json('message') ?: __('messages.unexpected_format');

            throw new Exception($message, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $prices;
    }
}
