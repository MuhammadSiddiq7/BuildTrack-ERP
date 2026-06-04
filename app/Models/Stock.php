<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'warehouse_id',
        'house_type_id',
        'project_id',
        'contractor_id',
        'date',
        'type',
        'demand_number',
        'purchase_order_number',
        'challan_number',
        'comment',
        'quantity',
        'issued_qty',
        'price',
        'item_id',
        'created_by',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
