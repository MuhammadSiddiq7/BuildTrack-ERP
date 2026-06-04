<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDepartment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class EmployeeDepartmentController extends Controller
{
     use AuthorizesRequests;

    public function index()
    {
        $this->authorize('employee_department_view');
        $departments = EmployeeDepartment::orderBy('id', 'desc')->get();
        $trashed = EmployeeDepartment::onlyTrashed()->get();
        return view('employee_departments.index', compact('departments', 'trashed'));
    }

    public function create()
    {
        $this->authorize('employee_department_create');
        return view('employee_departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string',
            'code'       => 'required|string|unique:employee_departments,code',
            'description' => 'nullable|string',
            'status'     => 'required|in:active,inactive',
        ]);

        EmployeeDepartment::create($validated);

        return redirect()
            ->route('employee_departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function edit($id)
    {
        $this->authorize('employee_department_edit');
        $department = EmployeeDepartment::findOrFail($id);

        return view('employee_departments.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $department = EmployeeDepartment::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'required|string',
            'code'       => 'required|string|unique:employee_departments,code,' . $id,
            'description' => 'nullable|string',
            // 'company_id' => 'required|exists:companies,id',
            'status'     => 'required|in:active,inactive',
            // 'department_check'  => 'required|boolean',
            // 'is_sira'       => 'nullable|boolean',
            // 'is_lifeguard_licenses' => 'nullable|boolean',
        ]);

        $department->update($validated);

        return redirect()
            ->route('employee_departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function delete($id)
    {
        $this->authorize('employee_department_trash');
        $department = EmployeeDepartment::findOrFail($id);
        $department->delete();

        return redirect()->route('employee_departments.index')->with('success', 'Department soft deleted.');
    }

    public function trash()
    {
        $this->authorize('employee_department_trash_view');
        $departments = EmployeeDepartment::onlyTrashed()->get();
        return view('employee_departments.trash', compact('departments'));
    }

    public function restore($id)
    {
        $this->authorize('employee_department_restore');
        $department = EmployeeDepartment::onlyTrashed()->findOrFail($id);
        $department->restore();
        return redirect()->route('employee_departments.index')->with('success', 'Department restored successfully.');
    }
}
