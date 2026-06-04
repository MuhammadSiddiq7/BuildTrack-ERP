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
        Schema::create('purchase_order_direct_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_direct_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_demand_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->decimal('qty', 12, 2)->default(0);
            $table->decimal('received_qty', 12, 2)->default(0);
            $table->string('unit')->nullable();
            $table->string('dc_number')->nullable();
            $table->text('dc_copy')->nullable();
            $table->date('po_date')->nullable();
            $table->text('po_copy')->nullable();
            $table->decimal('rate', 12, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_direct_items');
    }
};
