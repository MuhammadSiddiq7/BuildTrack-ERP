<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory , SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'id',
        'comparative_statement_id',
        'project_id',
        'po_number',
        'po_date',
        'grand_total',
        'created_by',
        'approve_status',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }

}
