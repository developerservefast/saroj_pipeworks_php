<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_category_id', 'brand_id', 'size_id', 'name', 'description', 'details', 'brand', 'size',
        'price', 'discount_percent', 'unit', 'stock_quantity', 'warranty', 'image', 'is_active', 'is_featured', 'is_bestseller', 'is_trending', 'is_latest', 'sort_order', 'meta_description',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'is_featured' => 'boolean', 'is_bestseller' => 'boolean', 'is_trending' => 'boolean', 'is_latest' => 'boolean'];
    }

    public function category() { return $this->belongsTo(ProductCategory::class, 'product_category_id'); }
    public function sizeRecord() { return $this->belongsTo(Size::class, 'size_id'); }
    public function sizes() { return $this->belongsToMany(Size::class, 'product_size'); }
    public function brandRecord() { return $this->belongsTo(Brand::class, 'brand_id'); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id'); }
    public function variants() { return $this->hasMany(ProductVariant::class)->orderBy('sort_order')->orderBy('id'); }
}
