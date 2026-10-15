<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('path');
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('hex', 7)->nullable();
            $t->decimal('price', 10, 2)->nullable();
            $t->string('image')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::table('products', function (Blueprint $t) {
            $t->text('details')->nullable()->after('description');
            $t->unsignedTinyInteger('discount_percent')->default(0)->after('price');
            $t->boolean('is_featured')->default(false);
        });

        // Keep existing data: current main images and saved colors.
        foreach (DB::table('products')->whereNotNull('image')->get(['id', 'image']) as $p) {
            DB::table('product_images')->insert(['product_id' => $p->id, 'path' => $p->image, 'created_at' => now(), 'updated_at' => now()]);
        }
        if (Schema::hasTable('product_colors')) {
            foreach (DB::table('product_colors')->get() as $c) {
                DB::table('product_variants')->insert(['product_id' => $c->product_id, 'name' => $c->name, 'hex' => $c->hex, 'created_at' => now(), 'updated_at' => now()]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('products', fn (Blueprint $t) => $t->dropColumn(['details', 'discount_percent', 'is_featured']));
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
    }
};
