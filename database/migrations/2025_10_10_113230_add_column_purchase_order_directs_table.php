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
        Schema::table('purchase_order_directs', function (Blueprint $table) {
            $table->text('merged_from_ids')->nullable()->after('status'); // store JSON array of merged PO IDs
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchhase_order_directs', function (Blueprint $table) {
            //
        });
    }
};
