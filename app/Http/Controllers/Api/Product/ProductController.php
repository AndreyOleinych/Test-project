<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Api\ApiController;
use App\Http\Presenters\ProductArrayPresenter;
use App\Http\Requests\Api\Product\AddProductValidationRequest;
use App\Http\Requests\Api\Product\UpdateProductValidationRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param  ProductArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function index(ProductArrayPresenter  $presenter): JsonResponse
    {
        $products = Product::all();

        return $this->successResponse($presenter->presentCollection($products));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddProductValidationRequest  $request
     * @param  ProductArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function store(AddProductValidationRequest $request, ProductArrayPresenter $presenter): JsonResponse
    {
        $productData = [
            'title' => $request->get('title'),
            'slug' => Str::slug($request->get('title')),
            'price' => $request->get('price'),
        ];

        if($request->get('discountPrice'))
        {
            $productData['discount_price'] = $request->get('discountPrice');
        }

        if($request->get('currency'))
        {
            $productData['currency'] = $request->get('currency');
        }

        if($request->get('qty'))
        {
            $productData['qty'] = $request->get('qty');
        }

        $product = Product::query()->create($productData);

        return $this->successResponse($presenter->present($product->refresh()));
    }

    /**
     * Display the specified resource.
     *
     * @param  Product  $product
     * @param  ProductArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function show(Product $product, ProductArrayPresenter $presenter): JsonResponse
    {
        return $this->successResponse($presenter->present($product));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProductValidationRequest  $request
     * @param  Product  $product
     * @param  ProductArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function update(UpdateProductValidationRequest $request, Product $product, ProductArrayPresenter  $presenter): JsonResponse
    {
        $product->update([
            'title' => $request->get('title'),
            'price' => $request->get('price'),
            'discount' => $request->get('discountPrice') ?? $product->getDiscountPrice(),
            'currency' => $request->get('currency') ?? $product->getCurrency(),
            'qty' => $request->get('qty') ?? $product->getQty(),
        ]);
        $product->save();

        return $this->successResponse($presenter->present($product));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     *
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return $this->emptyResponse();
    }
}
