<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable();
            $table->string('method')->default('GET');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('country')->nullable();
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();

            $table->index('visited_at');
            $table->index('url');
            $table->index('ip_address');
        });
    }

    public function down()
    {
        Schema::dropIfExists('analytics');
    }
};