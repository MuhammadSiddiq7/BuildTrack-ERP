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
        Schema::create('comparative_statement_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cs_item_id');
            $table->string('file_path');
            $table->timestamps();

            // ✅ Correct foreign key reference
            $table->foreign('cs_item_id', 'cs_item_fk')
            ->references('id')
            ->on('comparative_statement_items')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparative_statement_attachments');
    }
};
