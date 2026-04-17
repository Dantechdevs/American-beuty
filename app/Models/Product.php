<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id','brand_id','name','slug','short_description','description',
        'price','sale_price','sku','stock_quantity','track_stock','thumbnail',
        'is_active','is_featured','is_new_arrival','is_best_seller',
        'weight','ingredients','skin_type','concern','meta_title','meta_description',
    ];
    protected $casts = [
        'is_active'      => 'boolean',
        'is_featured'    => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_best_seller' => 'boolean',
        'track_stock'    => 'boolean',
        'price'          => 'decimal:2',
        'sale_price'     => 'decimal:2',
    ];

    public function category()  { return $this->belongsTo(Category::class); }
    public function brand()     { return $this->belongsTo(Brand::class); }
    public function images()    { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function reviews()   { return $this->hasMany(ProductReview::class)->where('is_approved', true); }
    public function wishlists() { return $this->hasMany(Wishlist::class); }

    public function getCurrentPrice(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercent(): int
    {
        if ($this->sale_price && $this->sale_price < $this->price) {
            return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getAverageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function isInStock(): bool
    {
        return !$this->track_stock || $this->stock_quantity > 0;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) $model->slug = Str::slug($model->name);
        });
    }
}
