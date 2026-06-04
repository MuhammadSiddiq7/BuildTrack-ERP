<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function type()
    {
        return $this->belongsTo(HouseProject::class, 'house_project_id');
    }

    public function house()
    {
        return $this->belongsTo(HouseProjectHouse::class, 'house_project_houses_id');
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class, 'contractor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'plan_activity')
            ->withPivot(['project_id', 'house_project_houses_id', 'house_project_id', 'contractor_id', 'schedule', 'start_date', 'finish_date', 'original'])
            ->withTimestamps();
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }


    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function planManager()
    {
        return $this->belongsTo(User::class, 'plan_manager_id');
    }
    public function planConsultant()
    {
        return $this->belongsTo(User::class, 'plan_consultant_id');
    }

    public function planClient()
    {
        return $this->belongsTo(User::class, 'plan_client_id');
    }

    public function planContractor()
    {
        return $this->belongsTo(User::class, 'plan_contractor_id');
    }
    public function planActivities()
    {
        return $this->hasMany(PlanActivity::class, 'plan_id');
    }
}
