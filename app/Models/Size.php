<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name', 'is_active'];

    public function products() { return $this->belongsToMany(Product::class, 'product_size'); }

    /** Sort value so 1/2, 3/4, 1, 1 1/2 come in real order. */
    public static function sortKey(string $name): float
    {
        $n = str_replace('½', ' 1/2', $name);
        if (preg_match('/(\d+)\s+(\d+)\/(\d+)/', $n, $m) && (int) $m[3] > 0) {
            return $m[1] + $m[2] / $m[3];
        }
        if (preg_match('/(\d+)\/(\d+)/', $n, $m) && (int) $m[2] > 0) {
            return $m[1] / $m[2];
        }
        if (preg_match('/\d+(\.\d+)?/', $n, $m)) {
            return (float) $m[0];
        }

        return PHP_FLOAT_MAX;
    }

    /** Comma-separated names in real size order, or null when empty. */
    public static function joinNames(array $names): ?string
    {
        usort($names, fn ($a, $b) => [static::sortKey($a), $a] <=> [static::sortKey($b), $b]);

        return $names ? implode(', ', $names) : null;
    }
}