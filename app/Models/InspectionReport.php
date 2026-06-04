<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function planActivity()
    {
        return $this->belongsTo(PlanActivity::class);
    }
}
