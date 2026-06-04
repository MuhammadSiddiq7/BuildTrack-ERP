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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            // Personal Information
            $table->string('name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('email')->nullable();
            $table->string('cnic')->nullable();
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'engaged', 'other'])->nullable();
            $table->string('previous_address')->nullable();
            $table->string('nationality')->nullable();
            $table->string('city')->nullable();

            // Job Related Info
            $table->foreignId('employee_department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('currently_employed_company')->nullable();
            $table->string('currently_employed_designation')->nullable();
            $table->string('currently_salary')->nullable();
            $table->string('allowances')->nullable();

            // Resume Upload
            $table->string('resume')->nullable();

            // Application Status
            $table->enum('status', ['pending', 'shortlisted', 'rejected', 'employeed'])->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
