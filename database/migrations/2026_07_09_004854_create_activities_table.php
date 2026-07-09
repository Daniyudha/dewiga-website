<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title_id');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->text('description_id');
            $table->text('description_en')->nullable();
            $table->string('image');
            $table->string('duration_id')->nullable();
            $table->string('duration_en')->nullable();
            $table->string('min_pax')->nullable();
            $table->string('includes_id')->nullable();
            $table->string('includes_en')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};