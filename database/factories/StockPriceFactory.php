<?php

namespace Database\Factories;

use App\Enum\Stock;
use App\Models\StockPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockPrice>
 */
class StockPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stock_id' => Stock::AAPL->value,
            'timestamp' => $this->faker->dateTime(),
            'open' => $this->faker->randomFloat(4, 0, 1000),
            'high' => $this->faker->randomFloat(4, 0, 1000),
            'low' => $this->faker->randomFloat(4, 0, 1000),
            'close' => $this->faker->randomFloat(4, 0, 1000),
            'volume' => $this->faker->randomNumber(5),
        ];
    }
}
