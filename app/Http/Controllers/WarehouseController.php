<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Warehouse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WarehouseController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('warehouse_view');
        $trashwarehouse = Warehouse::onlyTrashed()->count();
        $warehouses = Warehouse::orderBy('created_at', 'desc')->get();
        // dd($warehouses);
        return view('warehouse.index', compact('warehouses', 'trashwarehouse'));
    }

    public function create()
    {
        $this->authorize('project_create');
        // $contractors = Contractor::all();
        // $projects = Project::all();
        return view('warehouse.create');
        // , compact('contractors','projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable',
            'description' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $warehouse = new Warehouse();
            logUserActivity('Warehouse', 'Create warehouse ' . $warehouse->name, $warehouse->id, 'Warehouse');
            $warehouse->name = $request->name;
            $warehouse->address = $request->address;
            $warehouse->description = $request->description;
            $warehouse->status = $request->status;
            $warehouse->save();

            return redirect()->route('warehouse.index')->with('success', 'warehouse created successfully.');
        } catch (Exception $e) {
            Log::error('warehouse creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error creating warehouse: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $this->authorize('warehouse_edit');
        $warehouse = Warehouse::findOrFail($id);
        return view('warehouse.edit', compact('warehouse'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable',
            'description' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);
        try {
            $warehouse = Warehouse::findOrFail($id);
            logUserActivity('Warehouse', 'Update warehouse ' . $warehouse->name, $warehouse->id, 'Warehouse');
            $warehouse->name = $request->name;
            $warehouse->address = $request->address;
            $warehouse->description = $request->description;
            $warehouse->status = $request->status;
            $warehouse->save();

            return redirect()->route('warehouse.index')->with('success', 'warehouse updated successfully.');
        } catch (Exception $e) {
            Log::error('warehouse update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating warehouse: ' . $e->getMessage());
        }
    }
    public function assignItem($id)
    {
        $warehouse = Warehouse::with('items')->findOrFail($id);
        $assignedItems = $warehouse->items->pluck('id')->toArray();
        $allItems = Item::all();
        return view('warehouse.assign_item', compact('warehouse', 'allItems', 'assignedItems'));
    }
    public function destroy($id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);
            logUserActivity('Warehouse', 'Delete warehouse ' . $warehouse->name, $warehouse->id, 'Warehouse');
            $warehouse->delete();
            return redirect()->route('warehouse.index')->with('success', 'warehouse deleted successfully.');
        } catch (Exception $e) {
            Log::error('warehouse deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting warehouse: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $this->authorize('warehouse_trash_view');
        $warehouses = Warehouse::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('warehouse.trash', compact('warehouses'));
    }

    public function restore($id)
    {
        try {
            $warehouse = Warehouse::withTrashed()->findOrFail($id);
            $warehouse->restore();
            return redirect()->route('warehouse.index')->with('success', 'warehouse restored successfully.');
        } catch (Exception $e) {
            Log::error('warehouse restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring warehouse: ' . $e->getMessage());
        }
    }
}
