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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('company_bank_id')->nullable()->constrained('company_banks')->onDelete('set null');
            // $table->foreignId('company_bank_id')->constrained()->onDelete('cascade'); // 🏦 company bank
            // $table->foreignId('employee_bank_id')->constrained()->onDelete('cascade'); // 👤 employee bank
            // $table->decimal('gross_salary', 10, 2)->default(0);
            // $table->decimal('deductions', 10, 2)->nullable();
            // $table->decimal('net_salary', 10, 2)->default(0);
            // $table->string('month');
            // $table->date('payment_date')->nullable();
            // $table->integer('number_of_days')->nullable();
            // $table->integer('number_of_working_days_after_leave')->nullable();
            // $table->decimal('per_day_salary', 10, 2)->default(0);
            // $table->integer('paid_leave')->nullable();
            // $table->integer('unpaid_leave')->nullable();
            // $table->integer('absents')->default(0);
            // $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            // $table->text('remarks')->nullable();
            // $table->boolean('is_detacted')->default(false);
            $table->string('pay_date')->nullable(); // e.g. 2025-07
            $table->decimal('basic_salary', 15, 2)->nullable();
            $table->decimal('medical_allowance', 15, 2)->default(0);
            $table->decimal('house_rent', 15, 2)->default(0);
            $table->decimal('utilities', 15, 2)->default(0);
            $table->decimal('gross_salary', 15, 2)->nullable();
            $table->decimal('arrears', 15, 2)->default(0);
            $table->decimal('recovery', 15, 2)->default(0);
            $table->decimal('security_deposit', 15, 2)->default(0);
            $table->decimal('income_tax', 15, 2)->default(0);
            $table->decimal('absenteeism', 15, 2)->default(0);
            $table->decimal('net_pay', 15, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
