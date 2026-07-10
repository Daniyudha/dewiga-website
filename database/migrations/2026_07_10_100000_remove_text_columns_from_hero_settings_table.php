<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_settings', function (Blueprint $table) {
            $table->dropColumn(['title', 'subtitle', 'description', 'badge_text', 'badge_icon']);
        });
    }

    public function down(): void
    {
        Schema::table('hero_settings', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('badge_icon')->nullable();
        });
    }
};
