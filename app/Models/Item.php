<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Item extends Model
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'item',
        'warehouse_id',
        'size',
        'deno',
        'qty',
        'available_qty',
        'rate',
        'per_house_qty',
        'specification',
        'date',
        'items_type',
        'status',
    ];
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'item_project_pivot')
                    ->withPivot('quantity', 'house_project_id')
                    ->withTimestamps();
    }
    public function itemProjects()
    {
        return $this->hasMany(ItemProject::class, 'item_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
    public function itemDemands()
    {
        return $this->belongsToMany(ItemDemand::class, 'item_demand_item')
                    ->withPivot('item_qty' , 'quantity', 'contractor_id', 'house_project_id', 'over_qty', 'over_description')
                    ->withTimestamps();
    }
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'item_supplier')
                    ->withPivot('purchase_price', 'date', 'remarks')
                    ->withTimestamps();
    }
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'item_id');
    }

    public function comparativeStatementItems()
    {
        return $this->hasMany(ComparativeStatementItem::class, 'item_id');
    }
    public function comparativeStatements()
    {
        return $this->hasMany(ComparativeStatement::class, 'item_id');
    }

    public function houseProject()
    {
        return $this->belongsTo(HouseProject::class, 'house_project_id');
    }
    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'item_id');
    }

}
