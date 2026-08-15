<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->string('number_phone', 20)->nullable()->after('visitor_name');
        });

        // Backfill number_phone from related data:
        // 1. source booking (bookings.number_phone) when source_type = public_booking
        // 2. related bookings
        // 3. related open trip registrations
        // 4. price estimations (whatsapp column)
        $schedules = DB::table('schedules')->get();

        foreach ($schedules as $schedule) {
            $phone = null;

            // 1. From source booking when source_type = public_booking
            if ($schedule->source_type === 'public_booking' && $schedule->source_id) {
                $phone = DB::table('bookings')->where('id', $schedule->source_id)->value('number_phone');
            }

            // 2. From related bookings
            if (empty($phone)) {
                $phone = DB::table('bookings')->where('schedule_id', $schedule->id)->value('number_phone');
            }

            // 3. From related open trip registrations
            if (empty($phone)) {
                $phone = DB::table('open_trip_registrations')->where('schedule_id', $schedule->id)->value('number_phone');
            }

            // 4. From price estimation (whatsapp)
            if (empty($phone) && $schedule->price_estimation_id) {
                $phone = DB::table('price_estimations')->where('id', $schedule->price_estimation_id)->value('whatsapp');
            }

            if (!empty($phone)) {
                DB::table('schedules')->where('id', $schedule->id)->update(['number_phone' => $phone]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('number_phone');
        });
    }
};