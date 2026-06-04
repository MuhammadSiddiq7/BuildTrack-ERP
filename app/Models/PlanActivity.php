<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanActivity extends Model
{
    protected $table = 'plan_activity';

    protected $guarded = [];
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
    public function children()
    {
        return $this->hasMany(PlanActivity::class, 'parent_id');
    }
    public function billingRequests()
    {
        return $this->hasMany(BillingRequest::class);
    }
    public function inspectionReports()
    {
        return $this->hasMany(InspectionReport::class, 'plan_activity_id');
    }

}
