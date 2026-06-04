<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Designation;
use App\Models\EmployeeDepartment;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        // $this->authorize('application_view');
        $applications = Application::orderBy('created_at', 'desc')->get();
        $trashCount = Application::onlyTrashed()->count();
        return view('application.index', compact('applications', 'trashCount'));
    }

    public function create()
    {
        $this->authorize('application_view');
         $employee_departments = EmployeeDepartment::where('status', 'Active')->get();
        $designations = Designation::all();
        return view('application.create' , compact('employee_departments', 'designations'));
    }


    public function applicationcreate()
    {
        // $this->authorize('application_view');
        $employee_departments = EmployeeDepartment::where('status', 'Active')->get();
        $designations = Designation::all();
        return view('application.jobapply', compact('employee_departments', 'designations'));
    }



    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'father_name' => 'nullable|string|max:255',
                'surname' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:applications,email',
                'cnic' => 'nullable|string|max:20',
                'mobile' => 'nullable|string|max:20',
                'gender' => 'nullable|in:male,female,other',
                'dob' => 'nullable|date',
                'age' => 'nullable|integer',
                'current_address' => 'nullable|string|max:255',
                'previous_address' => 'nullable|string|max:255',
                'nationality' => 'nullable|string|max:100',
                'city' => 'nullable|string|max:100',
                'marital_status' => 'nullable|string|max:50',
                'employee_department_id' => 'nullable|exists:employee_departments,id',
                'designation_id' => 'nullable|exists:designations,id',
                'currently_employed_company' => 'nullable|string|max:255',
                'currently_employed_designation' => 'nullable|string|max:255',
                'currently_salary' => 'nullable|string|max:50',
                'allowances' => 'nullable|string|max:100',
                'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'job_applied_for' => 'nullable|string|max:255',
                'status' => 'nullable|in:pending,shortlisted,rejected,employeed',
            ]);

            // // File upload
            // if ($request->hasFile('resume')) {
            //     $resumePath = $request->file('resume')->store('resumes', 'public');
            //     $validated['resume'] = $resumePath;
            // }

            // File upload
if ($request->hasFile('resume')) {
    $file = $request->file('resume');
    $fileName = 'resume_' . time() . '.' . $file->getClientOriginalExtension();

    // Folder path (public/uploads/resumes/)
    $folder = public_path('uploads/resumes/');
    if (!file_exists($folder)) {
        mkdir($folder, 0755, true);
    }

    // Move file to folder
    $file->move($folder, $fileName);

    // Save relative path in DB
    $validated['resume'] = 'uploads/resumes/' . $fileName;
}


            $application = Application::create($validated);

            logUserActivity('application', 'Created application ' . $application->name, $application->id, 'application');

            DB::commit();
            return redirect()->route('application.index')->with('success', 'Application created successfully.');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating application: ' . $ex->getMessage());
        }
    }

        public function details($id)
    {
        // $this->authorize('application_details');
        $application = Application::findOrFail($id);
        return view('application.detail', compact('application'));
    }

    public function edit($id)
    {
        $this->authorize('application_edit');
        $application = Application::findOrFail($id);
        return view('application.edit', compact('application'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|email|unique:applications,email,' . $id,
                'mobile' => 'required|numeric',
                'gender' => 'nullable',
                'dob' => 'nullable',
                'current_address' => 'nullable',
                'previous_address' => 'nullable',
                'nationality' => 'nullable',
                'marital_status' => 'nullable',
                'resume' => 'nullable',
                'job_applied_for' => 'nullable',
                'status' => 'nullable',
            ]);

            $application = Application::findOrFail($id);

            if ($request->hasFile('resume')) {
                if ($application->resume && Storage::disk('public')->exists($application->resume)) {
                    Storage::disk('public')->delete($application->resume);
                }
                $resumePath = $request->file('resume')->store('resumes', 'public');
                $validated['resume'] = $resumePath;
            }

            $application->update($validated);

            logUserActivity('application', 'Updated application ' . $application->name, $application->id, 'application');

            DB::commit();
            return redirect()->route('application.index')->with('success', 'Application updated successfully.');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating application: ' . $ex->getMessage());
        }
    }

    public function destroy(application $application)
    {
        $this->authorize('application_trash');
        $application->delete();

        return redirect()->route('application.index')->with('success', 'application moved to trash.');
    }

    public function trash()
    {
        $this->authorize('application_trash_view');
        $applications = Application::onlyTrashed()->get();

        return view('application.trash', compact('applications'));
    }

    public function restore($id)
    {
        $this->authorize('application_restore');
        $application = Application::withTrashed()->findOrFail($id);
        $application->restore();

        return redirect()->route('designation.index')->with('success', 'Designation restored successfully.');
    }
}
