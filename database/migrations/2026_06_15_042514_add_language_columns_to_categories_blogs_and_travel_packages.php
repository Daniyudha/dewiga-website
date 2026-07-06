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
        // Categories: add name_id and name_en
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_id')->nullable()->after('name');
            $table->string('name_en')->nullable()->after('name_id');
        });

        // Blogs: add bilingual fields for title, excerpt, description
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('title_id')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_id');
            $table->text('excerpt_id')->nullable()->after('excerpt');
            $table->text('excerpt_en')->nullable()->after('excerpt_id');
            $table->longText('description_id')->nullable()->after('description');
            $table->longText('description_en')->nullable()->after('description_id');
        });

        // Travel Packages: add bilingual fields for type, location, description
        Schema::table('travel_packages', function (Blueprint $table) {
            $table->string('type_id')->nullable()->after('type');
            $table->string('type_en')->nullable()->after('type_id');
            $table->string('location_id')->nullable()->after('location');
            $table->string('location_en')->nullable()->after('location_id');
            $table->longText('description_id')->nullable()->after('description');
            $table->longText('description_en')->nullable()->after('description_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_id', 'name_en']);
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn([
                'title_id', 'title_en',
                'excerpt_id', 'excerpt_en',
                'description_id', 'description_en',
            ]);
        });

        Schema::table('travel_packages', function (Blueprint $table) {
            $table->dropColumn([
                'type_id', 'type_en',
                'location_id', 'location_en',
                'description_id', 'description_en',
            ]);
        });
    }
};
