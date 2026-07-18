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
        Schema::create('chat_logs', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 64)->index();
            $table->text('user_message');
            $table->longText('found_data')->nullable()->comment('JSON: data found in database search');
            $table->longText('prompt_sent')->nullable()->comment('Complete prompt sent to Gemini');
            $table->longText('ai_response')->nullable();
            $table->unsignedInteger('response_time_ms')->nullable()->comment('Response time in milliseconds');
            $table->string('locale', 10)->default('id');
            $table->boolean('success')->default(true);
            $table->string('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_logs');
    }
};