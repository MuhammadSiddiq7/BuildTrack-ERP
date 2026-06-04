<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemProject extends Model
{
    use HasFactory;

    protected $table = 'item_project_pivot';

    protected $fillable = [
       'house_project_id', 'project_id', 'contractor_id', 'item_id', 'brand_id', 'supplier_id','quantity'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_project_pivot')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
    public function houseProject()
    {
        return $this->belongsTo(HouseProject::class, 'house_project_id');
    }

}

