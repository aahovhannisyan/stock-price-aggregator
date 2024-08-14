<?php

namespace App\Services;

use App\Enum\Stock;
use App\Models\StockPrice;
use Illuminate\Support\Facades\Cache;

class StockPriceService
{
    public function updateStockPrices(Stock $stock, array $prices): void
    {
        $data = [];

        foreach ($prices as $timestamp => $price) {
            $data[] = [
                'stock_id' => $stock->value,
                'timestamp' => $timestamp,
                'open' => $price['1. open'] ?? 0,
                'high' => $price['2. high'] ?? 0,
                'low' => $price['3. low'] ?? 0,
                'close' => $price['4. low'] ?? 0,
                'volume' => $price['5. volume'] ?? 0,
            ];
        }

        $chunks = array_chunk($data, 100);

        foreach ($chunks as $chunk) {
            StockPrice::query()->upsert(
                $chunk,
                ['stock_id', 'timestamp'],
                ['open', 'high', 'low', 'close', 'volume']
            );
        }

        $minuteInSeconds = 60;
        Cache::put('stock_price_' . $stock->symbol(), StockPrice::fromArray($data[0]), $minuteInSeconds);
    }

    public function getLatestPrice(string $symbol): ?StockPrice
    {
        $stock = Stock::symbols()[$symbol] ?? null;
        $minuteInSeconds = 60;

        return Cache::remember('stock_price_' . $stock->symbol(), $minuteInSeconds, function () use ($stock) {
            return StockPrice::query()
                ->where('stock_id', $stock->value)
                ->orderBy('timestamp', 'desc')
                ->first();
        });
    }
}
