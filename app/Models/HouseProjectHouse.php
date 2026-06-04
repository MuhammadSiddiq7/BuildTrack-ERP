<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseProjectHouse extends Model
{
    protected $fillable = [
        'house_project_id',
        'house_number',
    ];

    public function houseProject()
    {
        return $this->belongsTo(HouseProject::class);
    }
}