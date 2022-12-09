<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'price',
        'discount_price',
        'currency',
        'qty',
        'product_id',
        'size_id',
    ];

    protected $touches = ['product'];
    protected $with = ['size'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($variant) {
            $variant->slug = Str::slug($variant->title). $variant->id;
            $variant->save();
        });

        static::updated(function ($variant) {
            if($variant->slug != Str::slug($variant->getTitle()).$variant->getId()){
                $variant->slug = Str::slug($variant->title). $variant->id;
                $variant->save();
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

    public function size(): BelongsTo
    {
        return $this->belongsTo(VariantSize::class,'size_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
