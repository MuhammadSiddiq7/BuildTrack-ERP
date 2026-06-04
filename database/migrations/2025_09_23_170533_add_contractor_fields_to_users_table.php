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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_contractor')->default(false)->after('department');
            $table->foreignId('contractor_id')->nullable()->constrained('contractors')->nullOnDelete()->after('is_contractor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['contractor_id']);
            $table->dropColumn(['is_contractor', 'contractor_id']);
        });
    }
};
