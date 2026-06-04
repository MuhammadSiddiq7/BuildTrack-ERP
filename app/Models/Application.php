<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    Use SoftDeletes ;

     protected $guarded = [];


        public function department()
    {
        return $this->belongsTo(EmployeeDepartment::class, 'employee_department_id');
    }

        public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

}
