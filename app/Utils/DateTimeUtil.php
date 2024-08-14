<?php

namespace App\Utils;

use Illuminate\Support\Carbon;

class DateTimeUtil
{
    public static function format(?string $timestamp): ?string
    {
        if (!$timestamp) {
            return null;
        }

        return Carbon::parse($timestamp)->format('Y-m-d H:i:s');
    }
}
