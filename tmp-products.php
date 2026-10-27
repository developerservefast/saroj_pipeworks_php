<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

$data = [
 '3/4 inch CPVC' => ['CPVC Elbow 90 Degree', 'CPVC Tee', 'CPVC Coupler', 'CPVC Ball Valve', 'CPVC End Cap'],
 'Bathroom & Sanitary' => ['Wall Mixer', 'Pillar Cock', 'Health Faucet', 'Overhead Shower', 'Angle Valve'],
 '110mm Drainage' => ['Drainage Bend', 'Drainage Tee', 'Drainage Coupling'],
];
$catSlug = Schema::hasColumn('product_categories', 'slug');
$prodSlug = Schema::hasColumn('products', 'slug');
$ci = 0;
foreach ($data as $catName => $names) {
    $ci++;
    $vals = ['is_active' => true, 'sort_order' => $ci];
    if ($catSlug) $vals['slug'] = Str::slug($catName);
    $cat = App\Models\ProductCategory::unguarded(fn () => App\Models\ProductCategory::firstOrCreate(['name' => $catName, 'parent_id' => null], $vals));
    foreach ($names as $k => $n) {
        $pv = [
            'description' => '<p>Sample product. Replace with the real description.</p>',
            'unit' => 'pcs',
            'is_active' => true,
            'is_bestseller' => $k === 0,
            'is_trending' => $k === 1,
            'is_latest' => $k === 2,
            'sort_order' => 900 + $k,
        ];
        if ($prodSlug) $pv['slug'] = Str::slug($catName . ' ' . $n);
        App\Models\Product::unguarded(fn () => App\Models\Product::firstOrCreate(['name' => $n, 'product_category_id' => $cat->id], $pv));
    }
}
echo App\Models\ProductCategory::count() . " categories, " . App\Models\Product::count() . " products\n";
