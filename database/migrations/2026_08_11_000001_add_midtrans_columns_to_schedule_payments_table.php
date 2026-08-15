<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedule_payments', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->after('reference_number');
            $table->string('midtrans_payment_token')->nullable()->after('midtrans_order_id');
            $table->text('midtrans_payment_link')->nullable()->after('midtrans_payment_token');
        });
    }

    public function down(): void
    {
        Schema::table('schedule_payments', function (Blueprint $table) {
            $table->dropColumn(['midtrans_order_id', 'midtrans_payment_token', 'midtrans_payment_link']);
        });
    }
};