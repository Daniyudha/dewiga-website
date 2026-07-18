<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('schedule_id')
                ->nullable()
                ->after('travel_package_id')
                ->constrained('schedules')
                ->nullOnDelete();

            $table->enum('status', ['pending', 'confirmed', 'cancelled'])
                ->default('pending')
                ->after('schedule_id');

            $table->text('notes')
                ->nullable()
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropColumn(['schedule_id', 'status', 'notes']);
        });
    }
};