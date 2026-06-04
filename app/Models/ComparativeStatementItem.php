<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComparativeStatementItem extends Model
{
    // protected $fillable = [
    //     'comparative_statement_id',
    //     'house_project_id',
    //     'item_id',
    //     'supplier_id',
    //     'quantity',
    //     'rate',
    //     'total',
    //     'remarks',
    // ];
 protected $fillable = [
        'comparative_statement_id',
        'item_id',
        'supplier_id',
        'contractor_id',
        'requested_qty',
        'allocated_qty',
        'remaining_qty',
        'rate',
        'total',
        'remarks',
    ];
    // ========
       public function comparativeStatement()
    {
        return $this->belongsTo(ComparativeStatement::class);
    }
     public function houseProject()
    {
        return $this->belongsTo(HouseProject::class);
    }
// ============================
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function attachments()
    {
        return $this->hasMany(ComparativeStatementAttachment::class, 'cs_item_id');
    }

}
