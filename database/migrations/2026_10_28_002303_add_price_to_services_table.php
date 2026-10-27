<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('services', function (Blueprint $t) {
        $t->decimal('price', 10, 2)->nullable()->after('image');
        $t->string('price_type', 20)->default('on_inspection')->after('price');
    });
}

public function down(): void
{
    Schema::table('services', fn (Blueprint $t) => $t->dropColumn(['price', 'price_type']));
}
};