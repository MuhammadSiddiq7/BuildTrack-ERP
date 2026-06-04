<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use SoftDeletes;
    protected $fillable = [

        'company_bank_id','project_id','employee_id', 'employee_bank_id','bank_id', 'basic_salary','paid_leave','unpaid_leave', 'house_allowance',
        'transport_allowance', 'absents','pay_date','deductions','payment_date',
        'advance_deduction', 'tax_deduction', 'total_allowances',
        'total_deductions', 'net_salary', 'pay_date', 'remarks',
        'is_detacted','number_of_days', 'per_day_salary', 'basir_salary',

        'medical_allowance', 'house_rent', 'utilities', 'gross_salary','security_deposit',
        'recovery', 'arrears', 'absenteeism','net_pay','income_tax',
    ];

    protected $dates = ['deleted_at'];

    public function employee()
    {
        return $this->belongsTo(Employee::class , 'employee_id');
    }
    public function companyBank()
    {
        return $this->belongsTo(CompanyBank::class, 'company_bank_id');
    }

    public function employeeBank()
    {
        return $this->belongsTo(EmployeeBank::class, 'employee_bank_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
