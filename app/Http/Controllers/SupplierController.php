<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
     use AuthorizesRequests;
    public function index()
    {
        $this->authorize('supplier_view');
        $trashsupplier = Supplier::onlyTrashed()->count();
        $suppliers = Supplier::orderBy('created_at', 'desc')->get();
        return view('supplier.index' , compact('suppliers', 'trashsupplier'));
    }

    public function create()
    {
        $this->authorize('supplier_create');

        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'nullable|string|max:255',
            'owner_name' => 'nullable|string|max:255',
            'cnic' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'ntn' => 'nullable',
            'address' => 'nullable',
            'mou_no' => 'nullable',
            'mou_date' => 'nullable',
            'addendum_no' => 'nullable',
            'addendum_date' => 'nullable',
            'description' => 'nullable',
            'contact' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $supplier = new Supplier();
            logUserActivity('Supplier', 'Create Supplier ' . $supplier->name, $supplier->id, 'Supplies');
            $supplier->brand_name = $request->brand_name;
            $supplier->owner_name = $request->owner_name;
            $supplier->cnic = $request->cnic;
            $supplier->name = $request->name;
            $supplier->ntn = $request->ntn;
            $supplier->contact = $request->contact;
            $supplier->brand_name = $request->brand_name;
            $supplier->owner_name = $request->owner_name;
            $supplier->cnic = $request->cnic;
            $supplier->mou_no = $request->mou_no;
            $supplier->mou_date = $request->mou_date;
            $supplier->addendum_no = $request->addendum_no;
            $supplier->addendum_date = $request->addendum_date;
            $supplier->address = $request->address;
            $supplier->description = $request->description;
            $supplier->status = $request->status;
            $supplier->save();

            return redirect()->route('supplier.index')->with('success', 'Supplier created successfully.');
        } catch (Exception $e) {
            Log::error('supplier creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error creating Supplier: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $this->authorize('supplier_edit');
        $supplier = Supplier::findOrFail($id);
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'brand_name' => 'nullable|string|max:255',
            'owner_name' => 'nullable|string|max:255',
            'cnic' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'ntn' => 'nullable',
            'contact' => 'nullable',
            'address' => 'nullable',
            'addendum_no' => 'nullable|string|max:255',
            'addendum_date' => 'nullable|date',
            'description' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);
        try {
            $supplier = Supplier::findOrFail($id);
            logUserActivity('Supplier', 'Create Supplier ' . $supplier->name, $supplier->id, 'Supplies');
            $supplier->brand_name = $request->brand_name;
            $supplier->owner_name = $request->owner_name;
            $supplier->cnic = $request->cnic;
            $supplier->name = $request->name;
            $supplier->brand_name = $request->brand_name;
            $supplier->ntn = $request->ntn;
            $supplier->addendum_no = $request->addendum_no;
            $supplier->addendum_date = $request->addendum_date;
            $supplier->contact = $request->contact;
            $supplier->address = $request->address;
            $supplier->description = $request->description;
            $supplier->status = $request->status;
            $supplier->save();
            return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
        } catch (Exception $e) {
            Log::error('supplier update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating Supplier: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            logUserActivity('Supplier', 'Delete Supplier ' . $supplier->name, $supplier->id, 'Supplies');
            $supplier->delete();
            return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully.');
        } catch (Exception $e) {
            Log::error('supplier deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting Supplier: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $this->authorize('supplier_trash_view');
        $suppliers = Supplier::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('supplier.trash', compact('suppliers'));
    }

    public function restore($id)
    {
        try {
            $supplier = Supplier::withTrashed()->findOrFail($id);
            $supplier->restore();
            return redirect()->route('supplier.index')->with('success', 'Supplier restored successfully.');
        } catch (Exception $e) {
            Log::error('supplier restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring Supplier: ' . $e->getMessage());
        }
    }

    public function assignItem($id)
    {
        $this->authorize('project_assign_item');
        $supplier = Supplier::with('items')->findOrFail($id);
        $assignedItem = DB::table('item_supplier')
        ->where('supplier_id', $supplier->id)->pluck('item_id')->toArray();
        $allItems = Item::all();

        return view('supplier.assign_item', compact('supplier', 'allItems', 'assignedItem'));
    }

    public function storeAssignedItems(Request $request, $id)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:items,id',
            'purchase_price' => 'required|array',
            'remarks' => 'nullable|array'
        ]);

        $supplier = Supplier::findOrFail($id);
        $syncData = [];

        foreach ($request->item_ids as $itemId) {
            $syncData[$itemId] = [
                'purchase_price' => $request->purchase_price[$itemId] ?? 0,
                'remarks' => $request->remarks[$itemId] ?? null,
                'date' => now()
            ];
        }

        $supplier->items()->syncWithoutDetaching($syncData);
        foreach ($request->item_ids as $itemId) {
            $item = Item::find($itemId);
            logUserActivity('Supplier', 'Assigned item "' . $item->item . '" to supplier', $item->id, 'Item');
        }

        return redirect()->back()->with('success', 'Items assigned successfully.');
    }

    public function unassignItems(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
        ]);
        $itemIds = collect($request->items)->pluck('id')->toArray();

        $supplier->items()->detach($itemIds);

        foreach ($itemIds as $itemId) {
            $item = Item::find($itemId);
            if ($item) {
                logUserActivity('Supplier', 'Unassigned item "' . $item->item . '" from Supplier', $item->id, 'Item');
            }
        }

        return response()->json(['success' => true]);
    }
    public function showSupplierRatesForm($itemId)
    {
        $item = Item::findOrFail($itemId);
        $suppliers = Supplier::all();

        return view('supplier.supplier_rate',compact('item', 'suppliers'));
    }
    public function storeItemSupplierRates(Request $request, $itemId)
    {
        // dd($request->all());
        $item = Item::findOrFail($itemId);

        foreach ($request->suppliers as $supplierId => $data) {
            if (isset($data['selected'])) {
                $item->suppliers()->attach($supplierId, [
                    'purchase_price' => $data['purchase_price'] ?? null,
                    'date' => $data['date'] ?? null,
                    'remarks' => $data['remarks'] ?? null,
                ]);
            }
        }

        return redirect()->route('item.index')->with('success', 'Supplier rates assigned successfully.');
    }

}
