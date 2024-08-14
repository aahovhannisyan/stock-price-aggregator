<?php

namespace App\Http\Resources;

use App\Utils\DateTimeUtil;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="StockPriceResource",
 *     type="object",
 *     nullable="true",
 *     @OA\Property(
 *         property="timestamp",
 *         description="The stock price timestamp",
 *         type="string",
 *         example="2024-08-13 19:59:00",
 *     ),
 *     @OA\Property(
 *         property="open",
 *         description="The opening price of the stock",
 *         type="string",
 *         example="220.6600",
 *     ),
 *     @OA\Property(
 *         property="high",
 *         description="The highest price of the stock",
 *         type="string",
 *         example="220.7000",
 *     ),
 *     @OA\Property(
 *         property="low",
 *         description="The lowest price of the stock",
 *         type="string",
 *         example="220.6400",
 *     ),
 *     @OA\Property(
 *         property="close",
 *         description="The closing price of the stock",
 *         type="string",
 *         example="0.0000",
 *     ),
 *     @OA\Property(
 *         property="volume",
 *         description="The number of shares traded",
 *         type="integer",
 *         example=1842,
 *     ),
 * )
 */
class StockPriceResource extends JsonResource
{
    /**
     * Disable the "data" wrapper.
     *
     * @var null
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'timestamp' => DateTimeUtil::format($this->timestamp),
            'open' => $this->open,
            'high' => $this->high,
            'low' => $this->low,
            'close' => $this->close,
            'volume' => $this->volume,
        ];
    }
}
