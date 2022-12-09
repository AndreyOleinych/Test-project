<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Product;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rules\File;

final class UploadProductPictureValidationRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                File::types(['jpg','jpeg','png'])->max(10240),
            ],
        ];
    }
}
