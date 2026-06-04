<?php

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

if (!function_exists('logUserActivity')) {
    function logUserActivity($activityType, $details, $entityId, $entityType) {
        $userActivity = new UserActivity();
        $userActivity->activity_type = $activityType;
        $userActivity->details = $details;
        // $userActivity->user_id = auth()->id();
        $userActivity->user_id = Auth::id();
        $userActivity->entity_id = $entityId;
        $userActivity->entity_type = $entityType;
        $userActivity->ip_address = request()->ip();
        $userActivity->save();
    }
}
