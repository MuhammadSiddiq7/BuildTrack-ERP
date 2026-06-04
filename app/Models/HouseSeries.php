<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class HouseSeries extends Model
    {
    protected $fillable = [
        'house_project_id',
        'total_of_houses',
    ];
        public function houseProject()
    {
        return $this->belongsTo(HouseProject::class, 'house_project_id');
    }


}
