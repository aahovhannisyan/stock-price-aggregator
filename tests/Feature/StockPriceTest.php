<?php

namespace Tests\Feature;

use App\Enum\Stock;
use App\Models\StockPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockPriceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test /latest-stock-price endpoint.
     *
     * @return void
     */
    public function test_latest_stock_price_endpoint(): void
    {
        StockPrice::factory()->create([
            'stock_id' => Stock::AAPL->value,
            'timestamp' => '2024-08-13 19:59:00',
            'open' => '220.6600',
            'high' => '220.7000',
            'low' => '220.6400',
            'close' => '0.0000',
            'volume' => 1842,
        ]);

        $response = $this->call('GET', 'api/latest-stock-price', ['symbol' => Stock::AAPL->symbol()]);
        $response->assertStatus(200)
            ->assertJson([
                'timestamp' => '2024-08-13 19:59:00',
                'open' => '220.6600',
                'high' => '220.7000',
                'low' => '220.6400',
                'close' => '0.0000',
                'volume' => 1842,
            ]);
    }

    /**
     * Test /report endpoint.
     *
     * @return void
     */
    public function test_report_endpoint(): void
    {
        StockPrice::factory()->create([
            'stock_id' => Stock::AAPL->value,
            'timestamp' => '2024-08-13 19:58:00',
            'open' => '220.6250',
            'high' => '220.6500',
            'low' => '220.6060',
            'close' => '0.0000',
            'volume' => 1442,
        ]);
        StockPrice::factory()->create([
            'stock_id' => Stock::AAPL->value,
            'timestamp' => '2024-08-13 19:59:00',
            'open' => '220.6600',
            'high' => '220.7000',
            'low' => '220.6400',
            'close' => '0.0000',
            'volume' => 1842,
        ]);

        $response = $this->call('GET', '/api/report', [
            'symbol' => Stock::AAPL->symbol(),
            'start' => '2024-08-13 19:58:00',
            'end' => '2024-08-13 19:59:00',
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'percentChange' => 0.02,
            ]);
    }
}
