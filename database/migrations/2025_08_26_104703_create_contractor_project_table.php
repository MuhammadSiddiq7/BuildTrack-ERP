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
        Schema::create('contractor_project', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('house_project_id')->constrained()->onDelete('cascade');
                $table->foreignId('contractor_id')->constrained()->onDelete('cascade');
                $table->string('contract_number')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractor_project');
    }
};
