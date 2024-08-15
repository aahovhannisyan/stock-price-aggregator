<?php

namespace App\Http\Requests;

use App\Enum\Stock;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetStockPriceReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $symbols = implode(',', array_keys(Stock::symbols()));

        return [
            'symbol' => ['string', 'required', 'max:10', 'in:' . $symbols],
            'start' => ['required', 'date_format:Y-m-d H:i:s', 'before:now'],
            'end' => ['required', 'date_format:Y-m-d H:i:s', 'after:start'],
        ];
    }
}
