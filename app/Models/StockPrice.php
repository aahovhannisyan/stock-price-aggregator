<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\InitializesFromArray;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockPrice extends Model
{
    use HasFactory, InitializesFromArray;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stock_id',
        'timestamp',
        'open',
        'high',
        'low',
        'close',
        'volume',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'timestamp' => 'datetime',
        ];
    }
}
