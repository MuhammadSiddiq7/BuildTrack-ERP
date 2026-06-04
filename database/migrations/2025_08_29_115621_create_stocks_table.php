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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contractor_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('house_type_id')->nullable(); // house_type_id add ki
            $table->string('demand_number')->nullable();
            $table->string('purchase_order_number')->nullable();
            $table->string('challan_number')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->string('type')->nullable();
            $table->date('date')->nullable();
            $table->text('comment')->nullable();
            $table->string('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
