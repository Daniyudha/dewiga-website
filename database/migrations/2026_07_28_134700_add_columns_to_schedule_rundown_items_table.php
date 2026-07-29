<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedule_rundown_items', function (Blueprint $table) {
            $table->foreignId('schedule_rundown_id')->nullable()->after('id')->constrained('schedule_rundowns')->cascadeOnDelete();
            $table->foreignId('activity_id')->nullable()->after('end_time')->constrained('activities')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('schedule_rundown_items', function (Blueprint $table) {
            $table->dropForeign(['schedule_rundown_id']);
            $table->dropForeign(['activity_id']);
            $table->dropColumn(['schedule_rundown_id', 'activity_id']);
        });
    }
};