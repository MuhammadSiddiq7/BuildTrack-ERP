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
    Schema::create('leaves', function (Blueprint $table) {
        $table->id();

        // Basic Employee Info
        $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
        $table->string('name');
        $table->string('father_name')->nullable();
        $table->string('employee_department'); // ID Number
        $table->string('employee_designation'); // ID Number
        $table->string('cnic')->nullable();
        $table->string('mobile_number')->nullable();
        $table->string('deployment')->nullable();
        $table->string('email')->nullable();

        // Leave Application Info
        $table->enum('application_type', ['earned', 'casual']); // New Policy
        $table->enum('leave_type', ['paid', 'unpaid']);
        $table->date('date_of_request');
        $table->string('leave_reason')->nullable();
        $table->date('start_date');
        $table->date('end_date');
        $table->integer('days_request')->default(0);
        $table->string('address_during_leave')->nullable();

        // Signature
        $table->longText('employee_signature')->nullable();

        // Approval Flow
        $table->boolean('approved_by_admin_supervisor')->default(false);
        $table->boolean('approved_by_project_manager')->default(false);
        $table->boolean('approved_by_oic')->default(false);
        $table->boolean('approved_by_hr')->default(false);
        $table->boolean('approved_by_ceo')->default(false);

        // Remarks for each approver
        $table->text('remarks_by_admin_supervisor')->nullable();
        $table->text('remarks_by_project_manager')->nullable();
        $table->text('remarks_by_oic')->nullable();
        $table->text('remarks_by_hr')->nullable();
        $table->text('remarks_by_ceo')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
