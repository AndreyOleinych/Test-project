<?php

namespace App\Http\Controllers\Api\ProductVariant;

use App\Http\Controllers\Api\ApiController;
use App\Http\Presenters\ProductVariantArrayPresenter;
use App\Http\Requests\Api\ProductVariant\AddProductVariantValidationRequest;
use App\Http\Requests\Api\ProductVariant\UpdateProductVariantValidationRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ProductVariantController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param  Product  $product
     * @param  ProductVariantArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function index(Product $product, ProductVariantArrayPresenter $presenter): JsonResponse
    {
        $variants = $product->variants()->get();

        return $this->successResponse($presenter->presentCollection($variants));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddProductVariantValidationRequest  $request
     * @param  Product  $product
     * @param  ProductVariantArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function store(AddProductVariantValidationRequest $request, Product $product, ProductVariantArrayPresenter $presenter): JsonResponse
    {
        $variantData = [
            'title' => $request->get('title'),
            'slug' => Str::slug($request->get('title')),
            'price' => $request->get('price'),
            'product_id' => $product->getId(),
            'size_id' => $request->get('size'),
        ];

        if($request->get('discountPrice'))
        {
            $variantData['discount_price'] = $request->get('discountPrice');
        }

        if($request->get('currency'))
        {
            $variantData['currency'] = $request->get('currency');
        }

        if($request->get('qty'))
        {
            $variantData['qty'] = $request->get('qty');
        }

        $variant = ProductVariant::query()->create($variantData);

        return $this->successResponse($presenter->present($variant->refresh()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProductVariantValidationRequest  $request
     * @param  ProductVariant  $variant
     * @param  ProductVariantArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function update(UpdateProductVariantValidationRequest $request, ProductVariant $variant, ProductVariantArrayPresenter $presenter): JsonResponse
    {
        $variant->update([
            'title' => $request->get('title'),
            'price' => $request->get('price'),
            'discount_price' => $request->get('discountPrice') ?? $variant->getDiscountPrice(),
            'currency' => $request->get('currency') ?? $variant->getCurrency(),
            'qty' => $request->get('qty') ?? $variant->getQty(),
            'size_id' => $request->get('size') ?? $variant->size->getId(),
        ]);

        return $this->successResponse($presenter->present($variant->refresh()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ProductVariant  $variant
     *
     * @return JsonResponse
     */
    public function destroy(ProductVariant $variant): JsonResponse
    {
        $variant->delete();

        return $this->emptyResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  ProductVariant  $variant
     * @param  ProductVariantArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function show(ProductVariant $variant, ProductVariantArrayPresenter $presenter): JsonResponse
    {
        return $this->successResponse($presenter->present($variant));
    }
}
