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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('item_demand_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('comparative_statement_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->string('po_number')->nullable();
            $table->date('po_date')->nullable();
            $table->text('grand_total')->nullable();
            $table->enum('approve_status', ['pending', 'approved', 'completed'])->default('pending');
            $table->string('created_by')->nullable();
            $table->enum('status', ['active', 'in_active'])->default('in_active');
            // $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
