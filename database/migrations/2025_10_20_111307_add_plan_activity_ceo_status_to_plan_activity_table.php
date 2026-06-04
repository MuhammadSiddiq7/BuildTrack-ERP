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
            $table->enum('ceo_status', ['pending', 'approved', 'rejected'])
                ->default('pending')->after('client_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_activity', function (Blueprint $table) {
            $table->dropColumn(['ceo_status']);
        });
    }
};
