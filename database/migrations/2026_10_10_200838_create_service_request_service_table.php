<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_request_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['service_request_id', 'service_id']);
        });

        // Keep the service already chosen on existing requests
        DB::table('service_requests')->whereNotNull('service_id')->orderBy('id')->each(function ($r) {
            DB::table('service_request_service')->insert([
                'service_request_id' => $r->id,
                'service_id' => $r->service_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_id');
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::dropIfExists('service_request_service');
    }
};
