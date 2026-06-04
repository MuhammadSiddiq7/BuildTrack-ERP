<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'employee_department_id',
    ];
    public function employee_department()
    {
        return $this->belongsTo(EmployeeDepartment::class);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'designation_id');
    }
}
