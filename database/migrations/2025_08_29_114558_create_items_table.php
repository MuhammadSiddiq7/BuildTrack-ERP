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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->text('item')->nullable();
            $table->date('date')->nullable();
            $table->string('size')->nullable();
            $table->text('deno')->nullable();
            $table->string('per_house_qty')->nullable();
            $table->string('items_type')->nullable();
            $table->string('available_qty')->nullable()->default(NULL);
            $table->string('qty')->nullable()->default(NULL);
            $table->string('rate')->nullable();
            $table->string('specification')->nullable();
            $table->enum('status', ['active', 'inactive'])->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
