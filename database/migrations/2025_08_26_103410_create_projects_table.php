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
       Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->foreignId('company_bank_id')->nullable()->constrained()->onDelete('set null');
    $table->string('project_name')->nullable();
    $table->string('project_number')->nullable();
    $table->integer('number_of_houses')->nullable();  
    $table->string('project_location')->nullable();
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
        Schema::dropIfExists('projects');
    }
};
