<?php

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Collection;

if (!function_exists('sendPermissionNotification')) {

    function sendPermissionNotification(
        string $permissionName,
        string $type,
        string $subject,
        string $message,
        string $url = null
    ) {
        $currentUser = auth()->user();
        if (!$currentUser) return;

        $usersWithPermission = collect();

        // Map permission names to department logic
        $permissionDepartments = [
            'manager_approve_plan_activity_custom' => ['Consultant', 'CEO'],             //PM approve plan activity
            'manager_reject_plan_activity_custom' => ['Consultant', 'CEO'],              //PM reject plan activity
            'consultant_approve_plan_activity_custom' => ['Client', 'CEO'],              //consultant approve plan activity
            'consultant_reject_plan_activity_custom' => ['Client', 'CEO'],               //consultant reject plan activity
            'client_approve_plan_activity_custom' => ['CEO'],                            //client approve plan activity
            'client_reject_plan_activity_custom' => ['CEO'],                             //client reject plan activity
            'update_progress_plan_activity_custom' => $currentUser->department === 'CEO' ? ['CEO'] : [], // All users if not CEO
            'IR_consultant_approve_custom' => ['Project-Manager', 'CEO'],                //IR approve 
            'IR_consultant_reject_custom' => ['Project-Manager', 'CEO'],                 //IR reject
            'IR_project_manager_approve_custom' => ['CEO'],                              //IR PM approve
            'IR_project_manager_reject_custom' => ['CEO'],                               //IR PM reject
            'IR_adcc_approve_custom' => ['CEO'],                                         //IR ADCC approve
            'IR_adcc_reject_custom' => ['CEO'],                                          //IR ADCC reject
            'billing_request_custom' => ['Project-Manager', 'CEO', 'Planning-Engineer'], //Billing request 
            'Billing_reject_custom' => ['Project-Manager', 'CEO', 'Planning-Engineer'],  //Billing reject
            'Billing_approve_custom' => ['Project-Manager', 'CEO', 'Planning-Engineer'], //Billing approve
            'request_ir_custom' => ['Project-Manager', 'CEO', 'Planning-Engineer'],      //IR request
        ];

        // Determine target users based on department logic
        if (isset($permissionDepartments[$permissionName])) {
            $departments = $permissionDepartments[$permissionName];

            if (empty($departments) && $permissionName === 'update_progress_plan_activity_custom') {
                // special case: all users except CEO
                $usersWithPermission = User::all();
            } else {
                // Filter users by department
                $usersWithPermission = User::whereIn('department', $departments)->get();
            }
        }

        // Fallback: find users by permission if no users found yet
        if ($usersWithPermission->isEmpty()) {
            $usersWithPermission = User::permission($permissionName)->get();
        }

        if ($usersWithPermission->isEmpty()) {
            return; // No one to notify
        }

        // Create the notification
        $notification = Notification::create([
            'type' => $type,
            'subject' => $subject,
            'message' => $message,
            'is_global' => false,
            'url' => $url,
        ]);

        // Attach the notification to each user
        $usersWithPermission->each(function ($user) use ($notification) {
            $user->notifications()->attach($notification->id, ['is_read' => false]);
        });
    }
}
