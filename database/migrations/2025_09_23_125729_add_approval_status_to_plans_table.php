<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {

            $table->foreignId('plan_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('plan_client_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('plan_contractor_id')->nullable()->constrained('users')->nullOnDelete()->after('plan_client_id');
            $table->foreignId('plan_quality_supervisor_id')->nullable()->constrained('users')->nullOnDelete()->after('plan_contractor_id');
            $table->foreignId('plan_planning_engineer_id')->nullable()->constrained('users')->nullOnDelete()->after('plan_quality_supervisor');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropForeign(['plan_manager_id']);
            $table->dropForeign(['plan_client_id']);
            $table->dropForeign(['plan_contractor_id']);
            $table->dropForeign(['plan_quality_supervisor_id']);
            $table->dropForeign(['plan_planning_engineer_id']);
            $table->dropColumn([
                'plan_manager_id',
                'plan_client_id',
                'plan_contractor_id',
                'plan_quality_supervisor_id',
                'plan_planning_engineer_id'
            ]);
        });
    }
};
