<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(EmployeeDepartment::class, 'employee_department_id');
    }

public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id');
}


}
