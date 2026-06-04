<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDirectItem extends Model
{
     protected $table = 'purchase_order_direct_items';

    protected $fillable = [
        'purchase_order_direct_id',
        'item_demand_id',
        'item_id',
        'qty',
        'unit',
        'received_qty',
        'dc_number',
        'dc_copy',
        'rate',
        'total'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrderDirect::class, 'purchase_order_direct_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function itemDemand()
    {
        return $this->belongsTo(ItemDemand::class, 'item_demand_id');
    }

}
