<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Contracts\PresenterCollectionInterface;
use App\Models\Product;
use Illuminate\Support\Collection;

final class ProductArrayPresenter implements PresenterCollectionInterface
{
    public function __construct(
        private ProductVariantArrayPresenter $variantArrayPresenter
    ){}

    public function present(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'slug' => $product->getSlug(),
            'picture' => $product->getPicture(),
            'price' => $product->getPrice(),
            'discountPrice' => $product->getDiscountPrice(),
            'currency' => $product->getCurrency(),
            'qty' => $product->getQty(),
            'variants' => $this->variantArrayPresenter->presentCollection($product->variants()->get()),
        ];
    }

    public function presentCollection(Collection $products): array
    {
        return $products->map(
            function (Product $product) {
                return $this->present($product);
            }
        )->all();
    }
}
