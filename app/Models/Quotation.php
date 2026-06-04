<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use SoftDeletes;
    protected $table = 'quotations';
    protected $guarded = [];

    public function itemDemand() {
        return $this->belongsTo(ItemDemand::class);
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

}
