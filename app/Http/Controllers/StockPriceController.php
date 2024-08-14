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

    public function index(GetLatestStockPriceRequest $request): ?StockPriceResource
    {
        $stockPrice = $this->service->getLatestPrice($request->input('symbol'));

        return $stockPrice ? StockPriceResource::make($stockPrice) : null;
    }
}
