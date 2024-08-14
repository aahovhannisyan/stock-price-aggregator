<?php

namespace App\Http\Controllers;

use App\Services\StockPriceService;
use App\Http\Resources\StockPriceResource;
use App\Http\Requests\GetLatestStockPriceRequest;

class StockPriceController
{
    public function __construct(private StockPriceService $service)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/latest-stock-price",
     *     tags={"Stock Price"},
     *     operationId="getStockPrice",
     *     summary="Get latest stock price data for the specified symbol",
     *     description="Get latest stock price data for the specified symbol",
     *     @OA\Parameter(
     *         description="Stock symbol",
     *         in="query",
     *         name="symbol",
     *         required=true,
     *         @OA\Schema(ref="#/components/schemas/StockSymbol"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Latest stock price data returned",
     *         @OA\JsonContent(ref="#/components/schemas/StockPriceResource"),
     *     )
     * )
     *
     * @param GetLatestStockPriceRequest $request
     * @return StockPriceResource|null
     */
    public function index(GetLatestStockPriceRequest $request): ?StockPriceResource
    {
        $stockPrice = $this->service->getLatestPrice($request->input('symbol'));

        return $stockPrice ? StockPriceResource::make($stockPrice) : null;
    }
}
