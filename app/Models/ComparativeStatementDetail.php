<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComparativeStatementDetail extends Model
{
    protected $guarded = [];

    public function comparativeStatement()
    {
        return $this->belongsTo(ComparativeStatement::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class , 'quotation_id');
    }

}
