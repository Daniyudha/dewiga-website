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
        Schema::create('open_trip_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('number_phone', 20);
            $table->string('institution')->nullable();
            $table->date('date');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('travel_package_id')->nullable()->constrained('travel_packages')->nullOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->decimal('amount', 12, 0)->nullable();
            $table->unsignedInteger('people_count')->default(1);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('open_trip_registrations');
    }
};