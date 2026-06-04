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
        Schema::create('item_demand_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('house_project_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('item_demand_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('house_series_id')->nullable()->constrained('house_series')->onDelete('set null');
            $table->foreignId('item_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('item_qty', 65, 2)->nullable();
            $table->decimal('allocated_qty', 10, 2)->nullable();
            $table->decimal('current_issued', 10, 2)->nullable();
            $table->decimal('previous_issued', 10, 2)->nullable();
            $table->decimal('progressive_total', 10, 2)->nullable();
            $table->decimal('balance_qty', 10, 2)->nullable();
            $table->decimal('over_qty', 10, 2)->nullable();
            $table->text('over_description')->nullable();
            $table->enum('status', ['pending', 'received', 'rejected'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_demand_item');
    }
};
