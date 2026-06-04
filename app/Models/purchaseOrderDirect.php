<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDirect extends Model
{
   protected $table = 'purchase_order_directs';

    protected $guarded = [];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $lastPO = self::latest('id')->first();
            $nextId = $lastPO ? $lastPO->id + 1 : 1;
            $model->po_number = 'PO-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            // Example: PO-00001
        });
    }
    public function items()
    {
        return $this->hasMany(PurchaseOrderDirectItem::class, 'purchase_order_direct_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id'); // Adjust 'item_id' if needed
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function demand()
    {
        return $this->belongsTo(ItemDemand::class, 'item_demand_id');
    }
    public function demands()
    {
        return $this->belongsToMany(ItemDemand::class, 'purchase_order_direct_demand');
    }
    public function contractors()
    {
        return $this->belongsToMany(
            Contractor::class,         // related model
            'item_demand_item',        // pivot table
            'item_demand_id',          // foreign key on pivot
            'contractor_id'            // related key on pivot
        )
        ->withPivot('item_id', 'item_qty')->withTimestamps();
    }
}
