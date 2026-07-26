<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_estimation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_estimation_id')->constrained()->cascadeOnDelete();
            $table->string('item_code');
            $table->string('item_name');
            $table->integer('quantity')->default(0);
            $table->integer('frequency')->default(1);
            $table->string('unit', 50)->nullable();
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('price_per_person', 12, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->json('calculation_details')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('price_estimation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_estimation_items');
    }
};