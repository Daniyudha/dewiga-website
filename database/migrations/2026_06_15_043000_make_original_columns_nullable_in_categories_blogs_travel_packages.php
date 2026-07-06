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
        // Categories: make name nullable (replaced by name_id/name_en)
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });

        // Blogs: make original columns nullable (replaced by bilingual fields)
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->text('excerpt')->nullable()->change();
            $table->longText('description')->nullable()->change();
        });

        // Travel Packages: make original columns nullable (replaced by bilingual fields)
        Schema::table('travel_packages', function (Blueprint $table) {
            $table->string('type')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->longText('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->text('excerpt')->nullable(false)->change();
            $table->longText('description')->nullable(false)->change();
        });

        Schema::table('travel_packages', function (Blueprint $table) {
            $table->string('type')->nullable(false)->change();
            $table->string('location')->nullable(false)->change();
            $table->longText('description')->nullable(false)->change();
        });
    }
};
