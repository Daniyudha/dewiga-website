<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('price_estimations', function (Blueprint $table) {
            $table->string('quotation_status')->default('draft')->after('difference_amount')
                  ->comment('draft, sent, approved, rejected, cancelled');
        });
    }

    public function down(): void
    {
        Schema::table('price_estimations', function (Blueprint $table) {
            $table->dropColumn('quotation_status');
        });
    }
};