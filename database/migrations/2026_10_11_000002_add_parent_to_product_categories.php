<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_categories', function (Blueprint $t) {
            $t->foreignId('parent_id')->nullable()->constrained('product_categories')->restrictOnDelete();
            if (! Schema::hasColumn('product_categories', 'image')) $t->string('image')->nullable();
            if (! Schema::hasColumn('product_categories', 'sort_order')) $t->unsignedInteger('sort_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('product_categories', fn (Blueprint $t) => $t->dropConstrainedForeignId('parent_id'));
    }
};
