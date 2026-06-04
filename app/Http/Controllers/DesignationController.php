<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\EmployeeDepartment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesignationController extends Controller
{
     public function index()
    {
        $designations = Designation::with('employee_department')->orderBy('created_at', 'desc')->get();
        $trashCount = Designation::onlyTrashed()->count();
        return view('designation.index', compact('designations', 'trashCount'));
    }

    public function create()
    {
        $departments = EmployeeDepartment::where('status', 'Active')->get();
        return view('designation.create', compact('departments'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'employee_department_id' => 'required|exists:employee_departments,id',
                'name' => 'required|string|max:255',
            ]);

            $designation = Designation::create($validated);

            logUserActivity('Designation', 'Created Designation ' . $designation->name, $designation->id, 'Designation');

            DB::commit();
            return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating designation: ' . $ex->getMessage());
        }
    }


    public function edit(Designation $designation)
    {
        $departments = EmployeeDepartment::where('status', 'Active')->get();
        $designation = Designation::findOrFail($designation->id);
        return view('designation.edit', compact('departments','designation'));
    }


    public function update(Request $request, Designation $designation)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'employee_department_id' => 'required|exists:employee_departments,id',
                'name' => 'required|string|max:255',
            ]);

            $designation->update($validated);

            logUserActivity('Designation', 'Updated Designation ' . $designation->name, $designation->id, 'Designation');

            DB::commit();
            return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating designation: ' . $ex->getMessage());
        }
    }


    public function destroy(Designation $designation)
    {
        $designation->delete();

        return redirect()->route('designations.index')->with('success', 'Designation moved to trash.');
    }


    public function trash()
    {
        $designations = Designation::onlyTrashed()->with('employee_department')->get();

        return view('designation.trash', compact('designations'));
    }


    public function restore($id)
    {
        $designation = Designation::withTrashed()->findOrFail($id);
        $designation->restore();

        return redirect()->route('designations.index')->with('success', 'Designation restored successfully.');
    }

    public function forceDelete($id)
    {
        $designation = Designation::withTrashed()->findOrFail($id);
        $designation->forceDelete();

        return redirect()->route('designations.trash')->with('success', 'Designation permanently deleted.');
    }
}
