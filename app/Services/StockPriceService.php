<?php

namespace App\Services;

use App\Enum\Stock;
use App\Models\StockPrice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;

class StockPriceService
{
    /**
     * @param Stock $stock
     * @param array $prices
     * @return void
     */
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

    /**
     * @param string $symbol
     * @return StockPrice|null
     */
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

    /**
     * @param string $symbol
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getReport(string $symbol, Carbon $startDate, Carbon $endDate): array
    {
        $stockPrices = $this->getStockPricesByDate($symbol, $startDate, $endDate);
        $percentChange = null;

        if ($stockPrices->count() >= 2) {
            $percentChange = $this->calculatePercentChange(
                $stockPrices->first(),
                $stockPrices->skip(1)->first()
            );
        }

        return [
            'stockPrices' => $stockPrices,
            'percentChange' => $percentChange,
        ];
    }

    /**
     * @param string $symbol
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return Collection
     */
    private function getStockPricesByDate(string $symbol, Carbon $startDate, Carbon $endDate): Collection
    {
        $stock = Stock::symbols()[$symbol] ?? null;

        return StockPrice::query()
            ->where('stock_id', $stock->value)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->orderBy('timestamp', 'desc')
            ->get();
    }

    /**
     * @param StockPrice $first
     * @param StockPrice $second
     * @return float|null
     */
    private function calculatePercentChange(StockPrice $first, StockPrice $second): ?float
    {
        if ((int)$second->high === 0) {
            return null;
        }

        return round(($first->high - $second->high) / $second->high * 100, 2);
    }

    /**
     * @param string $symbol
     * @return float|null
     */
    public function getPercentChange(string $symbol): ?float
    {
        $stock = Stock::symbols()[$symbol] ?? null;
        $percentChange = null;
        $stockPrices = StockPrice::query()
            ->where('stock_id', $stock->value)
            ->orderBy('timestamp', 'desc')
            ->get();

        if ($stockPrices->count() >= 2) {
            $percentChange = $this->calculatePercentChange(
                $stockPrices->first(),
                $stockPrices->skip(1)->first()
            );
        }

        return $percentChange;
    }
}
