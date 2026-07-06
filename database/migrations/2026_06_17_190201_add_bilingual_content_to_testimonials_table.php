<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->text('content_id')->nullable()->after('content');
            $table->text('content_en')->nullable()->after('content_id');
            $table->string('locale', 10)->default('id')->after('content_en');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['content_id', 'content_en', 'locale']);
        });
    }
};
