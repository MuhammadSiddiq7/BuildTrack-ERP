<?php

namespace App\Models;
use App\Models\HouseType;

use Illuminate\Database\Eloquent\Model;

class HouseProject extends Model
{

      protected $fillable = [
        'project_id',
        'house_type_id',
        'site_square_yard',
        'warehouse_id',        
        'description',
        'total_houses',
    ];
   

    public function houses()
{
    return $this->hasMany(HouseProjectHouse::class, 'house_project_id');
}

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function houseSeries()
    {
        return $this->hasMany(HouseSeries::class, 'house_project_id') ;
    }

    public function contractors()
    {
        return $this->belongsToMany(Contractor::class, 'contractor_project')
            ->withPivot('status', 'project_id','house_project_id','contract_number')
            ->withTimestamps();
    }

        public function items()
    {
        return $this->hasMany(ItemProject::class, 'house_project_id');
    }
public function houseType()
{
    return $this->belongsTo(HouseType::class, 'house_type_id', 'name');
}




}
