<?php

namespace App\Http\Controllers;

use App\Imports\itemsBImport;
use App\Imports\ItemsImport;
use App\Models\Brand;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Warehouse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('item_view');
        $trashitem = Item::onlyTrashed()->count();
         $items = Item::where('items_type', 'ATH' )->where('status', 'active')->get();
        return view('item.index', compact('items', 'trashitem'));
    }

    public function create()
    {
        $this->authorize('item_create');
        $warehouses = Warehouse::all();
        $suppliers = Supplier::all();
        $brands = Brand::all();
        return view('item.create', compact('warehouses', 'suppliers', 'brands'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'item' => 'required|string|max:255',
            'size' => 'nullable',
            'deno' => 'nullable',
            'per_house_qty' => 'nullable',
            'items_type' => 'required|string',
            'specification' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $item = new Item();
            logUserActivity('Item', 'Create Item ' . $item->item, $item->id, 'Item');
            $item->item = $request->item;
            $item->size = $request->size;
            $item->deno = $request->deno;
            $item->per_house_qty = $request->per_house_qty;
            $item->items_type = $request->items_type;
            $item->specification = $request->specification;
            $item->status = $request->status;
            $item->save();
            // dd($item);
            return redirect()->route('item.index')->with('success', 'Item created successfully.');
        } catch (Exception $e) {
            Log::error('item creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error creating Item: ' . $e->getMessage());
        }
    }
    // public function edit($id)
    // {
    //     $this->authorize('item_edit');
    //     $item = Item::findOrFail($id);
    //     return view('item.edit', compact('item'));
    // }
public function edit($id)
{
    $this->authorize('item_edit');
    $item = Item::findOrFail($id);
    $warehouses = Warehouse::all();
    $suppliers = Supplier::all();
    $brands = Brand::all();

    // return $item->items_type;
    return view('item.edit', compact('item', 'warehouses', 'suppliers', 'brands'));
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'item' => 'required|string|max:255',
        'size' => 'nullable',
        'deno' => 'nullable',
        'per_house_qty' => 'nullable',
        'items_type' => 'required', // could be array or string
        'specification' => 'nullable',
        'status' => 'required|in:active,inactive',
    ]);

    try {
        $item = Item::findOrFail($id);

        // If items_type is an array, implode it without spaces
        $itemsType = $request->items_type.'TH';
        // Fill other fields
        $item->fill([
            'item' => $request->item,
            'size' => $request->size,
            'deno' => $request->deno,
            'items_type' => $itemsType,
            'per_house_qty' => $request->per_house_qty,
            'specification' => $request->specification,
            'status' => $request->status,
        ]);

        $item->save();

        logUserActivity('Item', 'Update Item ' . $item->item, $item->id, 'Item');


        return redirect()->route(
            'item.index' . ($validated['items_type'] == 'A' ? '' : '.' . strtolower($validated['items_type']))
        )->with('success', 'Item updated successfully.');
    } catch (Exception $e) {
        Log::error('Item update failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error updating Item: ' . $e->getMessage());
    }
}


// public function update(Request $request, $id)
// {
//     $request->validate([
//         'item' => 'required|string|max:255',
//         'size' => 'nullable',
//         'deno' => 'nullable',
//         'items_type' => 'required|string|in:A,B,C,D',
//         'per_house_qty' => 'nullable',
//         'specification' => 'nullable',
//         'status' => 'required|in:active,inactive',
//     ]);

//     try {
//         $item = Item::findOrFail($id);
//         $item->item = $request->item;
//         $item->size = $request->size;
//         $item->deno = $request->deno;
//         $item->items_type = $request->items_type;
//         $item->per_house_qty = $request->per_house_qty;
//         $item->specification = $request->specification;
//         $item->status = $request->status;
//         $item->save();

//         logUserActivity('Item', 'Update Item ' . $item->item, $item->id, 'Item');

//         return 'Sivessss';
//         // return redirect()->route('item.index')->with('success', 'Item updated successfully.');
//     } catch (Exception $e) {
//         Log::error('item update failed: ' . $e->getMessage());
//         return redirect()->back()->with('error', 'Error updating Item: ' . $e->getMessage());
//     }
// }

