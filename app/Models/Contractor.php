<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contractor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'contact_number',
        'code',
        'description',
        'no_of_houses',
        'contractor_type',
        'status',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class)
                    ->withTimestamps();
    }
    public function project()
    {
        return $this->hasMany(Project::class , 'contractor_id');
    }
    public function itemDemands()
    {
        return $this->hasMany(ItemDemand::class);
    }
    public function houseProjects()
    {
        return $this->belongsToMany(HouseProject::class, 'contractor_project')
            ->withPivot('status', 'project_id','house_project_id','contract_number')
            ->withTimestamps();
    }
    // public function houseProjectHouse()
    // {
    //     return $this->hasMany(HouseProjectHouse::class, 'hose_project_id');
    // }
        public function houseProjectHouses()
    {
        return $this->hasManyThrough(
            HouseProjectHouse::class,     // Target model
            ContractorProject::class,     // Intermediate model
            'contractor_id',              // Foreign key on contractor_project
            'house_project_id',           // Foreign key on house_project_houses
            'id',                         // Local key on contractors
            'house_project_id'            // Local key on contractor_project
        );
    }
    public function plans()
    {
        return $this->hasMany(Plan::class, 'contractor_id', 'id');
    }

}
