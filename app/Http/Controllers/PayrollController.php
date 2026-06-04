<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\CompanyBank;
use App\Models\Employee;
use App\Models\EmployeeBank;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\Tax;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonthlyPayrollExport;
use App\Exports\YearlyPayrollExport;

class PayrollController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('payroll_view');
        $payrolls = Payroll::with(['employee', 'companyBank' , 'employeeBank'])->latest()->get();
        $trashedCount = Payroll::onlyTrashed()->count();

        return view('payrolls.index', compact('payrolls', 'trashedCount'));
    }
    public function getEmployeeDetails($id)
    {
        $employee = Employee::with(['project.bank'])->find($id);

        if (!$employee) {
            return response()->json(['success' => false]);
        }

        return response()->json([
            'success' => true,
            'project' => $employee->project ? [
                'id' => $employee->project->id,
                'name' => $employee->project->project_name,
            ] : null,
            'bank' => $employee->project && $employee->project->bank ? [
                'id' => $employee->project->bank->id,
                'name' => $employee->project->bank->name,
            ] : null,
        ]);
    }

    public function getEmployeeAttendanceSalary($employeeId)
    {
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found']);
        }

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $presentDays = Attendance::where('employee_id', $employeeId)
            ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
            ->whereNotNull('check_in')
            // ->whereNotNull('check_out')
            ->count();
        $unpaidLeaves = Leave::where('id_number', $employee->employee_id)
            ->where('leave_type', 'unpaid')
            ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
            ->sum('days_request');

        $paidLeaves = Leave::where('id_number', $employee->employee_id)
            ->where('leave_type', 'paid')
            ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
            ->sum('days_request');
        $workingDaysAfterLeave = max($presentDays - $unpaidLeaves, 0);
        $grossSalary = $workingDaysAfterLeave * ($employee->per_day_salary ?? 0);

        return response()->json([
            'success' => true,
            'working_days' => $presentDays,
            'working_days_after_leave' => $workingDaysAfterLeave,
            'per_day_salary' => $employee->per_day_salary ?? 0,
            'gross_salary' => $grossSalary,
            'paid_leave' => $paidLeaves,
            'unpaid_leave' => $unpaidLeaves
        ]);
    }
    public function create()
    {
        $this->authorize('payroll_create');
        $employees = Employee::with('salary', 'designation', 'project')->get();
        $banks = CompanyBank::all();
        return view('payrolls.create', compact('employees', 'banks'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'pay_date' => 'required|date_format:Y-m-d',
            'arrears' => 'nullable|numeric',
            'recovery' => 'nullable|numeric',
            'absenteeism' => 'nullable|numeric',
            'securityDeposit' => 'nullable|boolean',
        ]);

        $employee = Employee::with(['salary', 'project.bank'])->findOrFail($request->employee_id);
        $salary = $employee->salary;
        $project = $employee->project;
        $bank = $project?->bank;
        $basic = $salary->basic_salary;
        $medical = $basic * 0.10;
        $houseRent = $basic * 0.40;
        $utilities = $basic * 0.10;
        $gross = $basic + $medical + $houseRent + $utilities;
        $arrears = $request->arrears ?? 0;
        $recovery = $request->recovery ?? 0;
        $securityDeposit = $request->has('securityDeposit') ? $basic * 0.05 : 0;
        $absenteeism = $request->absenteeism ?? 0;

        $annualGross = $gross * 12;
        $taxSlab = Tax::where('min_income', '<=', $annualGross)
                    ->where(function($q) use($annualGross){
                        $q->where('max_income', '>=', $annualGross)
                            ->orWhereNull('max_income');
                    })->first();

        $annualTax = 0;
        if ($taxSlab) {
            $excess = max(0, $annualGross - $taxSlab->min_income);
            $annualTax = $taxSlab->fixed_tax + ($excess * ($taxSlab->percentage / 100));
        }
        $monthlyTax = round($annualTax / 12, 2);
        $netPay = $gross + $arrears - ($recovery + $securityDeposit + $monthlyTax + $absenteeism);

        Payroll::create([
            'employee_id' => $employee->id,
            'project_id' => $project?->id,
            'company_bank_id' => $bank?->id,
            'pay_date' => $request->pay_date,
            'basic_salary' => $basic,
            'medical_allowance' => $medical,
            'house_rent' => $houseRent,
            'utilities' => $utilities,
            'gross_salary' => $gross,
            'arrears' => $arrears,
            'recovery' => $recovery,
            'security_deposit' => $securityDeposit,
            'income_tax' => $monthlyTax,
            'absenteeism' => $absenteeism,
            'net_pay' => $netPay,
        ]);

        return redirect()->route('payrolls.index')->with('success', 'Payroll generated successfully!');
    }

    public function edit($id)
    {
        $this->authorize('payroll_edit');
        $payroll = Payroll::findOrFail($id);
        $employees = Employee::all();
        $employeeBankAccounts = EmployeeBank::all();
        $companyBanks = CompanyBank::all();
        return view('payrolls.edit', compact('payroll', 'employees', 'employeeBankAccounts', 'companyBanks'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'employee_id' => 'required',
            'company_bank_id' => 'required',
            'employee_bank_id' => 'required',
            'gross_salary' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'month' => 'nullable',
            'payment_date' => 'nullable|date',
            'remarks' => 'nullable',
        ]);

        Payroll::where('id', $id)->update([
            'company_bank_id'          => $request->company_bank_id,
            'employee_id'         => $request->employee_id,
            'employee_bank_id'         => $request->employee_bank_id,
            'gross_salary'        => $request->gross_salary,
            'deductions'          => $request->deductions,
            'month'        => $request->month,
            'payment_date'      => $request->payment_date,
            'remarks'      => $request->remarks,
        ]);


        return redirect()->route('payrolls.index')->with('success', 'Payroll updated successfully.');
    }
    public function show($id)
    {
        $this->authorize('payroll_show');
        $payroll = Payroll::with(['employee', 'companyBank','employeeBank'])->findOrFail($id);
        // dd($payroll);
        return view('payrolls.show', compact('payroll'));
    }
      public function previewHtml($id)
    {
        // $this->authorize('payroll_pdf');
        $payroll = Payroll::with(['employee','companyBank','project'])->findOrFail($id);
        // dd($payroll);
        return view('payrolls.pdf', compact('payroll'));
    }
