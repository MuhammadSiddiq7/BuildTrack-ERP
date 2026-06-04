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
        Schema::table('plan_activity', function (Blueprint $table) {
            $table->string('manager_status_date')->nullable()->after('manager_status');
            $table->string('client_status_date')->nullable()->after('client_status');
            $table->string('consultant_status_date')->nullable()->after('consultant_status');
            $table->string('quality_supervisor_date')->nullable()->after('quality_supervisor_progress');
            $table->string('planning_engineer_progress_date')->nullable()->after('planning_engineer_progress');
            $table->string('ceo_progress_date')->nullable()->after('ceo_progress');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_activity', function (Blueprint $table) {
            $table->dropColumn('manager_status_date');
            $table->dropColumn('client_status_date');
            $table->dropColumn('consultant_status_date');
            $table->dropColumn('quality_supervisor_date');
            $table->dropColumn('planning_engineer_progress_date');
            $table->dropColumn('ceo_progress_date');
        });
    }
};
