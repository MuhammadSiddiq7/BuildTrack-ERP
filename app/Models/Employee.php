<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Model
{
    use HasRoles , HasPermissions, SoftDeletes;
    protected $guarded = [];

    // Designation relation
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    // Project relation
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function bank()
    {
        return $this->belongsTo(CompanyBank::class, 'company_bank_id');
    }

    // Department relation
    public function department()
    {
        return $this->belongsTo(EmployeeDepartment::class, 'employee_department_id');
    }

    // Leaves relation
    public function leaves()
    {
        return $this->hasMany(Leave::class, 'id_number', 'employee_id');
    }


    public function bankAccount()
    {
        return $this->hasOne(EmployeeBank::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function salary() {
        return $this->hasOne(EmployeeSalary::class);
    }

    public function allowance() {
        return $this->hasOne(EmployeeAllowance::class);
    }

}
