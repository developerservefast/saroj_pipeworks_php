<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brand_product', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $t->unique(['product_id', 'brand_id']);
        });

        // Keep existing data: copy each product's current brand into the new table.
        foreach (DB::table('products')->whereNotNull('brand_id')->get(['id', 'brand_id']) as $p) {
            DB::table('brand_product')->insert(['product_id' => $p->id, 'brand_id' => $p->brand_id]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_product');
    }
};