<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Contracts\PresenterCollectionInterface;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

final class ProductVariantArrayPresenter implements PresenterCollectionInterface
{
    public function __construct(private VariantSizeArrayPresenter $sizeArrayPresenter)
    {}

    public function present(ProductVariant $variant): array
    {
        return [
            'id' => $variant->getId(),
            'title' => $variant->getTitle(),
            'slug' => $variant->getSlug(),
            'price' => $variant->getPrice(),
            'discountPrice' => $variant->getDiscountPrice(),
            'currency' => $variant->getCurrency(),
            'qty' => $variant->getQty(),
            'size' => $this->sizeArrayPresenter->present($variant->size),
            'productId' => $variant->product->getId(),
            'sizeId' => $variant->size->getId(),
        ];
    }

    public function presentCollection(Collection $variants): array
    {
        return $variants->map(
            function (ProductVariant $variant) {
                return $this->present($variant);
            }
        )->all();
    }
}
