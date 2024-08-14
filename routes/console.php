<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\FetchStockPrices;

Schedule::command(FetchStockPrices::class)->everyMinute();
