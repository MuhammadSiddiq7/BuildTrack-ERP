<?php

use App\Models\User;
use App\Models\Notification;

if (!function_exists('sendPermissionNotification')) {

    function sendPermissionNotification(string $permissionName, string $type, string $subject, string $message, string $url = null)
    {
        $user = auth()->user();
        $usersWithPermission = collect();

        if ($permissionName == 'manager_approve_plan_activity_custom' || $permissionName == 'manager_reject_plan_activity_custom') {//manager approve plan activity
            if ($user->department == 'Project-Manager') {
                $usersWithPermission = User::whereIn('department', ['Consultant', 'CEO'])->get();
            } elseif ($user->department == 'CEO') {
                $usersWithPermission = User::where('department', 'CEO')->get();
            }

        }elseif ($permissionName == 'consultant_approve_plan_activity_custom' || $permissionName == 'consultant_reject_plan_activity_custom') {//consultant approve plan activity
            if ($user->department == 'Consultant') {
                $usersWithPermission = User::whereIn('department', ['Client', 'CEO'])->get();
            } elseif ($user->department == 'CEO') {
                $usersWithPermission = User::where('department', 'CEO')->get();
            }

        }elseif ($permissionName == 'client_approve_plan_activity_custom' || $permissionName == 'client_reject_plan_activity_custom') {//client approve plan activity

           if ($user->department == 'Client' || $user->department == 'CEO') {
                $usersWithPermission = User::where('department', 'CEO')->get();
            }
            
        }elseif ($permissionName == 'update_progress_plan_activity_custom') {//update progress plan activity
            if ($user->department == 'CEO') {
                $usersWithPermission = User::where('department', 'CEO')->get();
            } else{
                $usersWithPermission = User::all();
            }
            
        }elseif ($permissionName == 'IR_consultant_approve_custom' || $permissionName == 'IR_consultant_reject_custom') {//IR consultant approve
            if ($user->department == 'Consultant') {
                $usersWithPermission = User::whereIn('department', ['Project-Manager', 'CEO'])->get();
            } elseif ($user->department == 'CEO') {
                $usersWithPermission = User::where('department', 'CEO')->get();
            }

        }elseif ($permissionName == 'IR_project_manager_approve_custom' || $permissionName == 'IR_project_manager_reject_custom') {//IR project manager approve    
            if ($user->department == 'Project-Manager' || $user->department == 'CEO') {
                $usersWithPermission = User::where('department', 'CEO')->get();
            } 

        }elseif ($permissionName == 'IR_adcc_approve_custom' || $permissionName == 'IR_adcc_reject_custom') {//IR adcc approve
                $usersWithPermission = User::where('department', 'CEO')->get();
        }elseif ($permissionName == 'billing_request_custom'){
                $usersWithPermission = User::whereIn('department', ['Project-Manager', 'CEO','	Planning-Engineer'])->get();
        }elseif ($permissionName == 'Billing_reject_custom' || 'Billing_approve_custom'){
                $usersWithPermission = User::whereIn('department', ['Project-Manager', 'CEO','	Planning-Engineer'])->get();
        }elseif ($permissionName == 'request_ir_custom' || 'request_ir_custom'){
                $usersWithPermission = User::whereIn('department', ['Project-Manager', 'CEO','	Planning-Engineer'])->get();
        }



        if ($usersWithPermission->isEmpty()) {
            $usersWithPermission = User::permission($permissionName)->get();
        }

        if ($usersWithPermission->isEmpty()) {
            return;
        }

        // Create the notification
        $notification = Notification::create([
            'type' => $type,
            'subject' => $subject,
            'message' => $message,
            'is_global' => false,
            'url' => $url
        ]);

        // Attach the notification to each user
        foreach ($usersWithPermission as $user) {
            $user->notifications()->attach($notification->id, ['is_read' => false]);
        }
    }
}
