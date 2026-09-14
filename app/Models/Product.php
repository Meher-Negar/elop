<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'brand_id',
        'base_price',
        'compare_price',
        'cost_price',
        'status',
        'is_featured',
        'is_active',
        'weight',
        'meta_title',
        'meta_description',
        'icon',
        'plate',
        'colors',
        'sizes',
        'stock',
        'rating',
        'reviews_count',
    ];

    protected $casts = [
        'base_price' => 'float',
        'compare_price' => 'float',
        'cost_price' => 'float',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'colors' => 'array',
        'sizes' => 'array',
        'rating' => 'float',
        'stock' => 'integer',
        'reviews_count' => 'integer',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}
