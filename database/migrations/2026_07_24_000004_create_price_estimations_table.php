<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_estimations', function (Blueprint $table) {
            $table->id();
            $table->string('estimation_number')->unique();
            $table->string('institution_name');
            $table->string('contact_person');
            $table->string('whatsapp');
            $table->date('arrival_date');
            $table->date('departure_date');
            $table->integer('student_count');
            $table->integer('companion_count');
            $table->integer('service_participant_count');
            $table->integer('activity_participant_count');

            // Calculation results
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('actual_price_per_person', 14, 2)->default(0);
            $table->string('rounding_type')->nullable(); // none, up_1000, up_5000, up_10000
            $table->decimal('rounded_price_per_person', 14, 2)->default(0);
            $table->decimal('quotation_total', 14, 2)->default(0);
            $table->decimal('difference_amount', 14, 2)->default(0);

            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('estimation_number');
            $table->index('institution_name');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_estimations');
    }
};