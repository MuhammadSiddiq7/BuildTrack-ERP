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

            $table->enum('consultant_status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('contractor_status');
            $table->string('amount')->nullable()->after('original');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('plan_activity', function (Blueprint $table) {
            $table->dropColumn(['consultant_status']);
            $table->dropColumn(['amount']);
        });
    }
};
