<?php

namespace App\View\Components;

use Closure;
use App\Enum\Stock;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use App\Models\StockPrice as StockPriceModel;

class StockPrice extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Stock $stock,
        public ?StockPriceModel $stockPrice = null,
        public ?float $percentChange = null,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.stock-price');
    }
}
