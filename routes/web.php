<?php

use App\Http\Controllers\StockPriceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stocks', [StockPriceController::class, 'show']);
