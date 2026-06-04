<?php

namespace App\Http\Controllers;

use App\Exports\MonthlyFuelExport;
use App\Models\Employee;
use App\Models\FuelReport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FuelReportController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('payroll_view');
        $reports = FuelReport::with('employee')->latest()->get();
        $trashedCount = FuelReport::onlyTrashed()->count();

        return view('fuelReport.index', compact('reports', 'trashedCount'));
    }
    public function create()
    {
        $this->authorize('payroll_create');
         $employees = Employee::all();
        return view('fuelReport.create', compact('employees'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'fuel_authorized' => 'required|integer',
            'petrol_rate' => 'required|numeric',
            'date' => 'required|date',
        ]);
        // dd($request->all());
        $reimbursement = $request->fuel_authorized * $request->petrol_rate;

        FuelReport::create([
            'employee_id' => $request->employee_id,
            'fuel_authorized' => $request->fuel_authorized,
            'petrol_rate' => $request->petrol_rate,
            'reimbursement' => $reimbursement,
            'date' => $request->date,
        ]);

        return redirect()->route('fuel.index')->with('success', 'Fuel report created!');
    }
     public function monthFuel(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        [$year, $month] = explode('-', $request->month);
        $filename = "payroll_{$month}_{$year}.xlsx";

        return Excel::download(new MonthlyFuelExport($month, $year), $filename);
    }
}
