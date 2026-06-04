<?php

namespace App\Http\Controllers;

use App\Imports\ActivitiesImport;
use App\Models\Activity;
use App\Models\PlanActivity;
use App\Models\User;
use App\Models\Notification;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ActivityController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('activity_view');
        $trashuser = Activity::onlyTrashed()->count();
        // $activities = Activity::get();
        $activities = Activity::with('children')->whereNull('parent_id')->get();
        return view('activity.index', compact('activities', 'trashuser'));
    }

    public function create()
    {
        $this->authorize('activity_create');
        $parents = Activity::where('status', 'active')->get();
        return view('activity.create', compact('parents'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'activity_code' => 'required',
            'name' => 'required',
            'parent_id' => 'nullable',
            'yardstick' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $activity = new Activity();
            logUserActivity('Activity', 'Create Activity ' . $activity->name, $activity->id, 'Activity');
            $activity->name = $request->name;
            $activity->activity_code = $request->activity_code;
            $activity->parent_id = $request->parent_id;
            $activity->yardstick = $request->yardstick;
            $activity->status = $request->status;
            $activity->save();

            return redirect()->route('activity.index')->with('success', 'Activity created successfully.');
        } catch (Exception $e) {
            Log::error('Activity creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error creating activity: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $this->authorize('activity_edit');
        $activity = Activity::findOrFail($id);
        $parents = Activity::whereNull('parent_id')->get();
        // dd($parents);
        return view('activity.edit', compact('activity', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'activity_code' => 'required',
            'name' => 'required',
            'parent_id' => 'nullable',
            'yardstick' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);
        try {
            $activity = Activity::findOrFail($id);

            logUserActivity('Activity', 'Update Activity ' . $activity->name, $activity->id, 'Activity');

            $activity->name = $request->name;
            $activity->activity_code = $request->activity_code;
            $activity->parent_id = $request->parent_id;
            $activity->yardstick = $request->yardstick;
            $activity->status = $request->status;
            $activity->save();
            return redirect()->route('activity.index')->with('success', 'Activity updated successfully.');
        } catch (Exception $e) {
            Log::error('Activity update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating activity: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $activity = Activity::findOrFail($id);
            logUserActivity('Activity', 'Deleted activity ' . $activity->name, $activity->id, 'Activity');
            $activity->delete();
            return redirect()->route('activity.index')->with('success', 'Activity deleted successfully.');
        } catch (Exception $e) {
            Log::error('Activity deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting activity: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $this->authorize('activity_trash_view');
        $activities = Activity::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('activity.trash', compact('activities'));
    }

    public function restore($id)
    {
        try {
            $activity = Activity::withTrashed()->findOrFail($id);
            $activity->restore();
            return redirect()->route('activity.index')->with('success', 'Activity restored successfully.');
        } catch (Exception $e) {
            Log::error('Activity restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring activity: ' . $e->getMessage());
        }
    }
    public function importActivities(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new ActivitiesImport, $request->file('file'));

        return back()->with('success', 'Activities imported successfully!');
    }

    // ------------------- MANAGER -------------------
    public function managerApprove(PlanActivity $planActivity)
    {
       if (!auth()->user()->hasRole('Project Manager') && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();

        if ($planActivity->manager_status !== 'pending') {
            return back()->with('error', 'Manager has already taken action on this activity.');
        }

        $planActivity->update([
            'manager_status' => 'approved',
            'manager_status_date' => now(),
            // 'plan_manager_id' => auth()->id(),
        ]);

      //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'manager_approve_plan_activity_custom',
                    'plan activity',
                    'plan activity approved',
                    'plan activity '.$planActivity->plan_id.' approved by'. $user->department,
                    route('plan.show', $planActivity->project_id)
                );

          //END======================================================================================



        return back()->with('success', 'Activity approved by Manager.');
    }

    public function managerReject(PlanActivity $planActivity)
    {
        if (!auth()->user()->hasRole('Project Manager') && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        if ($planActivity->manager_status !== 'pending') {
            return back()->with('error', 'Manager has already taken action on this activity.');
        }

        $planActivity->update([
            'manager_status' => 'rejected',
            'manager_status_date' => now(),
            // 'plan_manager_id' => auth()->id(),
        ]);

        $user = auth()->user();
              //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'manager_reject_plan_activity_custom',                   
                    'plan activity',                      
                    'plan activity approved',           
                    'plan activity '.$planActivity->plan_id.' approved by'. $user->department,
                    route('plan.show', $planActivity->project_id)
                );
    
          //END======================================================================================


        return back()->with('error', 'Activity rejected by Manager.');
    }
    // ------------------- MANAGER -------------------
    public function consultantApprove(PlanActivity $planActivity)
    {
        if (!auth()->user()->hasRole('Consultant') && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        if ($planActivity->consultant_status !== 'pending') {
            return back()->with('error', 'Consultant has already taken action on this activity.');
        }
            // dd($planActivity);
            $planActivity->update([
                'consultant_status' => 'approved',
                'consultant_status_date' => now(),
                // 'plan_consultant_id' => auth()->id(),
            ]);

            $user = auth()->user();


              //SEND NOTIFICATION CODE START FROM HERE==================================================
                 sendPermissionNotification(
                    'consultant_approve_plan_activity_custom',
                    'plan activity',
                    'plan activity approved',
                    'plan activity '.$planActivity->plan_id.' approved by'. $user->department,
                    route('plan.show', $planActivity->project_id)
                );

               //END======================================================================================

        return back()->with('success', 'Activity approved by Consultant.');
    }

    public function consultantReject(PlanActivity $planActivity)
    {
        if (!auth()->user()->hasRole('Consultant') && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        if ($planActivity->consultant_status !== 'pending') {
            return back()->with('error', 'consultant has already taken action on this activity.');
        }

        $planActivity->update([
            'consultant_status' => 'rejected',
            'consultant_status_date' => now(),
            // 'plan_consultant_id' => auth()->id(),
        ]);

              $user = auth()->user();


              //SEND NOTIFICATION CODE START FROM HERE==================================================
                 sendPermissionNotification(
                    'consultant_reject_plan_activity_custom',                   
                    'plan activity',                      
                    'plan activity approved',           
                    'plan activity '.$planActivity->plan_id.' approved by'. $user->department,
                    route('plan.show', $planActivity->project_id)
                );
    
               //END======================================================================================


        return back()->with('error', 'Activity rejected by Consultant.');
    }

    // ------------------- CLIENT -------------------
    public function clientApprove(PlanActivity $planActivity)
    {
        if (!auth()->user()->hasRole('Client') && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        if ($planActivity->client_status !== 'pending') {
            return back()->with('error', 'Client has already taken action on this activity.');
        }

        $planActivity->update([
            'client_status' => 'approved',
            'client_status_date' => now(),
            // 'plan_client_id' => auth()->id(),
        ]);

        $user = auth()->user();

        //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'client_approve_plan_activity_custom',
                    'plan activity',
                    'plan activity approved',
                    'plan activity '.$planActivity->plan_id.' approved by '. $user->department,
                    route('plan.show', $planActivity->project_id)
                );

          //END======================================================================================

        return back()->with('success', 'Activity approved by Client.');
    }

    public function clientReject(PlanActivity $planActivity)
    {
        if (!auth()->user()->hasRole('Client') && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        if ($planActivity->client_status !== 'pending') {
            return back()->with('error', 'Client has already taken action on this activity.');
        }

        $planActivity->update([
            'client_status' => 'rejected',
            'client_status_date' => now(),
            // 'plan_client_id' => auth()->id(),
        ]);

            $user = auth()->user();

        //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'client_reject_plan_activity_custom',                   
                    'plan activity',                      
                    'plan activity approved',           
                    'plan activity '.$planActivity->plan_id.' approved by '. $user->department,
                    route('plan.show', $planActivity->project_id)
                );
    
          //END======================================================================================


        return back()->with('error', 'Activity rejected by Client.');
    }


    // ------------------- CONTRACTOR -------------------
    public function contractorApprove(PlanActivity $planActivity)
    {
        if (!(auth()->user()->is_contractor == 1 || auth()->user()->contractor_id > 0 || auth()->user()->hasRole('Admin'))) {
            abort(403, 'Unauthorized action.');
        }

        if ($planActivity->contractor_status !== 'pending') {
            return back()->with('error', 'Contractor has already taken action on this activity.');
        }

        $planActivity->update([
            'contractor_status' => 'approved',
            'contractor_id'     => auth()->user()->contractor_id,
        ]);

        return back()->with('success', 'Activity approved by Contractor.');
    }

    public function contractorReject(PlanActivity $planActivity)
    {
        if (!(auth()->user()->is_contractor == 1 || auth()->user()->contractor_id > 0 || auth()->user()->hasRole('Admin'))) {
            abort(403, 'Unauthorized action.');
        }

        if ($planActivity->contractor_status !== 'pending') {
            return back()->with('error', 'Contractor has already taken action on this activity.');
        }

        $planActivity->update([
            'contractor_status' => 'rejected',
            'contractor_id'     => auth()->user()->contractor_id,
        ]);

        return back()->with('error', 'Activity rejected by Contractor.');
    }


    // ------------------- PROGRESS UPDATE -------------------
    public function updateProgress(Request $request, PlanActivity $planActivity)
    {

        if (!auth()->user()->hasAnyRole(['Admin', 'Quality Supervisor', 'Planning Engineer'])) {
            return response()->json(['error' => 'You are not authorized to update progress.'], 403);
        }
        $request->validate([
            'field' => 'required|in:project_manager_progress,planning_engineer_progress,ceo_progress',
            // 'value' => 'required|in:0,25,50,75,100',
            'value' => 'required|numeric|min:0|max:100',
        ]);
        $currentValue = (float) $planActivity->{$request->field};
        $newValue = (float) $request->value;

        if ($newValue < $currentValue) {
            return response()->json(['error' => 'Progress cannot be decreased once updated.'], 422);
        }
        $canSelect100 = $planActivity->manager_status === 'approved'
            && $planActivity->client_status === 'approved'
            && $planActivity->consultant_status === 'approved';

        $isRejected = $planActivity->manager_status === 'rejected'
            || $planActivity->client_status === 'rejected'
            || $planActivity->consultant_status === 'rejected';

        if ($request->value == '100' && !$canSelect100) {
            return response()->json(['error' => 'Cannot select 100% unless all are approved.'], 422);
        }

        if ((int) $request->value > 75 && $isRejected) {
            return response()->json(['error' => 'Cannot go beyond 75% if any rejected.'], 422);
        }

        // $planActivity->update([
        //     $request->field => $request->value
        // ]);
        $dateFieldMap = [
            'project_manager_progress'   => 'project_manager_date',
            'planning_engineer_progress'  => 'planning_engineer_progress_date',
            'ceo_progress'                => 'ceo_progress_date',
        ];

        $updateData = [$request->field => $request->value,];

        if (isset($dateFieldMap[$request->field])) {
            $updateData[$dateFieldMap[$request->field]] = now();
        }

        $planActivity->update($updateData);

        // send notification
        $user = auth()->user();

        // Convert field name to readable format
        $formattedField = str_replace('_', ' ', $request->field);
        $formattedField = ucwords($formattedField); // Capitalize words

        sendPermissionNotification(
            'update_progress_plan_activity_custom',
            $formattedField . ' update',                       // e.g., "Ceo Progressupdated update"
            $formattedField . ' updated',                      // e.g., "Ceo Progressupdated updated"
            'Plan activity ' . $planActivity->plan_id . ' updated by ' . $user->department,
            route('plan.show', $planActivity->project_id)
        );


        return response()->json(['success' => 'Progress updated successfully.']);
    }



}
