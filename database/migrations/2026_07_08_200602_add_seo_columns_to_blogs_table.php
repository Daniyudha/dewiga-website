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
        Schema::table('blogs', function (Blueprint $table) {
            $table->text('meta_keywords_id')->nullable()->after('image');
            $table->text('meta_keywords_en')->nullable()->after('meta_keywords_id');
            $table->text('meta_description_id')->nullable()->after('meta_keywords_en');
            $table->text('meta_description_en')->nullable()->after('meta_description_id');
            $table->string('og_image')->nullable()->after('meta_description_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn([
                'meta_keywords_id',
                'meta_keywords_en',
                'meta_description_id',
                'meta_description_en',
                'og_image',
            ]);
        });
    }
};