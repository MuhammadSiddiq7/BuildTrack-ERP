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
            $table->string('ir_report_date')->nullable()->after('ceo_progress_date');
            $table->string('ir_report_copy')->nullable()->after('ir_report_date');
            $table->string('status')->nullable()->after('ir_report_copy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_activity', function (Blueprint $table) {
            $table->dropColumn('ir_report_date');
            $table->dropColumn('ir_report_copy');
            $table->dropColumn('status');
        });
    }
};
