<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rundown_templates', function (Blueprint $table) {
            $table->string('code')->nullable()->after('name');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('is_active');
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('rundown_templates', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['code', 'created_by']);
            $table->dropSoftDeletes();
        });
    }
};