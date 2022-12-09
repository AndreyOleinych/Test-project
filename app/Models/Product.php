<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'picture',
        'price',
        'discount_price',
        'currency',
        'qty',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            $product->slug = Str::slug($product->title). $product->id;
            $product->save();
        });

        static::updated(function ($product) {
            if($product->slug != Str::slug($product->getTitle()). $product->getId()){
                $product->slug = Str::slug($product->title). $product->id;
                $product->save();
            }
        });
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getDiscountPrice(): int
    {
        return $this->discount_price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getQty(): int
    {
        return $this->qty;
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}
