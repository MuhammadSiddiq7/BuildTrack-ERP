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
        // Schema::create('comparative_statement_items', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('comparative_statement_id')->nullable()->constrained()->onDelete('set null');
        //     $table->foreignId('house_project_id')->nullable()->constrained()->onDelete('set null');
        //     $table->foreignId('item_id')->nullable()->constrained()->onDelete('set null');
        //     $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
        //     $table->integer('quantity')->nullable();
        //     $table->decimal('rate', 10, 2)->nullable();
        //     $table->decimal('total', 10, 2)->nullable();
        //     $table->text('remarks')->nullable();
        //     $table->timestamps();
        // });


        Schema::create('comparative_statement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comparative_statement_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('item_demand_id')->nullable()->constrained()->onDelete('cascade');

            $table->decimal('requested_qty', 10 , 3)->nullable();
            $table->decimal('allocated_qty', 10 , 3)->nullable();
            $table->decimal('remaining_qty', 10 , 3)->nullable();
            $table->decimal('rate', 10, 3)->default(0);
            $table->decimal('total', 15, 3)->default(0);

            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparative_statement_items');
    }
};
