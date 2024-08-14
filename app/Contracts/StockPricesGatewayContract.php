<?php

namespace App\Contracts;

interface StockPricesGatewayContract
{
    public function getStockPrices($symbol);
}
