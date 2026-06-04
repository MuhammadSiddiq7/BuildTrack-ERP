<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComparativeStatementAttachment extends Model
{
   protected $fillable = [
        'cs_item_id',
        'file_path',
    ];

    public function item()
    {
        return $this->belongsTo(ComparativeStatementItem::class, 'cs_item_id');
    }
}
