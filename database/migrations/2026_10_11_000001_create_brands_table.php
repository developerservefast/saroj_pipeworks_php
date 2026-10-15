<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::table('products', function (Blueprint $t) {
           $t->foreignId('brand_id')->nullable()->after('product_category_id')->constrained('brands')->nullOnDelete();
        });

        // Copy existing brand text into the new table (old column is kept).
        foreach (DB::table('products')->whereNotNull('brand')->where('brand', '!=', '')->distinct()->pluck('brand') as $name) {
            $id = DB::table('brands')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
            DB::table('products')->where('brand', $name)->update(['brand_id' => $id]);
        }
    }

    public function down(): void
    {
        Schema::table('products', fn (Blueprint $t) => $t->dropConstrainedForeignId('brand_id'));
        Schema::dropIfExists('brands');
    }
};
