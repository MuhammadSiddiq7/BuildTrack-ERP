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
         Schema::create('comparative_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_demand_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->text('grand_total')->nullable();
            $table->string('created_by')->nullable();
            $table->enum('status', ['active', 'in_active'])->default('in_active');
            $table->enum('approve_status', ['pending', 'approved', 'completed'])->default('pending');
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
        Schema::dropIfExists('comparative_statements');
    }
};
