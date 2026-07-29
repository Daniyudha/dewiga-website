<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rundown_template_items', function (Blueprint $table) {
            $table->foreignId('activity_id')->nullable()->constrained('activities')->nullOnDelete()->after('end_time');
            $table->string('person_in_charge')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('rundown_template_items', function (Blueprint $table) {
            $table->dropForeign(['activity_id']);
            $table->dropColumn(['activity_id', 'person_in_charge']);
        });
    }
};