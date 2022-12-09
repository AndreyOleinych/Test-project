<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Api\ApiController;
use App\Http\Presenters\ProductArrayPresenter;
use App\Http\Requests\Api\Product\UploadProductPictureValidationRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadProductPictureController extends ApiController
{
    /**
     * Upload product picture.
     *
     * @param  UploadProductPictureValidationRequest  $request
     * @param  Product  $product
     * @param  ProductArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function __invoke(
        UploadProductPictureValidationRequest $request,
        Product $product,
        ProductArrayPresenter $presenter
    ): JsonResponse
    {
        $file = $request->file('image');

        if ($product->getPicture()) {
            Storage::disk('s3')->delete(
                Str::remove(
                    env('AWS_LINK'),
                    $product->getPicture()
                )
            );
        }

        $path = Storage::disk('s3')->putFileAs(
            config('filesystems.product_pictures'),
            $file,
            $file->hashName(),
            's3'
        );

        $product->picture = Storage::disk('s3')->url($path);
        $product->save();

        return $this->successResponse($presenter->present($product));
    }
}
