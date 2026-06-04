<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $table = 'user_activities';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
