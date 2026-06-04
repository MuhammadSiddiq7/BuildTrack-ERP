<?php

namespace App\Http\Controllers;

use App\Models\BillingRequest;
use App\Models\Contractor;
use App\Models\InspectionReport;
use App\Models\PlanActivity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingRequestController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('billing_view');

        $billingRequests = BillingRequest::with([
            'planActivity.plan.contractor',
            'planActivity.plan.house',
            'planActivity.activity',
            'requester',
            'approver'
        ])->orderBy('created_at', 'desc')->get();
        $groupedBillings = $billingRequests->groupBy(function ($billing) {
            return optional($billing->planActivity->plan->contractor)->id;
        });

        return view('planing.billing', compact('groupedBillings'));
    }

    public function store(Request $request, PlanActivity $planActivity)
    {
        if (
            !in_array($planActivity->manager_status, ['approved']) ||
            !in_array($planActivity->consultant_status, ['approved']) ||
            !in_array($planActivity->client_status, ['approved'])
        ) {
            return back()->with('error', 'Activity must be approved before billing request.');
        }
        // dd($planActivity);
        $progress = $planActivity->ceo_progress;
        $alreadyBilled = $planActivity->billingRequests()->sum('percentage');

        if ($progress <= $alreadyBilled) {
            return back()->with('error', 'No new progress available for billing.');
        }

        $eligiblePercentage = $progress - $alreadyBilled;
        $amount = ($planActivity->amount * $eligiblePercentage) / 100;

        BillingRequest::create([
            'plan_activity_id' => $planActivity->id,
            'requested_by' => Auth::id(),
            'percentage' => $eligiblePercentage,
            'amount' => $amount,
            'percent_input' => $progress,
            'date' => now(),
        ]);

        return back()->with('success', 'Billing request submitted successfully.');
    }

    public function approve(BillingRequest $billingRequest)
    {
        $this->authorize('billing_approve');
        $user = Auth::user();
        $billingRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        
      //SEND NOTIFICATION CODE START FROM HERE==================================================
        sendPermissionNotification(
            'Billing_approve_custom',                   
            'Billing approve',                      
            'billing approved',           
            'billing request '.$billingRequest->plan_activity_id.' approved by '. $user->department,
            route('contractor.billing.view', $billingRequest->plan_activity_id)
        );
      //END======================================================================================


        return back()->with('success', 'Billing request approved.');
    }

    public function reject(BillingRequest $billingRequest)
    {
        $user = Auth::user();
        $billingRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

              //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'Billing_reject_custom',                   
                    'Billing reject',                      
                    'Billing rejected',                      
                    'Billing rejected '.$billingRequest->plan_activity_id.' rejected by '. $user->department,
                    route('contractor.billing.view', $billingRequest->plan_activity_id)
                );
            //END======================================================================================


        return back()->with('error', 'Billing request rejected.');
    }

    // contractor Open link billing request Function
    public function contractorCodeForm()
    {
        return view('contractorActivity.contractorCode');
    }
    public function showBilling($code)
    {
        $contractor = Contractor::where('code', $code)
            ->with(['plans.planActivities.activity', 'plans.planActivities.billingRequests'])->first();

        if (!$contractor) {
            if (request()->ajax()) {
                return response()->json(['exists' => false]);
            }
            return back()->with('error', 'Invalid contractor code.');
        }

        if (request()->ajax()) {
            return response()->json(['exists' => true]);
        }

        $plans = $contractor->plans->map(function ($plan) {
            $plan->planActivities = $plan->planActivities->filter(function ($pa) {
                return $pa->manager_status === 'approved' &&
                    $pa->consultant_status === 'approved' &&
                    $pa->client_status === 'approved';
            });
            return $plan;
        });
        // dd($plans);
        return view('contractorActivity.billingList', compact('contractor', 'plans'));
    }

    public function requestBilling(Request $request, PlanActivity $planActivity)
    {
        $user = Auth::user();

        if (
            $planActivity->manager_status !== 'approved' ||
            $planActivity->consultant_status !== 'approved' ||
            $planActivity->client_status !== 'approved'
        ) {
            return back()->with('error', 'This activity is not fully approved yet.');
        }

        $progress = $planActivity->ceo_progress;
        $alreadyBilled = $planActivity->billingRequests()->sum('percentage');

        if ($progress <= $alreadyBilled) {
            return back()->with('error', 'No remaining progress available for billing.');
        }

        $eligiblePercentage = $progress - $alreadyBilled;
        $amount = ($planActivity->amount * $eligiblePercentage) / 100;

        BillingRequest::create([
            'plan_activity_id' => $planActivity->id,
            'requested_by' => '10',
            'percentage' => $eligiblePercentage,
            'percent_input' => $progress,
            'date' => now(),
            'amount' => $amount,
        ]);

        
      //SEND NOTIFICATION CODE START FROM HERE==================================================
        sendPermissionNotification(
            'billing_request_custom',                   
            'Billing Request ',                      
            'Billing Request',           
            'Billing Request for Plan'.$planActivity->plan_id.'by '. $user->department,
            route('plan.show', $planActivity->project_id)
        );
      //END======================================================================================


        return back()->with('success', 'Billing request submitted successfully.');
    }

    public function irReport($planActivityId)
    {
        $activity = PlanActivity::with(['activity.parent', 'plan'])->findOrFail($planActivityId);
        $ir = InspectionReport::where('plan_activity_id', $planActivityId)->first();
        // dd($activity, $ir);
        return view('planing.ir_report', compact('activity', 'ir'));
    }
    public function requestIR(Request $request, PlanActivity $planActivity)
    {
        // return $planActivity->activity->name;
        $plan = $planActivity->plan;
        $user = Auth::user();
        
        $contractor = $plan->contractor ?? null;

        $existingIR = InspectionReport::where('plan_activity_id', $planActivity->id)->first();
        if ($existingIR) {
            return back()->with('error', 'IR request already submitted for this activity.');
        }
        $irNo = 'ADCC-' . str_pad(InspectionReport::max('id') + 1, 6, '0', STR_PAD_LEFT);

        InspectionReport::create([
            'plan_activity_id' => $planActivity->id,
            'plan_id' => $plan->id,
            'contractor_id' => $contractor?->id,
            'banglow_no' => $plan->house->house_number ?? 'N/A',
            'house_type' => $plan->type->house_type_id ?? 'N/A',
            'ir_no' => $irNo,
            'description_of_work' => $planActivity->activity->name ?? 'N/A',
            'activity_name' => $planActivity->activity->name ?? 'N/A',
            'activity_amount' => $request->amount ?? $planActivity->amount ?? 0,
            'status' => 'pending',
            'approval_status' => 'noted',
            'remarks' => 'IR requested by contractor',
            'inspection_date' => now(),
        ]);

      //SEND NOTIFICATION CODE START FROM HERE==================================================
        sendPermissionNotification(
            'request_ir_custom',                   
            'IR Request',                      
            'IR Requested',           
            'IR Request for Plan '.$planActivity->activity->name.' approved by '. $user->department,
            route('contractor.billing.view', $planActivity->plan->contractor->code)
        );
      //END======================================================================================


        return back()->with('success', 'IR request submitted successfully. Awaiting approval.');
    }
    public function contractorApprove(Request $request, $id)
    {
        // dd($request->all());
        $report = InspectionReport::findOrFail($id);
        $report->update([
            'contractor_status' => 'approved',
            'contractor_remarks' => $request->contractor_remarks ?? auth()->user()->name . ' approved this report.'
        ]);
        return back()->with('success', 'Contractor approved the report.');
    }

    public function contractorReject(Request $request, $id)
    {
        $report = InspectionReport::findOrFail($id);
        $report->update([
            'contractor_status' => 'rejected',
            'contractor_remarks' => $request->contractor_remarks ?? auth()->user()->name . ' rejected this report.'
        ]);
        return back()->with('error', 'Contractor rejected the report.');
    }

    public function consultantApprove(Request $request, $id)
    {
        $report = InspectionReport::findOrFail($id);
        $report->update([
            'consultant_status' => 'approved',
            'consultant_remarks' => $request->remarks ?? auth()->user()->name . ' approved this report.'
        ]);

             //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'IR_consultant_approve_custom',                   
                    'Inspection Report',                      
                    'Inspection Report approved',           
                    'inspection report approved by '. auth()->user()->department,
                    route('inspection.report.show', $id)
                );
    
          //END======================================================================================


        return back()->with('success', 'Consultant approved the report.');
    }

    public function consultantReject(Request $request, $id)
    {
        $report = InspectionReport::findOrFail($id);
        $report->update([
            'consultant_status' => 'rejected',
            'consultant_remarks' => $request->remarks ?? auth()->user()->name . ' rejected this report.'
        ]);


             //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'IR_consultant_reject_custom',                   
                    'Inspection Report',                      
                    'Inspection Report rejected',           
                    'inspection report rejected by '. auth()->user()->department,
                    route('inspection.report.show', $id)
                );
    
          //END======================================================================================


        return back()->with('error', 'Consultant rejected the report.');
    }

    public function adccApprove(Request $request, $id)
    {
        $report = InspectionReport::findOrFail($id);
        $report->update([
            'adcc_status' => 'approved',
            'adcc_remarks' => $request->remarks ?? auth()->user()->name . ' approved this report.'
        ]);

            //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'IR_adcc_approve_custom',                   
                    'Inspection Report',                      
                    'Inspection Report approved',           
                    'inspection report approved by '. auth()->user()->department,
                    route('inspection.report.show', $id)
                );
    
          //END======================================================================================
        return back()->with('success', 'ADCC approved the report.');
    }

    public function adccReject(Request $request, $id)
    {
        $report = InspectionReport::findOrFail($id);
        $report->update([
            'adcc_status' => 'rejected',
            'adcc_remarks' => $request->remarks ?? auth()->user()->name . ' rejected this report.'
        ]);

        //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'IR_adcc_reject_custom',                   
                    'Inspection Report',                      
                    'Inspection Report rejected',           
                    'inspection report rejected by '. auth()->user()->department,
                    route('inspection.report.show', $id)
                );
    
          //END======================================================================================

        return back()->with('error', 'ADCC rejected the report.');
    }
    public function pmApprove(Request $request, $id)
    {
        $report = InspectionReport::findOrFail($id);
        $report->update([
            'pm_status' => 'approved',
            'pm_remarks' => $request->remarks ?? auth()->user()->name . ' approved this report.'
        ]);

            //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'IR_project_manager_approve_custom',                   
                    'Inspection Report',                      
                    'Inspection Report approved',           
                    'inspection report approved by '. auth()->user()->department,
                    route('inspection.report.show', $id)
                );
    
          //END======================================================================================

        return back()->with('success', 'PM approved the report.');
    }

    public function pmReject(Request $request, $id)
    {
        $report = InspectionReport::findOrFail($id);
        $report->update([
            'pm_status' => 'rejected',
            'pm_remarks' => $request->remarks ?? auth()->user()->name . ' rejected this report.'
        ]);

                    //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'IR_project_manager_reject_custom',                   
                    'Inspection Report',                      
                    'Inspection Report rejected',           
                    'inspection report rejected by '. auth()->user()->department,
                    route('inspection.report.show', $id)
                );
    
          //END======================================================================================


        
        return back()->with('error', 'PM rejected the report.');
    }
    public function updateGeneralRemarks(Request $request, $id)
    {
        $report = InspectionReport::findOrFail($id);
        $report->update(['remarks' => $request->remarks]);
        return back()->with('success', 'General remarks updated successfully.');
    }
}
