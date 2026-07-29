<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_settings', function (Blueprint $table) {
            $table->id();
            $table->text('short_profile')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('advantages')->nullable();
            $table->text('location')->nullable();
            $table->string('maps_url')->nullable();
            $table->text('contact')->nullable();
            $table->string('tagline')->nullable();
            $table->text('commitment')->nullable();
            $table->text('dp_terms')->nullable();
            $table->text('cancellation_terms')->nullable();
            $table->text('participant_change_terms')->nullable();
            $table->text('force_majeure_terms')->nullable();
            $table->text('payment_terms')->nullable();
            $table->string('check_in_time', 10)->nullable();
            $table->string('check_out_time', 10)->nullable();
            $table->text('homestay_terms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_settings');
    }
};