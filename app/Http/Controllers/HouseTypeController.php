<?php

namespace App\Http\Controllers;

use App\Models\HouseType;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HouseTypeController extends Controller
{
     use AuthorizesRequests;
    public function index()
    {
        $this->authorize('houseType_view');
        $trashhouseType = HouseType::onlyTrashed()->count();
        $houseTypes = houseType::orderBy('created_at', 'desc')->get();
        return view('houseType.index', compact('houseTypes', 'trashhouseType'));
    }

    public function create()
    {
        $this->authorize('houseType_create');
        return view('houseType.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'status' => 'nullable|in:active,inactive',
        ]);

        try {
            $houseType = new houseType();
            logUserActivity('houseType', 'Create houseType ' . $houseType->name, $houseType->id, 'houseType');
            $houseType->name = $request->name;
            $houseType->description = $request->description;
            $houseType->status = $request->status;
            $houseType->save();

            return redirect()->route('houseType.index')->with('success', 'houseType created successfully.');
        } catch (Exception $e) {
            Log::error('houseType creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error creating houseType: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $this->authorize('houseType_edit');
        $houseType = houseType::findOrFail($id);
        return view('houseType.edit', compact('houseType'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'status' => 'nullable|in:active,inactive',
        ]);
        try {
            $houseType = houseType::findOrFail($id);
            logUserActivity('houseType', 'Update houseType ' . $houseType->name, $houseType->id, 'houseType');
            $houseType->name = $request->name;
            $houseType->description = $request->description;
            $houseType->status = $request->status;
            $houseType->save();

            return redirect()->route('houseType.index')->with('success', 'houseType updated successfully.');
        } catch (Exception $e) {
            Log::error('houseType update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating houseType: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $houseType = houseType::findOrFail($id);
            logUserActivity('houseType', 'Delete houseType ' . $houseType->name, $houseType->id, 'houseType');
            $houseType->delete();
            return redirect()->route('houseType.index')->with('success', 'houseType deleted successfully.');
        } catch (Exception $e) {
            Log::error('houseType deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting houseType: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $this->authorize('houseType_trash_view');
        $houseTypes = houseType::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('houseType.trash', compact('houseTypes'));
    }

    public function restore($id)
    {
        try {
            $houseType = houseType::withTrashed()->findOrFail($id);
            $houseType->restore();
            return redirect()->route('houseType.index')->with('success', 'houseType restored successfully.');
        } catch (Exception $e) {
            Log::error('houseType restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring houseType: ' . $e->getMessage());
        }
    }
}
