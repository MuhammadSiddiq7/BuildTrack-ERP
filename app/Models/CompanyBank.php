<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyBank extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'branch',
        'account_number',
        'iban',
        'status',
    ];
}
