<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    protected $guarded = ['name', 'brand_name', 'owner_name', 'mou_no', 'mou_date','cnic', 'ntn','contact' ,'address', 'description','status'];

    public function project()
    {
        return $this->hasMany(Project::class, 'supplier_id');
    }
    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_supplier')
                    ->withPivot('purchase_price', 'date', 'remarks')
                    ->withTimestamps();
    }

}
