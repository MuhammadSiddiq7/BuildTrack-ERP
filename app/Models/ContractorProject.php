<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractorProject extends Model
{
    protected $table = 'contractor_project';
    protected $fillable = [
        'project_id',
        'house_project_id',
        'contractor_id',
        'contract_number',
        'status',
    ];

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }

    public function houseProject()
    {
        return $this->belongsTo(HouseProject::class);
    }
}
