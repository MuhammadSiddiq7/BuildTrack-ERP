<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Tax extends Model
{
    use SoftDeletes;
    protected $guarded = [];

     public function scopeForIncome($q, float $income)
    {
        return $q->where('min_income', '<=', $income)
                 ->where(function ($qq) use ($income) {
                    $qq->where('max_income', '>=', $income)
                       ->orWhereNull('max_income');
                 });
    }
}
