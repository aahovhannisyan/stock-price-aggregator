<?php

use App\Http\Middleware\ForceJson;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockPriceController;

Route::middleware(ForceJson::class)->group(function () {
    Route::get('/latest-stock-price', [StockPriceController::class, 'index']);
});
