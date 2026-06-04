<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ItemDemand extends Model
{
    use SoftDeletes;

    protected $fillable = ['public_token','project_id','demand_no', 'date' , 'created_by','status'];
    protected static function booted()
    {
        static::creating(function ($demand) {
            $demand->public_token = Str::uuid(); // unique token
        });
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
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

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_demand_item')
                    ->withPivot('contractor_id')
                    ->withPivot('house_project_id')
                    ->withPivot('house_series_id')
                    ->withPivot('item_qty')
                    ->withPivot('over_qty')
                    ->withPivot('allocated_qty')
                    ->withPivot('over_description')
                    ->withPivot('current_issued')
                    ->withPivot('previous_issued')
                    ->withPivot('progressive_total')
                    ->withPivot('balance_qty')
                    ->withPivot('status')
                    ->withPivot('comments')
                    ->withTimestamps();
    }
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'item_id');
    }
    public function purchaseOrder()
    {
        return $this->hasMany(PurchaseOrder::class, 'comparative_statement_id');
    }
    public function comparativeStatementItems()
    {
        return $this->hasMany(ComparativeStatementItem::class, 'item_id');
    }
    public function comparativeStatement()
    {
        return $this->hasOne(ComparativeStatement::class, 'item_demand_id');
    }
    public function quotation()
    {
        return $this->hasOne(Quotation::class, 'item_demand_id');
    }
    public function department()
    {
        return $this->belongsTo(EmployeeDepartment::class, 'employee_department_id');
    }
    public function purchaseOrders()
    {
        return $this->belongsToMany(PurchaseOrderDirect::class, 'purchase_order_direct_demand');
    }


}
