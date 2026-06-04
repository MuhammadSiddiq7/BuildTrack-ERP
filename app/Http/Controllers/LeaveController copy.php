<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
    use AuthorizesRequests;
    public function detail($id)
    {
        $this->authorize('leave_view');
        $leave = Leave::findOrFail($id);
        // dd($leave);
        return view('leave.detail', compact('leave'));
    }

    public function show(Request $request)
    {
        $this->authorize('leave_view');
        $leave = Leave::query();

        if ($request->filled('application_type')) {
            $leave->where('application_type', $request->application_type);
        }
        if ($request->filled('id_number')) {
            $leave->where('id_number', 'like', '%' . $request->id_number . '%');
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $leave->where(function ($query) use ($request) {
                $query->whereDate('start_date', '<=', $request->end_date)->whereDate('end_date', '>=', $request->start_date);
            });
        } elseif ($request->filled('start_date')) {
            $leave->whereDate('end_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $leave->whereDate('start_date', '<=', $request->end_date);
        }

        $leave = $leave->get();
        return view('leave.show', compact('leave'));
    }

    public function create()
    {
        $department = EmployeeDepartment::get();
        return view('leave.create', compact('department'));
    }


   public function store(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'name' => 'required|string|max:255',
        'father_name' => 'nullable|string|max:255',
        'employee_department' => 'required|string|max:255',
        'employee_designation' => 'required|string|max:255',
        'cnic' => 'nullable|string|max:50',
        'mobile_number' => 'nullable|string|max:50',
        'deployment' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',

        'application_type' => 'required|in:earned,casual',
        'leave_type' => 'required|in:paid,unpaid',
        'date_of_request' => 'required|date',
        'leave_reason' => 'nullable|string|max:500',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'days_request' => 'required|numeric|min:1',
        'address_during_leave' => 'nullable|string|max:255',
        'employee_signature' => 'nullable|string',
    ]);

    // 🟢 Paid leave entitlement check
    $currentMonth = now()->month;
    $currentYear = now()->year;
    $entitled = $currentMonth * 2.5;

    if ($request->leave_type === 'paid') {
        $employee = Employee::find($request->employee_id);
        if ($employee && $employee->joining_date) {
            $effectiveStart = Carbon::createFromDate($currentYear, 1, 1)->startOfMonth();
            $entitled = round($entitled, 2);

            $taken = Leave::where('employee_id', $request->employee_id)
                ->where('leave_type', 'paid')
                ->whereDate('start_date', '>=', $effectiveStart)
                ->where('approved_by_higher_management', 1)
                ->sum('days_request');

            if (($taken + $request->days_request) > $entitled) {
                $remaining = round($entitled - $taken, 2);

                return back()->withErrors([
                    'days_request' => "❌ Leave limit exceeded. Taken: $taken | Total: $entitled | Remaining: $remaining"
                ])->withInput();
            }
        }
    }

    // 🟢 Save leave
    $leave = new Leave();
    $leave->employee_id = $request->employee_id;
    $leave->name = $request->name;
    $leave->father_name = $request->father_name;
    $leave->employee_department = $request->employee_department;
    $leave->employee_designation = $request->employee_designation;
    $leave->cnic = $request->cnic;
    $leave->mobile_number = $request->mobile_number;
    $leave->deployment = $request->deployment;
    $leave->email = $request->email;

    $leave->application_type = $request->application_type;
    $leave->leave_type = $request->leave_type;
    $leave->date_of_request = $request->date_of_request;
    $leave->leave_reason = $request->leave_reason;
    $leave->start_date = $request->start_date;
    $leave->end_date = $request->end_date;
    $leave->days_request = $request->days_request;
    $leave->address_during_leave = $request->address_during_leave;

    // 🖊 Save signature if provided
    if ($request->employee_signature && preg_match('/^data:image\/(\w+);base64,/', $request->employee_signature)) {
        $signatureData = $request->employee_signature;
        $image = substr($signatureData, strpos($signatureData, ',') + 1);
        $image = str_replace(' ', '+', $image);
        $imageName = 'signature_' . time() . '.jpg';
        $folder = public_path('uploads/signatures/');
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }
        file_put_contents($folder . $imageName, base64_decode($image));
        $leave->employee_signature = 'uploads/signatures/' . $imageName;
    }

    $leave->save();

    return redirect()->back()->with('success', '✅ Leave application submitted successfully.');
}

    public function approve(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $user = auth()->user();
        $action = $request->input('action');
        $remarks = $request->input('remarks');

        $value = $action === 'approve' ? 1 : -1;
        $sendEmail = false;

        if ($user->department == 'hr' && $leave->approved_by_hr == 0) {
            $leave->approved_by_hr = $value;
            $leave->remarks_by_hr = $remarks;

            if ($value == -1) {
                $sendEmail = true;
            }
        } elseif ($user->department == 'operation' && $leave->approved_by_hr == 1 && $leave->approved_by_operation == 0) {
            $leave->approved_by_operation = $value;
            $leave->remarks_by_operation = $remarks;

            if ($value == -1) {
                $sendEmail = true;
            }
        } elseif ($user->department == 'higher_management' && $leave->approved_by_hr == 1 && $leave->approved_by_operation == 1 && $leave->approved_by_higher_management == 0) {
            $leave->approved_by_higher_management = $value;
            $leave->remarks_by_higher_management = $remarks;

            if ($value == 1) {
                $sendEmail = true;
            } elseif ($value == -1) {
                $sendEmail = true;
            }
        } else {
            return back()->with('error', 'Already processed or not allowed!');
        }

        $leave->save();

        if ($sendEmail && $leave->email) {
            $view = $value == 1
                ? 'emails.leave_approved'
                : 'emails.leave_rejected';
        }

        return back()->with('success', 'Leave request ' . $action . 'ed successfully!');
    }




    public function getEmployeeId($employee_id)
    {
        Log::info("Step 1 - Received ID Number:", [$employee_id]);

        $employee = Employee::where('employee_id', $employee_id)->first();
        // dd($employee);
        Log::info("Step 2 - Employee Found:", [$employee]);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found', 'input' => $employee_id], 404);
        }

        return response()->json([
            'name' => $employee->name,
            'father_name' => $employee->father_name,
            'employee_id' => $employee->employee_id,
            'cnic' => $employee->cnic,
            'employee_department_id' => $employee->employee_department_id,
        ]);
    }

}
