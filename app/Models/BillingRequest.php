<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingRequest extends Model
{
    protected $guarded = [];

    public function Activity()
    {
        return $this->belongsTo(Activity::class);
    }
    public function planActivity()
    {
        return $this->belongsTo(PlanActivity::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
