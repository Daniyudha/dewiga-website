<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('guest_type')->default('lokal')->after('people_count');
        });
        Schema::table('open_trip_registrations', function (Blueprint $table) {
            $table->string('guest_type')->default('lokal')->after('people_count');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('guest_type');
        });
        Schema::table('open_trip_registrations', function (Blueprint $table) {
            $table->dropColumn('guest_type');
        });
    }
};