// public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'item' => 'required|string|max:255',
    //         'size' => 'nullable',
    //         'deno' => 'nullable',
    //         'items_type' => 'required|string|in:A',
    //         'per_house_qty' => 'nullable',
    //         'specification' => 'nullable',
    //         'status' => 'required|in:active,inactive',
    //     ]);
    //     try {
    //         $item = Item::findOrFail($id);
    //         logUserActivity('Item', 'Update Item ' . $item->item, $item->id, 'Item');
    //         $item->item = $request->item;
    //         $item->size = $request->size;
    //         $item->deno = $request->deno;
    //         $item->items_type = $request->items_type; // 👈 yahan save hoga
    //         $item->per_house_qty = $request->per_house_qty;
    //         $item->specification = $request->specification;
    //         $item->status = $request->status;
    //         $item->save();

    //         return redirect()->route('item.index')->with('success', 'Item updated successfully.');
    //     } catch (Exception $e) {
    //         Log::error('item update failed: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Error updating Item: ' . $e->getMessage());
    //     }
    // }

    public function destroy($id)
    {
        try {
            $item = Item::findOrFail($id);
            logUserActivity('Item', 'Delete Item ' . $item->item, $item->id, 'Item');
            $item->delete();
            return redirect()->route('item.index')->with('success', 'Item deleted successfully.');
        } catch (Exception $e) {
            Log::error('item deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting Item: ' . $e->getMessage());
        }

    }

    public function trash()
    {
        // $this->authorize('item_trash_view');
        $items = Item::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('item.trash', compact('items'));
    }

    public function restore($id)
    {
        try {
            $item = Item::withTrashed()->findOrFail($id);
            $item->restore();
            return redirect()->route('item.index')->with('success', 'item restored successfully.');
        } catch (Exception $e) {
            Log::error('item restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring Item: ' . $e->getMessage());
        }
    }
    //  public function importC(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls,csv',
    //     ]);
    //      $type = 'C';
    //         Excel::import(new ItemsBImport($type), $request->file('file'));

    //     return back()->with('success', 'Items imported successfully!');
    // }
     public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        $type = 'ATH';
            Excel::import(new ItemsImport ($type), $request->file('file'));

        return back()->with('success', 'Items imported successfully!');
    }
    //=========== Type B===========
    public function indexB()
    {
        $this->authorize('item_b_view');
        $trashitem = Item::onlyTrashed()->count();
        $items = Item::where('items_type', 'BTH' )->where('status', 'active')->get();
        return view('item.itemB.index', compact('items', 'trashitem'));
    }




    public function destroyB($id)
    {
        try {
            $item = Item::findOrFail($id);
            logUserActivity('Item', 'Delete Item ' . $item->item, $item->id, 'Item');
            $item->delete();
            return redirect()->route('item.index')->with('success', 'Item deleted successfully.');
        } catch (Exception $e) {
            Log::error('item deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting Item: ' . $e->getMessage());
        }
    }

    public function trashB()
    {
        // $this->authorize('item_trash_view');
        $items = Item::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('item.itemB.trash', compact('items'));
    }

    public function restoreB($id)
    {
        try {
            $item = Item::withTrashed()->findOrFail($id);
            $item->restore();
            return redirect()->route('item.index')->with('success', 'item restored successfully.');
        } catch (Exception $e) {
            Log::error('item restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring Item: ' . $e->getMessage());
        }
    }
    public function importB(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
         $type = 'BTH';
            Excel::import(new ItemsImport ($type), $request->file('file'));

        return back()->with('success', 'Items imported successfully!');
    }

