<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeAllowance;
use App\Models\EmployeeDepartment;
use App\Models\EmployeeSalary;
use App\Models\Project;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;



class EmployeeController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('employee_view');

        $trashemployee = Employee::onlyTrashed()->count();
        $employees = Employee::orderBy('created_at', 'desc')->get();

        return view('employee.index', compact('employees', 'trashemployee',));
    }

    public function create(Request $request)
    {
        $this->authorize('employee_create');
        $employee_departments = EmployeeDepartment::where('status', 'Active')->get();
        $designations = Designation::all();
        $projects = Project::all();
        $banks = Bank::where('status', 'Active')->get();

        return view('employee.create', compact('employee_departments', 'designations', 'banks', 'projects'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $data = $request->validate([
                'employee_id' => 'nullable|string',
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'father_name' => 'required|string|max:255',
                'designation_id' => 'required|exists:designations,id',
                'joining_date' => 'nullable|date',
                'tenure' => 'nullable',
                'cnic' => 'nullable|string|max:15',
                'mobile_number' => 'nullable|string|max:20',
                'account_number' => 'nullable|string|max:50',
                'dob' => 'nullable|date',
                'age' => 'nullable|integer',
                'education' => 'nullable|string|max:255',
                'deployment_area' => 'nullable|string|max:255',
                'project_id' => 'nullable|integer',
                'basic_salary' => 'nullable|string',
                'gross_salary' => 'nullable|string',
                'calling_card' => 'required|in:1,0', // ✅ Yes/No (1 or 0)
                'card_account' => 'nullable|string|max:100', // ✅ dependent on calling_card
                'employee_department_id' => 'required|exists:employee_departments,id',
                'employee_status' => 'required|in:active,inactive',
                'comment' => 'nullable|string',
                'employee_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);


            if ($request->hasFile('employee_picture')) {
                $image = $request->file('employee_picture');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/employees'), $imageName);
                $data['employee_picture'] = 'uploads/employees/' . $imageName;
            }

            // Employee Create
            $employee = Employee::create($request->only([
                'employee_id',
                'name',
                'father_name',
                'email',
                'designation_id',
                'joining_date',
                'tenure',
                'cnic',
                'mobile_number',
                'account_number',
                'dob',
                'age',
                'education',
                'deployment_area',
                'project_id',
                'employee_department_id',
                'employee_status',
                'comment'
            ]) + ['employee_picture' => $data['employee_picture'] ?? null]);

            // Salary
           EmployeeSalary::create([
    'employee_id' => $employee->id,
    'basic_salary' => str_replace(',', '', $request->basic_salary),
    'house_rent' => str_replace(',', '', $request->house_rent),
    'medical' => str_replace(',', '', $request->medical),
    'utilities' => str_replace(',', '', $request->utilities),
    'fuel_allowance_monthly' => str_replace(',', '', $request->fuel_allowance_monthly),
    'gross_salary' => str_replace(',', '', $request->gross_salary),
]);


            // Allowances
            EmployeeAllowance::create([
                'employee_id' => $employee->id,
                'company_vehicle' => $request->company_vehicle ? 1 : 0,
                'fuel_allowance_vehicle' => $request->fuel_allowance_vehicle,
                'card_account' => $request->card_account,
                'calling_card' => $request->calling_card ? 1 : 0,
                'scale_level' => $request->scale_level,
            ]);

            logUserActivity('Employee', 'Created Employee ' . $employee->name, $employee->id, 'Employee');
            DB::commit();

            return redirect()->route('employee.index')->with('success', 'Employee created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Employee Store Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $this->authorize('employee_view');
        $employee = Employee::where('id', $id)->get();
        $employee_salary = EmployeeSalary::where('id', $id)->get();
        $employee_allowance = EmployeeAllowance::where('id', $id)->get();
        return view('employee.show', compact('employee','employee_salary', 'employee_allowance'));
    }

public function edit($id)
{
    $this->authorize('employee_edit');

    $employee = Employee::with(['salary', 'allowance'])->findOrFail($id);

    $employee_departments = EmployeeDepartment::where('status', 'Active')->get();
    $designations = Designation::all();
    $projects = Project::all();
    $banks = Bank::where('status', 'Active')->get();

    return view('employee.edit', compact('employee', 'employee_departments', 'designations', 'projects', 'banks'));
}





 public function update(Request $request, $id)
{
    // dd($request->all());
    DB::beginTransaction();
    try {
        $data = $request->validate([
            'employee_id' => 'nullable|string',
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'designation_id' => 'required|exists:designations,id',
            'joining_date' => 'nullable|date',
            'tenure' => 'nullable',
            'cnic' => 'nullable|string|max:15',
            'mobile_number' => 'nullable|string|max:20',
            'account_number' => 'nullable|string|max:50',
            'dob' => 'nullable|date',
            'age' => 'nullable|integer',
            'education' => 'nullable|string|max:255',
            'deployment_area' => 'nullable|string|max:255',
            'project_id' => 'nullable|integer',
            'basic_salary' => 'nullable|string',
            'house_rent' => 'nullable|string',
            'medical' => 'nullable|string',
            'utilities' => 'nullable|string',
            'fuel_allowance_monthly' => 'nullable|string',
            'gross_salary' => 'nullable|string',
            'calling_card' => 'required|in:1,0',
            'card_account' => 'nullable|string|max:100',
            'employee_department_id' => 'required|exists:employee_departments,id',
            'employee_status' => 'required|in:active,inactive',
            'comment' => 'nullable|string',
            'employee_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'company_vehicle' => 'nullable|boolean',
            'fuel_allowance_vehicle' => 'nullable|numeric',
            'scale_level' => 'nullable|string|max:50',
        ]);

        $employee = Employee::findOrFail($id);

        // ✅ Image update
        if ($request->hasFile('employee_picture')) {
            // Purani image delete (agar exist karti hai)
            if ($employee->employee_picture && file_exists(public_path($employee->employee_picture))) {
                unlink(public_path($employee->employee_picture));
            }

            $image = $request->file('employee_picture');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/employees'), $imageName);
            $data['employee_picture'] = 'uploads/employees/' . $imageName;
        }

        // ✅ Employee Update
        $employee->update($request->only([
            'employee_id',
            'name',
            'father_name',
            'email',
            'designation_id',
            'joining_date',
            'tenure',
            'cnic',
            'mobile_number',
            'account_number',
            'dob',
            'age',
            'education',
            'deployment_area',
            'project_id',
            'employee_department_id',
            'employee_status',
            'comment'
        ]) + ['employee_picture' => $data['employee_picture'] ?? $employee->employee_picture]);

        // ✅ Salary Update / Create
       $employee->salary()->updateOrCreate(
    ['employee_id' => $employee->id],
    [
        'basic_salary' => $request->basic_salary ? str_replace(',', '', $request->basic_salary) : null,
        'house_rent' => $request->house_rent ? str_replace(',', '', $request->house_rent) : null,
        'medical' => $request->medical ? str_replace(',', '', $request->medical) : null,
        'utilities' => $request->utilities ? str_replace(',', '', $request->utilities) : null,
        'fuel_allowance_monthly' => $request->fuel_allowance_monthly ? str_replace(',', '', $request->fuel_allowance_monthly) : null,
        'gross_salary' => $request->gross_salary ? str_replace(',', '', $request->gross_salary) : null,
    ]
);


        // ✅ Allowances Update / Create
        $employee->allowance()->updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'company_vehicle' => $request->company_vehicle ? 1 : 0,
                'fuel_allowance_vehicle' => $request->fuel_allowance_vehicle,
                'card_account' => $request->card_account,
                'calling_card' => $request->calling_card ? 1 : 0,
                'scale_level' => $request->scale_level,
            ]
        );

        logUserActivity('Employee', 'Updated Employee ' . $employee->name, $employee->id, 'Employee');
        DB::commit();

        return redirect()->route('employee.index')->with('success', 'Employee updated successfully.');
    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Employee Update Error: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);
            $employeeName = $employee->name;
            $employeeId = $employee->id;
            // Log the deletion
            logUserActivity('Employee', 'Deleted Employee ' . $employeeName, $employeeId, 'Employee');

            // Delete the employee
            $employee->delete();

            // Create delete notification
            // $notification = Notification::create([
            //     'type' => 'Employee',
            //     'subject' => 'Employee Deleted',
            //     'message' => 'Employee ' . $employeeName . ' has been deleted' . ($company ? ' from company "' . $company->name . '"' : '') . '.',
            //     'is_global' => true,
            //     'url' => route('employee.index'),
            // ]);

            // // Attach notification to all users
            // $users = User::all();
            // foreach ($users as $user) {
            //     $user->notifications()->attach($notification->id, ['is_read' => false]);
            // }

            DB::commit();
            return redirect()->route('employee.index')->with('success', 'Employee deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Employee Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while deleting the Employee.');
        }
    }

    public function trash()
    {
        $this->authorize('employee_trash_view');
        $employees = Employee::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('employee.trash', compact('employees'));
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::onlyTrashed()->findOrFail($id);
            $employeeName = $employee->name;
            $employeeId = $employee->id;

            // Restore the employee
            $employee->restore();

            // Log user activity
            logUserActivity('Employee', 'Restored Employee ' . $employeeName, $employeeId, 'Employee');

            // Create notification
            // $notification = Notification::create([
            //     'type' => 'Employee',
            //     'subject' => 'Employee Restored',
            //     'message' => 'Employee ' . $employeeName . ' has been restored' . ($company ? ' for company "' . $company->name . '"' : '') . '.',
            //     'is_global' => true,
            //     'url' => route('employee.index'),
            // ]);

            // // Attach notification to all users
            // $users = User::all();
            // foreach ($users as $user) {
            //     $user->notifications()->attach($notification->id, ['is_read' => false]);
            // }

            DB::commit();
            return redirect()->route('employee.index')->with('success', 'Employee restored successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Employee Restore Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while restoring the Employee.');
        }
    }



    public function import(Request $request)
{
    $this->authorize('employee_create'); // optional, if you use gates

    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
        Excel::import(new EmployeesImport, $request->file('file'));
        return redirect()->route('employee.index')->with('success', 'Employees imported successfully.');
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
        $errors = [];
        foreach ($failures as $failure) {
            $errors[] = 'Row '.$failure->row().': '.implode(', ', $failure->errors());
        }
        return back()->with('error', implode(' | ', $errors));
    } catch (\Exception $e) {
        \Log::error('Employee Import Error: '.$e->getMessage());
        return back()->with('error', 'Import failed: ' . $e->getMessage());
    }
}


}
