<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rundown_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rundown_template_id')->constrained()->cascadeOnDelete();
            $table->integer('day_number')->default(1);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('activity_name');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rundown_template_items');
    }
};