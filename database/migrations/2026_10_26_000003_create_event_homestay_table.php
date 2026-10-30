<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_homestay', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('homestay_id')->constrained()->cascadeOnDelete();
            $table->string('group_name_id')->nullable();
            $table->string('group_name_en')->nullable();
            $table->text('notes')->nullable();
            $table->unique(['event_id', 'homestay_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_homestay');
    }
};
