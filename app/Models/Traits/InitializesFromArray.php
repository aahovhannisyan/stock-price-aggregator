<?php

namespace App\Models\Traits;

trait InitializesFromArray
{
    public static function fromArray(array $attributes): static
    {
        $instance = new static();
        $instance->fill($attributes);

        return $instance;
    }
}
