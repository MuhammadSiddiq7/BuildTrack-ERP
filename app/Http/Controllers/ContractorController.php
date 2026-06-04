<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContractorController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('contractor_view');
        $trashcontractor = Contractor::onlyTrashed()->count();
        $contractors = Contractor::orderBy('created_at', 'desc')->get();
        return view('contractor.index', compact('contractors', 'trashcontractor'));
    }

    public function create()
    {
        $this->authorize('contractor_create');
        return view('contractor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable',
            'code' => 'required|unique:contractors,code,null,id',
            'description' => 'nullable',
            'no_of_houses' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $contractor = new Contractor();
            logUserActivity('Contractor', 'Create Contractor ' . $contractor->name, $contractor->id, 'Contractor');
            $contractor->name = $request->name;
            $contractor->contact_number = $request->contact_number;
            $contractor->code = $request->code;
            $contractor->description = $request->description;
            $contractor->no_of_houses = $request->no_of_houses;
            $contractor->status = $request->status;
            // dd($contractor);
            $contractor->save();

            return redirect()->route('contractor.index')->with('success', 'Contractor created successfully.');
        } catch (Exception $e) {
            Log::error('contractor creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error creating Contractor: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $this->authorize('contractor_edit');
        $contractor = Contractor::findOrFail($id);
        return view('contractor.edit', compact('contractor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable',
            'code' => 'required|unique:contractors,code,' . $id,
            'description' => 'nullable',
            'no_of_houses' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);
        try {
            $contractor = Contractor::findOrFail($id);
            logUserActivity('Contractor', 'Update Contractor ' . $contractor->name, $contractor->id, 'Contractor');
            $contractor->name = $request->name;
            $contractor->contact_number = $request->contact_number;
            $contractor->code = $request->code;
            $contractor->description = $request->description;
            $contractor->no_of_houses = $request->no_of_houses;
            $contractor->status = $request->status;
            $contractor->save();

            return redirect()->route('contractor.index')->with('success', 'Contractor updated successfully.');
        } catch (Exception $e) {
            Log::error('contractor update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating Contractor: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $contractor = Contractor::findOrFail($id);
            logUserActivity('Contractor', 'Delete Contractor ' . $contractor->name, $contractor->id, 'Contractor');
            $contractor->delete();
            return redirect()->route('contractor.index')->with('success', 'Contractor deleted successfully.');
        } catch (Exception $e) {
            Log::error('contractor deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting Contractor: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $this->authorize('contractor_trash_view');
        $contractors = Contractor::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('contractor.trash', compact('contractors'));
    }

    public function restore($id)
    {
        try {
            $contractor = Contractor::withTrashed()->findOrFail($id);
            $contractor->restore();
            return redirect()->route('contractor.index')->with('success', 'Contractor restored successfully.');
        } catch (Exception $e) {
            Log::error('contractor restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring Contractor: ' . $e->getMessage());
        }
    }
}
