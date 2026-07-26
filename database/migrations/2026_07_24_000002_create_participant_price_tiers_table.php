<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participant_price_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_component_id')->constrained()->cascadeOnDelete();
            $table->integer('minimum_participants');
            $table->integer('maximum_participants')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('additional_price_per_participant', 12, 2)->nullable();
            $table->timestamps();

            $table->index('pricing_component_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participant_price_tiers');
    }
};