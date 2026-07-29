<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('price_estimations', function (Blueprint $table) {
            $table->string('proposal_number')->nullable()->unique()->after('estimation_number');
            $table->string('proposal_status')->default('draft')->after('proposal_number');
            $table->string('proposal_title')->nullable()->after('proposal_status');
            $table->string('program_objective')->nullable()->after('proposal_title');
            $table->string('program_subtitle')->nullable()->after('program_objective');
            $table->text('learning_outputs')->nullable()->after('program_subtitle');
            $table->string('target_participants')->nullable()->after('learning_outputs');
            $table->text('village_advantages')->nullable()->after('target_participants');
            $table->text('facilities_checklist')->nullable()->after('village_advantages');
            $table->json('facilities')->nullable()->after('facilities_checklist');
            $table->date('proposal_sent_at')->nullable()->after('facilities');
            $table->date('approved_at')->nullable()->after('proposal_sent_at');
            $table->foreignId('converted_schedule_id')->nullable()->after('approved_at')->constrained('schedules')->nullOnDelete();
            $table->integer('proposal_version')->default(1)->after('converted_schedule_id');
            $table->foreignId('rundown_template_id')->nullable()->after('proposal_version')->constrained('rundown_templates')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('price_estimations', function (Blueprint $table) {
            $table->dropForeign(['converted_schedule_id']);
            $table->dropForeign(['rundown_template_id']);
            $table->dropColumn([
                'proposal_number',
                'proposal_status',
                'proposal_title',
                'program_objective',
                'program_subtitle',
                'learning_outputs',
                'target_participants',
                'village_advantages',
                'facilities_checklist',
                'facilities',
                'proposal_sent_at',
                'approved_at',
                'converted_schedule_id',
                'proposal_version',
                'rundown_template_id',
            ]);
        });
    }
};