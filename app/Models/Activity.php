<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'activity_code',
        'parent',
        'yardstick',
        'status',
    ];
    public function parent()
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }
      public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_activity')
            ->withPivot(['project_id','house_project_houses_id','house_project_id','contractor_id','schedule','start_date','finish_date','original'])
            ->withTimestamps();
    }

}
