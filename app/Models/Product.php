<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_category_id', 'name', 'description', 'brand', 'size',
        'price', 'unit', 'warranty', 'image', 'is_active', 'sort_order',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