//=========== Type C===========
    public function indexC()
    {
        $this->authorize('item_c_view');
        $trashitem = Item::onlyTrashed()->count();
        $items = Item::where('items_type', 'CTH' )->where('status', 'active')->get();
        return view('item.itemC.index', compact('items', 'trashitem'));
    }



    // public function storeC(Request $request)
    // {
    //     // dd($request->all());
    //     $request->validate([
    //         'item' => 'required|string|max:255',
    //         'size' => 'nullable',
    //         'deno' => 'nullable',
    //         'items_type' => 'required|string|in:C',
    //         'per_house_qty' => 'nullable',
    //         'specification' => 'nullable',
    //         'status' => 'required|in:active,inactive',
    //     ]);

    //     try {
    //         $item = new Item();
    //         logUserActivity('Item', 'Create Item ' . $item->item, $item->id, 'Item');
    //         $item->item = $request->item;
    //         $item->size = $request->size;
    //         $item->deno = $request->deno;
    //         $item->items_type = $request->items_type; // 👈 yahan save hoga
    //         $item->per_house_qty = $request->per_house_qty;
    //         $item->specification = $request->specification;
    //         $item->status = $request->status;
    //         $item->save();
    //         // dd($item);
    //         return redirect()->route('item.index')->with('success', 'Item created successfully.');
    //     } catch (Exception $e) {
    //         Log::error('item creation failed: ' . $e->getMessage());

    //         return redirect()->back()->with('error', 'Error creating Item: ' . $e->getMessage());
    //     }
    // }
    // public function editC($id)
    // {
    //     $this->authorize('item_edit');
    //     $item = Item::findOrFail($id);
    //     return view('item.itemC.edit', compact('item'));
    // }

    // public function updateC(Request $request, $id)
    // {
    //     $request->validate([
    //         'item' => 'required|string|max:255',
    //         'size' => 'nullable',
    //         'deno' => 'nullable',
    //         'items_type' => 'required|string|in:C',
    //         'per_house_qty' => 'nullable',
    //         'specification' => 'nullable',
    //         'status' => 'required|in:active,inactive',
    //     ]);
    //     try {
    //         $item = Item::findOrFail($id);
    //         logUserActivity('Item', 'Update Item ' . $item->item, $item->id, 'Item');
    //         $item->item = $request->item;
    //         $item->size = $request->size;
    //         $item->deno = $request->deno;
    //         $item->items_type = $request->items_type; // 👈 yahan save hoga
    //         $item->per_house_qty = $request->per_house_qty;
    //         $item->specification = $request->specification;
    //         $item->status = $request->status;
    //         $item->save();

    //         return redirect()->route('item.index')->with('success', 'Item updated successfully.');
    //     } catch (Exception $e) {
    //         Log::error('item update failed: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Error updating Item: ' . $e->getMessage());
    //     }
    // }

    public function destroyC($id)
    {
        try {
            $item = Item::findOrFail($id);
            logUserActivity('Item', 'Delete Item ' . $item->item, $item->id, 'Item');
            $item->delete();
            return redirect()->route('item.index')->with('success', 'Item deleted successfully.');
        } catch (Exception $e) {
            Log::error('item deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting Item: ' . $e->getMessage());
        }
    }

    public function trashC()
    {
        $this->authorize('item_c_trash_view');
        $items = Item::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('item.itemC.trash', compact('items'));
    }

    public function restoreC($id)
    {
        try {
            $item = Item::withTrashed()->findOrFail($id);
            $item->restore();
            return redirect()->route('item.index')->with('success', 'item restored successfully.');
        } catch (Exception $e) {
            Log::error('item restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring Item: ' . $e->getMessage());
        }
    }

public function importC(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
            $type = 'CTH';
            Excel::import(new ItemsImport ($type), $request->file('file'));

        return back()->with('success', 'Items imported successfully!');
    }
//=========== Type D===========
    public function indexD()
    {
        $this->authorize('item_d_view');
        $trashitem = Item::onlyTrashed()->count();
        $items = Item::where('items_type', 'DTH' )->where('status', 'active')->get();
        return view('item.itemD.index', compact('items', 'trashitem'));
    }




    // public function editD($id)
    // {
    //     $this->authorize('item_edit');
    //     $item = Item::findOrFail($id);
    //     return view('item.itemD.edit', compact('item'));
    // }

    // public function updateD(Request $request, $id)
    // {
    //     $request->validate([
    //         'item' => 'required|string|max:255',
    //         'size' => 'nullable',
    //         'deno' => 'nullable',
    //         'per_house_qty' => 'nullable',
    //         'specification' => 'nullable',
    //         'status' => 'required|in:active,inactive',
    //     ]);
    //     try {
    //         $item = Item::findOrFail($id);
    //         logUserActivity('Item', 'Update Item ' . $item->item, $item->id, 'Item');
    //         $item->item = $request->item;
    //         $item->size = $request->size;
    //         $item->deno = $request->deno;
    //         $item->per_house_qty = $request->per_house_qty;
    //         $item->specification = $request->specification;
    //         $item->status = $request->status;
    //         $item->save();

    //         return redirect()->route('item.index')->with('success', 'Item updated successfully.');
    //     } catch (Exception $e) {
    //         Log::error('item update failed: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Error updating Item: ' . $e->getMessage());
    //     }
    // }

    public function destroyD($id)
    {
        try {
            $item = Item::findOrFail($id);
            logUserActivity('Item', 'Delete Item ' . $item->item, $item->id, 'Item');
            $item->delete();
            return redirect()->route('item.index')->with('success', 'Item deleted successfully.');
        } catch (Exception $e) {
            Log::error('item deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting Item: ' . $e->getMessage());
        }
    }

    public function trashD()
    {
        $this->authorize('item_d_trash_view');
        $items = Item::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('item.itemD.trash', compact('items'));
    }

    public function restoreD($id)
    {
        try {
            $item = Item::withTrashed()->findOrFail($id);
            $item->restore();
            return redirect()->route('item.index')->with('success', 'item restored successfully.');
        } catch (Exception $e) {
            Log::error('item restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring Item: ' . $e->getMessage());
        }
    }
    public function importD(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
         $type = 'DTH';
            Excel::import(new ItemsImport($type), $request->file('file'));

        return back()->with('success', 'Items imported successfully!');
    }
}
