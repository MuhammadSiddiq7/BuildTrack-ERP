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
       Schema::create('inspection_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_activity_id')->nullable()->constrained('plan_activity')->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->foreignId('contractor_id')->nullable()->constrained('contractors')->nullOnDelete();
            $table->string('banglow_no')->nullable();
            $table->string('house_type')->nullable();
            $table->string('ir_no')->unique()->nullable();
            $table->string('description_of_work')->nullable();
            $table->string('activity_name')->nullable();
            $table->decimal('activity_amount', 15, 2)->nullable();
            $table->string('contractor_initials')->nullable();
            $table->string('adcc_initials')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('approval_status', ['approved', 'noted', 'rejected'])->default('noted');
            $table->enum('status', ['approved', 'rejected', 'pending'])->default('pending');
            $table->enum('contractor_status', ['approved', 'rejected', 'pending'])->default('pending');
            $table->text('contractor_remarks')->nullable();
            $table->enum('consultant_status', ['approved', 'rejected', 'pending'])->default('pending');
            $table->text('consultant_remarks')->nullable();
            $table->enum('nhs_status', ['approved', 'rejected', 'pending'])->default('pending');
            $table->text('nhs_remarks')->nullable();
            $table->enum('adcc_status', ['approved', 'rejected', 'pending'])->default('pending');
            $table->text('adcc_remarks')->nullable();
            $table->enum('pm_status', ['approved', 'rejected', 'pending'])->default('pending');
            $table->text('pm_remarks')->nullable();
            $table->string('inspected_by')->nullable();
            $table->date('inspection_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_reports');
    }
};
