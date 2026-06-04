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
        Schema::create('purchase_order_directs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_demand_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->string('po_number')->unique()->nullable();
            $table->string('mou_no')->nullable();
            $table->date('po_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->enum('status', ['open', 'approved', 'delivered', 'closed', 'cancelled'])->default('open');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_directs');
    }
};
