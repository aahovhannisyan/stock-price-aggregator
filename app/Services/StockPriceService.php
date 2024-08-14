<?php

namespace App\Services;

use App\Enum\Stock;
use App\Models\StockPrice;

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
    }
}
