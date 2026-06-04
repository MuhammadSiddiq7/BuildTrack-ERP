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
        Schema::create('item_demands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->string('demand_no')->nullable();
            $table->string('public_token')->unique()->nullable();
            $table->string('date')->nullable();
            $table->string('created_by')->nullable();
            $table->boolean('approved_by_pm')->default(false);
            $table->boolean('approved_by_sm')->default(false);
            $table->boolean('approved_by_mp')->default(false);
            $table->boolean('approved_by_ceo')->default(false);
            $table->text('remarks_by_pm')->nullable();
            $table->text('remarks_by_sm')->nullable();
            $table->text('remarks_by_mp')->nullable();
            $table->text('remarks_by_ceo')->nullable();
            $table->enum('status', ['pending', 'issued', 'received'])->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_demands');
    }
};
