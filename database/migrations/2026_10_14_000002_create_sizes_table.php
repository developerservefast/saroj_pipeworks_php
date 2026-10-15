<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sizes', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::table('products', function (Blueprint $t) {
            $t->foreignId('size_id')->nullable()->constrained('sizes')->nullOnDelete();
        });

        foreach (DB::table('products')->whereNotNull('size')->where('size', '!=', '')->distinct()->pluck('size') as $name) {
            $id = DB::table('sizes')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
            DB::table('products')->where('size', $name)->update(['size_id' => $id]);
        }
    }

    public function down(): void
    {
        Schema::table('products', fn (Blueprint $t) => $t->dropConstrainedForeignId('size_id'));
        Schema::dropIfExists('sizes');
    }
};