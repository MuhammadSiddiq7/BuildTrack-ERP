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
            $table->enum('quality_supervisor_progress', ['0', '25', '50', '75', '100'])
                ->default('0')
                ->after('contractor_status');

            $table->enum('planning_engineer_progress', ['0', '25', '50', '75', '100'])
                ->default('0')
                ->after('quality_supervisor_progress');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_activity', function (Blueprint $table) {
            $table->dropColumn(['quality_supervisor_progress', 'planning_engineer_progress']);
        });
    }
};
