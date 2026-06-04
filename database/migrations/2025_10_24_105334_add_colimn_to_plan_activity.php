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
          $table->string('project_manager_progress')->nullable()->after('quality_supervisor_progress');
          $table->string('project_manager_date')->nullable()->after('project_manager_progress');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_activity', function (Blueprint $table) {
            $table->dropColumn('project_manager_progress');
            $table->dropColumn('project_manager_date');
        });
    }
};
