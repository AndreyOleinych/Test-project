<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\ProductVariant;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

final class AddProductVariantValidationRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'unique:product_variants,title',
                'string'
            ],
            'price' => [
                'required',
                'integer',
                'gt:0'
            ],
            'discountPrice' => [
                'integer',
                'gte:0',
            ],
            'currency' => [
                Rule::in(['USD','EUR']),
                'string'
            ],
            'qty' => [
                'integer',
                'gte:0'
            ],
            'size' => [
                'required',
                'integer',
                'exists:variant_sizes,id'
            ],
        ];
    }
}
