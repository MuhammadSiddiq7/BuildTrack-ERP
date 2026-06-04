<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use PDF;

use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('attendance_view');
        Carbon::setLocale('en');
        config(['app.timezone' => 'Asia/Karachi']);



        $selectedMonth = $request->input('month', now()->format('Y-m'));


        $startDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth()->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth()->endOfDay();


       $employees = Employee::where('employee_status', 'active')
        ->with(['attendances' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('attendance_date', [$startDate, $endDate]);
        }])
        ->get();
        $daysInMonth = $startDate->daysInMonth;
        $today = Carbon::today()->format('Y-m-d');
        $isCurrentMonth = $selectedMonth === Carbon::now()->format('Y-m');

        \Log::info("Attendance Index: today=$today, selectedMonth=$selectedMonth, isCurrentMonth=" . ($isCurrentMonth ? 'true' : 'false'));
        return view('attendance.index', compact('employees', 'selectedMonth', 'daysInMonth', 'today', 'isCurrentMonth'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'attendance' => 'required|in:Present,Absent',
        ]);

        $existing = Attendance::where('employee_id', $data['employee_id'])
            ->where('attendance_date', $data['attendance_date'])
            ->first();

        if (!$existing) {
            Attendance::create($data);
        } else {
            $existing->update(['attendance' => $data['attendance']]);
        }

        return response()->json(['message' => 'Attendance saved successfully']);
    }





    public function downloadReport(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $monthYear = $request->month;


        $startDate = Carbon::parse($monthYear)->startOfMonth();
        $endDate = Carbon::parse($monthYear)->endOfMonth();


        $dates = CarbonPeriod::create($startDate, $endDate);

        
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->get()
            ->keyBy(function ($item) {
                return \Carbon\Carbon::parse($item->attendance_date)->format('Y-m-d');
            });


        return PDF::loadView('attendance.report_pdf', compact('employee', 'dates', 'attendances', 'monthYear'))
            ->download('Attendance_Report_' . $employee->employee_id . '_' . $monthYear . '.pdf');
    }
}
