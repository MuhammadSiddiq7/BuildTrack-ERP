<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Holiday;
use App\Models\HouseProject;
use App\Models\HouseProjectHouse;
use App\Models\InspectionReport;
use App\Models\Plan;
use App\Models\PlanActivity;
use App\Models\Project;
use App\Models\User;
use App\Models\Contractor;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('planning_view');
        $trashuser = Plan::onlyTrashed()->count();
        $planing = Plan::with(['project', 'type', 'house', 'contractor', 'user'])
            ->orderBy('created_at', 'desc')->get();

        return view('planing.index', compact('planing', 'trashuser'));
    }

    public function create()
    {
        $this->authorize('planning_create');

        $projects = Project::get();
        $ceos = User::whereHas('roles', function($q) {
            $q->where('name', 'Admin')->orWhere('name', 'CEO');})->get();
        $projectManagers = User::whereHas('roles', fn($q) => $q->where('name', 'Project Manager'))->get();
        $consultants = User::whereHas('roles', fn($q) => $q->where('name', 'Consultant'))->get();
        $clients = User::whereHas('roles', fn($q) => $q->where('name', 'Client'))->get();
        $contractorUsers = User::whereNotNull('contractor_id')->get();
        $planningEngineers = User::whereHas('roles', fn($q) => $q->where('name', 'Planning Engineer'))->get();
        $qualitySupervisors = User::whereHas('roles', fn($q) => $q->where('name', 'Quality Supervisor'))->get();

        return view('planing.create', compact(
            'projects',
            'ceos',
            'projectManagers',
            'consultants',
            'clients',
            'contractorUsers',
            'planningEngineers',
            'qualitySupervisors'
        ));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'project_id' => 'required|exists:projects,id',
    //         'house_type_id' => 'required|exists:house_projects,id',
    //         'plan_manager_id' => 'nullable|exists:users,id',
    //         'plan_consultant_id' => 'nullable|exists:users,id',
    //         'plan_client_id' => 'nullable|exists:users,id',
    //         'plan_contractor_id' => 'nullable|exists:users,id',
    //         'plan_planning_engineer_id' => 'nullable|exists:users,id',
    //         'plan_quality_supervisor_id' => 'nullable|exists:users,id',
    //         'status' => 'required|in:active,inactive',
    //     ]);
    //     // dd($request->all());
    //     try {
    //         $houses = HouseProjectHouse::where('house_project_id', $request->house_type_id)->get();

    //         foreach ($houses as $house) {
    //             $contractor = DB::table('contractor_project')
    //                 ->join('contractors', 'contractor_project.contractor_id', '=', 'contractors.id')
    //                 ->where('contractor_project.house_project_id', $request->house_type_id)
    //                 ->select('contractors.id')
    //                 ->first();

    //             if ($contractor) {
    //                 Plan::create([
    //                     'project_id' => $request->project_id,
    //                     'house_project_id' => $request->house_type_id,
    //                     'house_project_houses_id' => $house->id,
    //                     'contractor_id' => $contractor->id,
    //                     'plan_manager_id' => $request->plan_manager_id,
    //                     'plan_consultant_id' => $request->plan_consultant_id,
    //                     'plan_client_id' => $request->plan_client_id,
    //                     'plan_contractor_id' => $request->plan_contractor_id,
    //                     'plan_planning_engineer_id' => $request->plan_planning_engineer_id,
    //                     'plan_quality_supervisor_id' => $request->plan_quality_supervisor_id,
    //                     'status' => $request->status,
    //                 ]);
    //             }
    //         }

    //         return redirect()->route('plan.index')->with('success', 'Plans created for all houses in selected type.');

    //     } catch (Exception $e) {
    //         Log::error('Plan creation failed: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Error creating plan: ' . $e->getMessage());
    //     }
    // }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'house_type_id' => 'required',
            'house_project_houses_id' => 'required|array',
            'house_project_houses_id.*' => 'exists:house_project_houses,id',
            'contractor_id' => 'required',
            'amount' => 'required',
            'user_id' => 'nullable|exists:users,id',
            'plan_ceo_id' => 'nullable|exists:users,id',
            'plan_manager_id' => 'nullable|exists:users,id',
            'plan_consultant_id' => 'nullable|exists:users,id',
            'plan_client_id' => 'nullable|exists:users,id',
            'plan_contractor_id' => 'nullable|exists:users,id',
            'plan_planning_engineer_id' => 'nullable|exists:users,id',
            'plan_quality_supervisor_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);
            // dd($request->all());

        try {
            foreach ($request->house_project_houses_id as $houseId) {
            $plan = new Plan();
            $plan->project_id = $request->project_id;
            $plan->house_project_id = $request->house_type_id;
            // $plan->house_project_houses_id = $request->house_project_houses_id;
            $plan->house_project_houses_id = $houseId;
            $plan->contractor_id = $request->contractor_id;
            $plan->amount = $request->amount;
            $plan->user_id = $request->user_id;
            $plan->plan_ceo_id = $request->plan_ceo_id;
            $plan->plan_manager_id = $request->plan_manager_id;
            $plan->plan_consultant_id = $request->plan_consultant_id;
            $plan->plan_client_id = $request->plan_client_id;
            $plan->plan_contractor_id = $request->plan_contractor_id;
            $plan->plan_planning_engineer_id = $request->plan_planning_engineer_id;
            $plan->plan_quality_supervisor_id = $request->plan_quality_supervisor_id;
            $plan->status = $request->status;
            $plan->save();

                logUserActivity('Plan', 'Create Plan ' . $plan->id, $plan->id, 'Plan');
            }
            return redirect()->route('plan.index')->with('success', 'Plan created successfully.');
        } catch (Exception $e) {
            Log::error('Plan creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating plan: ' . $e->getMessage());
        }
    }

    public function getContractorsByProject($projectId)
    {
        $contractors = DB::table('contractor_project')
            ->join('contractors', 'contractor_project.contractor_id', '=', 'contractors.id')
            ->where('contractor_project.project_id', $projectId)
            ->select('contractors.id', 'contractors.name')
            ->distinct()
            ->get();

        return response()->json($contractors);
    }

    public function getHouseTypesByContractor($projectId, $contractorId)
    {
       $houseTypes = HouseProject::where('project_id', $projectId)
        ->whereHas('contractors', function ($query) use ($contractorId) {
            $query->where('contractor_id', $contractorId);
        })
        ->whereNotNull('house_type_id')->select('id', 'house_type_id')->distinct()->get();

        return response()->json($houseTypes);
    }

    public function getHouses($houseProjectId)
    {
        $usedHouseIds = DB::table('plans')->whereNotNull('house_project_houses_id')
        ->pluck('house_project_houses_id')->toArray();

        $houses = HouseProjectHouse::where('house_project_id', $houseProjectId)
        ->whereNotIn('id', $usedHouseIds)->get();
        return response()->json($houses);
    }

    public function edit($id)
    {
        $this->authorize('planning_edit');
        $plan = Plan::findOrFail($id);
        return view('planing.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'status' => 'required|in:active,inactive',
            'department' => 'required',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'password' => 'nullable|string|min:3',
        ]);
        try {
            $plan = Plan::findOrFail($id);
            logUserActivity('Plan', 'Update Plan ' . $plan->name, $plan->id, 'Plan');
            $plan->name = $request->name;
            $plan->email = $request->email;
            $plan->status = $request->status;
            $plan->department = $request->department;
            // $plan->role_id = $request->role_id;
            if (!empty($request->password)) {
                $plan->password = Hash::make($request->password);
            }
            $plan->save();
            $plan->syncRoles($request->roles);
            return redirect()->route('plan.index')->with('success', 'Plan updated successfully.');
        } catch (Exception $e) {
            Log::error('Plan update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating plan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $plan = Plan::findOrFail($id);
            logUserActivity('Plan', 'Deleted plan ' . $plan->name, $plan->id, 'Plan');
            $plan->delete();
            return redirect()->route('plan.index')->with('success', 'Plan deleted successfully.');
        } catch (Exception $e) {
            Log::error('Plan deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting plan: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $this->authorize('planning_trash_view');
        $activities = Plan::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('planing.trash', compact('activities'));
    }

    public function restore($id)
    {
        try {
            $plan = Plan::withTrashed()->findOrFail($id);
            $plan->restore();
            return redirect()->route('plan.index')->with('success', 'Plan restored successfully.');
        } catch (Exception $e) {
            Log::error('Plan restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring user: ' . $e->getMessage());
        }
    }

    public function assignActivity($id)
    {
        // dd($id);
        $this->authorize('project_assign_item');

         $planing = Plan::with(['project', 'type', 'house', 'contractor', 'user'])->findOrFail($id);

        // dd($planing);
        $assignedActivities = PlanActivity::with('activity')
            ->where('plan_id', $planing->first()->id ?? null)->get()
            ->groupBy(fn($pa) => $pa->activity->parent_id ?: 'root');


        $assignedIds = PlanActivity::where('plan_id', $planing->first()->id ?? null)
            ->where('house_project_houses_id',$planing->first()->house->id)
            ->pluck('activity_id')
            ->toArray();

        $activity = Activity::with('children')
            ->whereNull('parent_id')
            ->whereNotIn('id', $assignedIds)
            ->get();

        $holidays = Holiday::where('type', 'holiday')->get(['date','start_date','end_date']);
        $weekends = Holiday::where('type', 'weekend')->pluck('date');

        $weekendDays = [];
        foreach ($weekends as $w) {
            $weekendDays[] = \Carbon\Carbon::parse($w)->dayOfWeek;
        }

            // return $assignedActivities;
        return view('planing.assign_activity',compact('activity', 'planing', 'assignedActivities','holidays','weekendDays'));
    }
    // public function assignActivity($id)
    // {
   
    //     $this->authorize('project_assign_item');
    //     $planing = Plan::with(['project', 'type', 'house', 'contractor', 'user'])->findOrFail($id);
       
    //     $assignedActivities = PlanActivity::with('activity')
    //         ->where('plan_id', $planing->id)->get()
    //         ->groupBy(fn($pa) => $pa->activity->parent_id ?: 'root');

    //     $assignedIds = PlanActivity::where('plan_id', $planing->id)
    //         ->pluck('activity_id')->toArray();

    //     // Assign activities display
    //     $activity = Activity::with('children')->whereNull('parent_id')
    //         ->whereNotIn('id', $assignedIds)->get();

    //     $holidays = Holiday::where('type', 'holiday')->get(['date','start_date','end_date']);
    //     $weekends = Holiday::where('type', 'weekend')->pluck('date');

    //     $weekendDays = [];
    //     foreach ($weekends as $w) {
    //         $weekendDays[] = \Carbon\Carbon::parse($w)->dayOfWeek;
    //     }

    //     return view('planing.assign_activity', compact('activity', 'planing',
    //      'assignedActivities', 'holidays', 'weekendDays'));
    // }
    public function assignActivityCombine()
    {
        $this->authorize('project_assign_item');

        $planings = Plan::with('project', 'type')
            ->get()
            ->unique('project_id');

        $assignedActivities = PlanActivity::with('activity')
            ->whereIn('plan_id', $planings->pluck('id'))
            ->get()
            ->groupBy(fn($pa) => $pa->activity->parent_id ?: 'root');

        $assignedIds = PlanActivity::whereIn('plan_id', $planings->pluck('id'))
            ->pluck('activity_id')
            ->toArray();

        $activity = Activity::with('children')
            ->whereNull('parent_id')
            // ->whereNotIn('id', $assignedIds)
            ->get();

        $holidays = Holiday::where('type', 'holiday')->get(['date','start_date','end_date']);
        $weekends = Holiday::where('type', 'weekend')->pluck('date');

        $weekendDays = [];
        foreach ($weekends as $w) {
            $weekendDays[] = \Carbon\Carbon::parse($w)->dayOfWeek;
        }

        return view('planing.assign_activity_combine', compact('activity', 'planings', 'assignedActivities', 'holidays', 'weekendDays'));
    }
    public function getTypesByProject(Request $request)
    {
        $projectId = $request->project_id;

        $types = Plan::where('project_id', $projectId)
            ->with('type')
            ->get()
            ->filter(fn($plan) => $plan->type && $plan->type->house_type_id)
            ->groupBy(fn($plan) => $plan->type->house_type_id)
            ->keys();

        return response()->json($types->values());
    }

    public function getHousesByProjectAndType(Request $request)
    {
        $projectId = $request->project_id;
        $selectedType = $request->type;

        $plans = Plan::where('project_id', $projectId)
            ->whereHas('type', function($q) use ($selectedType) {
                $q->where('house_type_id', $selectedType);
            })
            ->with('house')
            ->get();

        $assignedPlanIds = \App\Models\PlanActivity::pluck('plan_id')->toArray();

        $availablePlans = $plans->reject(function ($plan) use ($assignedPlanIds) {
            return in_array($plan->id, $assignedPlanIds);
        });

        $houses = $availablePlans->map(function ($plan) {
            return [
                'plan_id' => $plan->id,
                'house_id' => $plan->house->id,
                'house_number' => $plan->house->house_number,
            ];
        })->values();

        return response()->json($houses);
    }

    public function storeAssignedActivity(Request $request)
    {

        // return $request->all();
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'activity_ids' => 'required|array',
            'activity_ids.*' => 'exists:activities,id',
            'schedule' => 'nullable|array',
            'start_date' => 'nullable|array',
            'finish_date' => 'nullable|array',
            'original' => 'nullable|array'
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $planAmount = $plan->amount;

        foreach ($request->activity_ids as $activityId) {
            $activity = Activity::findOrFail($activityId);
            $yardstick = (float) $activity->yardstick;
            $calculatedAmount = null;

            if ($yardstick > 0 && $planAmount > 0) {
                $calculatedAmount = ($planAmount * $yardstick) / 100;
            }

            $plan->activities()->attach($activityId, [
                'project_id' => $request->project_id,
                'house_project_houses_id' => $request->house_project_houses_id,
                'house_project_id' => $request->house_project_id,
                'contractor_id' => $request->contractor_id,
                'schedule' => $request->schedule[$activityId] ?? null,
                'start_date' => $request->start_date[$activityId] ?? null,
                'finish_date' => $request->finish_date[$activityId] ?? null,
                'original' => $request->original[$activityId] ?? null,
                'amount' => $calculatedAmount,
            ]);
        }

        // return 'Sucessssssss';

        return redirect()->back()->with('success', 'Activity assigned successfully.');
    }

    public function storeAssignedActivityCombine(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'house_ids' => 'required|array|min:1',
            'plan_id' => 'required|array|min:1',
            'activity_ids' => 'required|array|min:1',
            'activity_ids.*' => 'exists:activities,id',
        ]);

        // Loop over all selected houses & plan IDs (paired by index)
        foreach ($request->plan_id as $index => $planId) {
            $houseId = $request->house_ids[$index] ?? null;
            if (!$houseId) continue;

            // Fetch each plan separately
            $plan = Plan::findOrFail($planId);
            $planAmount = $plan->amount ?? 0;

            foreach ($request->activity_ids as $activityId) {
                $activity = Activity::findOrFail($activityId);
                $yardstick = (float) $activity->yardstick;
                $calculatedAmount = ($yardstick > 0 && $planAmount > 0)
                    ? ($planAmount * $yardstick) / 100
                    : null;

                //Each activity goes into PlanActivity table for that plan
                PlanActivity::create([
                    'plan_id' => $plan->id,
                    'activity_id' => $activityId,
                    'project_id' => $request->project_id,
                    'house_project_houses_id' => $houseId,
                    'house_project_id' => $plan->house_project_id,
                    'contractor_id' => $plan->contractor_id,
                    'schedule' => $request->schedule[$activityId] ?? null,
                    'start_date' => $request->start_date[$activityId] ?? null,
                    'finish_date' => $request->finish_date[$activityId] ?? null,
                    'original' => $request->original[$activityId] ?? null,
                    'amount' => $calculatedAmount,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Activities assigned successfully!');
    }



    public function unassignActivity(Request $request)
    {

        // return 'Sucessss';
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:plan_activity,id',
        ]);

        $itemIds = collect($request->items)->pluck('id')->toArray();

        try {
            PlanActivity::whereIn('id', $itemIds)->delete();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('Unassign failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $plan = Plan::with(['project','type','house','contractor','planActivities.activity'])->findOrFail($id);
        return view('planing.show', compact('plan'));
    }

//    public function contractor_index()
// {
//     //    $contractors = Contractor::with(['houseProjects.project', 'houseProjects.houses'])->get();
//         $contractors = Contractor::all();
//         return view('planing.contractor_index', compact('contractors'));
// }

public function contractor_progress()
{
    // Sare contractors ke saath unke projects aur houses load karte hain
    $contractors = Contractor::with(['houseProjects.project', 'houseProjects.houses'])->get();

    // Chart data prepare
    $chartData = [];

    foreach ($contractors as $contractor) {
        $projectCount = $contractor->houseProjects->groupBy('project_id')->count();
        $houseCount = $contractor->houseProjects->sum(function ($hp) {
            return $hp->houses->count();
        });

        // Agar aapke paas demands ka relation hai to uncomment kar lo:
        // $demandCount = $contractor->itemDemands()->count();
        $demandCount = rand(2, 15); // temporary demo purpose (remove later)

        $chartData[] = [
            'name' => $contractor->name,
            'projects' => $projectCount,
            'houses' => $houseCount,
            'demands' => $demandCount,
        ];
    }

    return view('planing.contractor_progress', compact('chartData', 'contractors'));
}

// public function contractor_progress_detail($contractorId)
// {
//     $plans = Plan::with(['house', 'activities'])
//         ->where('contractor_id', $contractorId)
//         ->get();

//     $allActivities = collect();

//     // Step 1: collect all activities
//     foreach ($plans as $plan) {
//         foreach ($plan->activities as $activity) {
//             $allActivities->push([
//                 'activity_name' => $activity->name ?? 'Unnamed Activity',
//                 'house_name' => $plan->house->house_number ?? 'Unknown',
//                 'start_date' => $activity->pivot->start_date,
//                 'end_date' => $activity->pivot->finish_date,
//                 'progress' => $activity->pivot->project_manager_progress ?? null, // agar field hai
//             ]);
//         }
//     }

//     // Step 2: group by activity name
//     $mergedActivities = $allActivities
//         ->filter(fn($a) => $a['start_date'] && $a['end_date'])
//         ->groupBy('activity_name')
//         ->map(function ($group) {
//             return [
//                 'activity_name' => $group->first()['activity_name'],
//                 'start_date' => $group->min('start_date'),
//                 'end_date' => $group->max('end_date'),
//                 'avg_progress' => round($group->avg('progress'), 1),
//                 'houses_count' => $group->count(),
//             ];
//         })
//         ->values(); // reset index

//     return view('planing.contractor_progress_detail', compact('plans', 'mergedActivities'));
// }

public function contractor_progress_detail($contractorId)
{
    $plans = Plan::with(['house', 'activities'])
        ->where('contractor_id', $contractorId)
        ->get();

    $allActivities = collect();

    foreach ($plans as $plan) {
        foreach ($plan->activities as $activity) {
            $allActivities->push([
                'activity_name' => $activity->name ?? 'Unnamed Activity',
                'house_name' => $plan->house->house_number ?? 'Unknown',
                'start_date' => $activity->pivot->start_date,
                'end_date' => $activity->pivot->finish_date,
                'progress' => $activity->pivot->project_manager_progress 
                              ? (float) str_replace('%','',$activity->pivot->project_manager_progress)
                              : 0,
            ]);
        }
    }

    // Merge same activity names across houses
    $mergedActivities = $allActivities
        ->filter(fn($a) => $a['start_date'] && $a['end_date'])
        ->groupBy('activity_name')
        ->map(function ($group) {
            return [
                'activity_name' => $group->first()['activity_name'],
                'house_name' => implode(',', $group->pluck('house_name')->unique()->toArray()),
                'start_date' => $group->min('start_date'),
                'end_date' => $group->max('end_date'),
                'avg_progress' => round($group->avg('progress'), 1)
            ];
        })
        ->values();

    return view('planing.contractor_progress_detail', compact('plans', 'mergedActivities'));
}





    public function check($id)
    {
        $plan = Plan::with('house')->findOrFail($id);
        $activities = DB::table('plan_activity')
            ->join('activities', 'plan_activity.activity_id', '=', 'activities.id')
            ->where('plan_activity.house_project_houses_id', $id)
            ->select(
                'plan_activity.start_date',
                'plan_activity.finish_date',
                'plan_activity.project_manager_progress as progress',
                'activities.name'
            )
            ->get();


        return view('planing.check', compact('plan', 'activities'));
    }

    public function houseActivitiesChart($houseId)
    {
        // $activities = DB::table('plan_activity')
        //     ->join('activities', 'plan_activity.activity_id', '=', 'activities.id')
        //     ->select('activities.name','plan_activity.start_date',
        //         'plan_activity.finish_date','plan_activity.project_manager_progress'
        //     )->where('plan_activity.house_project_houses_id', $houseId)->get();

        // $activities = $activities->map(function ($item) {
        //     $item->progress =intval($item->project_manager_progress);
        //     return $item;
        // });
        // dd($activities);
  
        $activities = PlanActivity::with('activity')
            ->where('house_project_houses_id', $houseId)
            ->select('plan_activity.start_date', 'plan_activity.finish_date', 'plan_activity.project_manager_progress', 'activities.name')
            ->get();
            
  
        return response()->json($activities);
    }

    public function getauth(){
        return auth()->user();
    }

}
