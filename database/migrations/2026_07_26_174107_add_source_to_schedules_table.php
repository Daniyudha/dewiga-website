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
        Schema::table('schedules', function (Blueprint $table) {
            // Add source tracking columns
            $table->string('source_type', 50)->nullable()->after('type')
                  ->comment('manual, public_booking, price_estimation, open_trip, legacy');
            $table->unsignedBigInteger('source_id')->nullable()->after('source_type');
            $table->foreignId('price_estimation_id')->nullable()
                  ->after('source_id')
                  ->constrained('price_estimations')
                  ->nullOnDelete();

            // Add status column (standardized)
            $table->string('status', 50)->nullable()->after('is_active')
                  ->default('pending')
                  ->comment('draft, pending, confirmed, in_progress, completed, cancelled');

            // Add indexes
            $table->index(['source_type', 'source_id'], 'schedules_source_index');
            $table->index('price_estimation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['price_estimation_id']);
            $table->dropIndex('schedules_source_index');
            $table->dropColumn([
                'source_type',
                'source_id',
                'price_estimation_id',
                'status',
            ]);
        });
    }
};