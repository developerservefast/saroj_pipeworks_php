<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductCategory extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id', 'image', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id')->orderBy('sort_order');
    }

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public static function makeSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $n = 2;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }
        return $slug;
    }
}
