<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_size', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('size_id')->constrained()->cascadeOnDelete();
            $t->unique(['product_id', 'size_id']);
        });

        foreach (DB::table('products')->whereNotNull('size_id')->get(['id', 'size_id']) as $p) {
            DB::table('product_size')->insert(['product_id' => $p->id, 'size_id' => $p->size_id]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_size');
    }
};