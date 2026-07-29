<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->string('payment_number', 50)->nullable()->unique();
            $table->string('payment_type', 50)->default('down_payment')
                  ->comment('down_payment, installment, settlement, refund, adjustment');
            $table->decimal('amount', 14, 2)->default(0);
            $table->date('payment_date');
            $table->string('payment_method', 50)->default('bank_transfer')
                  ->comment('cash, bank_transfer, qris, other');
            $table->string('reference_number', 100)->nullable();
            $table->string('proof_file', 255)->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 50)->default('paid')
                  ->comment('paid, refunded, cancelled');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index('schedule_id');
            $table->index('payment_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_payments');
    }
};