<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_bank_id',
        'project_name',
        'project_number',
        'number_of_houses',
        'project_location',
        'status',
    ];
    public function items()
    {
        return $this->belongsToMany(Item::class , 'item_project_pivot')
                    ->withPivot(['house_project_id', 'contractor_id', 'brand_id', 'supplier_id', 'quantity', 'created_at'])
                    ->withTimestamps();
    }
    public function contractors()
    {
        // return $this->belongsToMany(Contractor::class)->withTimestamps();
        return $this->belongsToMany(Contractor::class)
        ->withPivot('status', 'house_project_id','contract_number')
        ->withTimestamps();
    }
    public function houseProjects()
    {
        return $this->hasMany(HouseProject::class);
    }
    public function itemDemands()
    {
        return $this->hasMany(ItemDemand::class);
    }
     public function bank()
    {
        return $this->belongsTo(CompanyBank::class, 'company_bank_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'project_id');
    }

}
