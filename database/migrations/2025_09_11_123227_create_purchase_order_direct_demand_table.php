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
        Schema::create('purchase_order_direct_demand', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_direct_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_demand_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_direct_demand');
    }
};
