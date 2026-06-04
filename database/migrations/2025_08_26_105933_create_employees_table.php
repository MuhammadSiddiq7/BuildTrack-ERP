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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_id')->nullable();
            $table->string('name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('email')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('tenure')->nullable();
            $table->string('cnic')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('account_number')->nullable();
            $table->string('dob')->nullable();
            $table->string('age')->nullable();
            $table->string('education')->nullable();
            $table->string('deployment_area')->nullable();
            $table->string('employee_picture')->nullable();
            $table->enum('employee_status', ['active', 'inactive'])->nullable();
            $table->text('comment')->nullable();
            $table->boolean('is_resigned')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