public function downloadPdf($id)
{
    $payroll = Payroll::with(['employee','companyBank','project'])->findOrFail($id);

    $pdf = PDF::loadView('payrolls.pdf', [
        'payroll' => $payroll,
        'pdf' => true
    ])->setPaper('a4', 'portrait');

    return $pdf->download('payroll_invoice_' . $payroll->employee->name . '.pdf');
}


    public function downloadByMonth(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        [$year, $month] = explode('-', $request->month);
        $filename = "payroll_{$month}_{$year}.xlsx";

        return Excel::download(new MonthlyPayrollExport($month, $year), $filename);
    }

    public function downloadByYear(Request $request)
    {
        $request->validate([
            'from_year' => 'required|date_format:Y-m-d',
            'to_year' => 'required|date_format:Y-m-d',
        ]);

        $fromYear = \Carbon\Carbon::parse($request->from_year)->year;
        $toYear = \Carbon\Carbon::parse($request->to_year)->year;
        $filename = "payroll_{$fromYear}_to_{$toYear}.xlsx";

        return Excel::download(new YearlyPayrollExport($fromYear, $toYear), $filename);
    }


    public function destroy($id)
    {
        $this->authorize('payroll_trash');
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();
        return redirect()->route('payrolls.index')->with('success', 'Payroll deleted successfully.');
    }

    public function trash()
    {
        $this->authorize('payroll_trash_view');
        $payrolls = Payroll::onlyTrashed()->with(['employee', 'bank'])->latest()->get();
        return view('payrolls.trash', compact('payrolls'));
    }

    public function restore($id)
    {
        Payroll::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('payrolls.trash')->with('success', 'Payroll restored successfully.');
    }

}
