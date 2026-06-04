<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\HouseType;
use App\Models\ItemDemand;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{

    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('stock_view');
        $user = Auth::User();
        $trashstock = Stock::onlyTrashed()->count();
        $stocks = Stock::with('creator')->where('type', 'in')->latest()->get();
        $warehouses = Warehouse::all();
        $items = Item::all();
        $houseTypes = HouseType::all();
        $projects = Project::all();
        return view('stock.stockIn', compact('stocks', 'user', 'warehouses',
        'projects', 'items', 'trashstock','houseTypes'));
    }
    public function getItems($houseType)
    {
        $items = Item::where('items_type', $houseType)->get();
        // dd($items);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'date'       => 'required|date',
            'quantity'   => 'required',
            'price'           => 'nullable',
            'demand_number' => 'nullable|string',
            'purchase_order_number' => 'nullable|string',
            'challan_number' => 'nullable|string',
            'warehouse_id'    => 'required',
            'project_id'    => 'required',
            'house_type_id' => 'required|string', //  new
            'comment' => 'nullable|string',
        ]);
        try {
            DB::beginTransaction();

            $stock = Stock::create([
                'warehouse_id' => $request->warehouse_id,
                'date' => $request->date,
                'type' => 'in',
                'quantity' => $request->quantity,
                'price' => $request->price,
                'item_id' => $request->item_id,
                'project_id' => $request->project_id,
                'house_type_id' => $request->house_type_id,
                'demand_number' => $request->demand_number,
                'purchase_order_number' => $request->purchase_order_number,
                'challan_number' => $request->challan_number,
                'comment' => $request->comment,
                'created_by' => Auth::id(),
            ]);
            logUserActivity('Stock', 'Create stock in ' . $stock->quantity . ' for ' . $stock->item->name, $stock->id, 'Stock');

            $item = Item::find($request->item_id);
            if ($item) {
                // dd($item);
                $item->available_qty += $request->quantity;
                $item->qty += $request->quantity;
                $item->save();
            }

            DB::commit();

            return redirect()->route('stock.index')->with('success', 'Stock In entry added successfully.');
        } catch (Exception $e) {
            Log::error('stock creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error creating stock: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'item_id' => 'required',
        'date'       => 'required|date',
        'quantity'   => 'required|numeric|min:0',
        'price'           => 'nullable|numeric|min:0',
        'demand_number' => 'nullable|string',
        'purchase_order_number' => 'nullable|string',
        'challan_number' => 'nullable|string',
        'warehouse_id'    => 'required',
        'house_type_id' => 'required|string',
        'project_id' => 'required',
        'comment' => 'nullable|string',
    ]);

    try {
        DB::beginTransaction();

        $stock = Stock::findOrFail($id);

        // 🔹 Pehle purani item quantity adjust karo
        $oldItem = Item::find($stock->item_id);
        if ($oldItem) {
            $oldItem->available_qty -= $stock->quantity;
            $oldItem->qty -= $stock->quantity;
            $oldItem->save();
        }

        // 🔹 Update stock record
        $stock->update([
            'warehouse_id' => $request->warehouse_id,
            'date' => $request->date,
            'type' => 'in',
            'quantity' => $request->quantity,
            'price' => $request->price,
            'item_id' => $request->item_id,
            'project_id' => $request->project_id,
            'house_type_id' => $request->house_type_id,
            'demand_number' => $request->demand_number,
            'purchase_order_number' => $request->purchase_order_number,
            'challan_number' => $request->challan_number,
            'comment' => $request->comment,
        ]);

        // 🔹 Ab nayi item quantity add karo
        $newItem = Item::find($request->item_id);
        if ($newItem) {
            $newItem->available_qty += $request->quantity;
            $newItem->qty += $request->quantity;
            $newItem->save();
        }

        logUserActivity('Stock', 'Update stock in ' . $stock->quantity . ' for ' . $stock->item->name, $stock->id, 'Stock');

        DB::commit();

        return redirect()->route('stock.index')->with('success', 'Stock In entry updated successfully.');
    } catch (Exception $e) {
        DB::rollBack();
        Log::error('stock update failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error updating stock: ' . $e->getMessage());
    }
}

// ✅ Delete (soft delete)
public function destroy($id)
{
    try {
        DB::beginTransaction();

        $stock = Stock::findOrFail($id);

        // Item quantity ko wapas adjust karo (delete ka matlab hai wo stock hata dena)
        $item = Item::find($stock->item_id);
        if ($item) {
            $item->available_qty -= $stock->quantity;
            $item->qty -= $stock->quantity;
            $item->save();
        }

        $stock->delete();

        logUserActivity('Stock', 'Deleted stock entry of ' . $stock->quantity . ' for ' . $stock->item->name, $stock->id, 'Stock');

        DB::commit();

        return redirect()->route('stock.index')->with('success', 'Stock entry deleted successfully.');
    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Stock delete failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error deleting stock: ' . $e->getMessage());
    }
}
public function trash()
{
    $this->authorize('stock_view');
    $stocks = Stock::onlyTrashed()->with('creator', 'item')->latest()->get();
    return view('stock.trash', compact('stocks'));
}
public function restore($id)
{
    try {
        DB::beginTransaction();

        $stock = Stock::onlyTrashed()->findOrFail($id);
        $stock->restore();

        // Restore karte waqt item quantity ko wapis add karo
        $item = Item::find($stock->item_id);
        if ($item) {
            $item->available_qty += $stock->quantity;
            $item->qty += $stock->quantity;
            $item->save();
        }

        logUserActivity('Stock', 'Restored stock entry of ' . $stock->quantity . ' for ' . $stock->item->name, $stock->id, 'Stock');

        DB::commit();

        return redirect()->route('stock.trash')->with('success', 'Stock entry restored successfully.');
    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Stock restore failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error restoring stock: ' . $e->getMessage());
    }
}
    //stockin Soft Delete, Trash, Restore Methods (Old)
    // public function destroy($id)
    // {
    //     $stock = Stock::findOrFail($id)->delete();
    //     logUserActivity('Stock', 'Delete stock in ' . $stock->item->name, $stock->id, 'Stock');
    //     return redirect()->route('stock.index')->with('success', 'Stock deleted!');
    // }
    // public function trash()
    // {
    //     $this->authorize('stock_trash_view');
    //     $stocks = Stock::onlyTrashed()->orderBy('created_at', 'desc')->get();
    //     return view('stock.trash', compact('stocks'));
    // }

    // public function restore($id)
    // {
    //     try {
    //         $stock = Stock::withTrashed()->findOrFail($id);
    //         $stock->restore();
    //         return redirect()->route('stock.index')->with('success', 'stock restored successfully.');
    //     } catch (Exception $e) {
    //         Log::error('stock restoration failed: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Error restoring stock: ' . $e->getMessage());
    //     }
    // }



    // Stock Out Function

    public function stockOut()
    {
        $this->authorize('stockOut_view');
        $user = Auth::User();
        $trashstockout = Stock::onlyTrashed()->count();
        $stocks = Stock::with('creator')->where('type', 'out')->latest()->get();
        $warehouses = Warehouse::all();
        $items = Item::all();
        $contractors = Contractor::all();
        return view('stock.stockOut', compact(
            'stocks',
            'user',
            'warehouses',
            'contractors',
            'items',
            'trashstockout'
        ));
    }
    public function storeStockOut(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required',
            'contractor_id' => 'required',
            'date' => 'required|date',
            'house_type_id' => 'required|string', //  new
            'demand_number' => 'nullable|string',
            'purchase_order_number' => 'nullable|string',
            'challan_number' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'item_id'  => 'required',
            'comment' => 'nullable',
        ]);
        try {
            $stock = Stock::create([
                'warehouse_id' => $request->warehouse_id,
                'contractor_id' => $request->contractor_id,
                'house_type_id' => $request->house_type_id, //new
                'date' => $request->date,
                'type' => 'out',
                 'demand_number' => $request->demand_number,
                'purchase_order_number' => $request->purchase_order_number,
                'challan_number' => $request->challan_number,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'item_id' => $request->item_id,
                'comment' => $request->comment,
                'created_by'  => Auth::id(),

            ]);
            logUserActivity('Stock', 'Create stock out ' . $stock->quantity . ' for ' . $stock->item->name, $stock->id, 'Stock');

            return redirect()->route('stock.out.index')->with('success', 'Stock Out entry added successfully.');
        } catch (Exception $e) {
            Log::error('stock creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error creating stock: ' . $e->getMessage());
        }
    }

    public function updateStockOut(Request $request, $id)
    {
        $request->validate([
            'warehouse_id' => 'required',
            'date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'item_id'    => 'required',
        ]);
        try {
            $stock = Stock::findOrFail($id);
            logUserActivity('Stock', 'Update stock out ' . $stock->quantity . ' for ' . $stock->item->name, $stock->id, 'Stock');
            $stock->update([
                'warehouse_id' => $request->warehouse_id,
                'date' => $request->date,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'item_id' => $request->item_id,
            ]);

            return redirect()->route('stock.out.index')->with('success', 'Stock Out entry added successfully.');
        } catch (Exception $e) {
            Log::error('stock creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error creating stock: ' . $e->getMessage());
        }
    }
    public function destroyStockOut($id)
    {
        $stock = Stock::findOrFail($id);
        logUserActivity('Stock', 'Delete stock out ' . $stock->quantity . ' for ' . $stock->item->name, $stock->id, 'Stock');
        $stock->delete();

        return redirect()->route('stock.out.index')->with('success', 'Stock Out deleted!');
    }
    public function trashStockOut()
    {
        // $this->authorize('stock_trash_view');
        $stocks = Stock::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('stock.trashStockOut', compact('stocks'));
    }

    public function restoreStockOut($id)
    {
        try {
            $stock = Stock::withTrashed()->findOrFail($id);
            $stock->restore();
            return redirect()->route('stock.out.index')->with('success', 'stock out restored successfully.');
        } catch (Exception $e) {
            Log::error('stock restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring stock: ' . $e->getMessage());
        }
    }
    public function stockCheck(Request $request)
    {
        $this->authorize('stockCheck_view');

        $warehouseId = $request->warehouse_id;
        $type = $request->type;
        $projectId = $request->project_id;

        // $itemsQuery = Item::query()->with([
        //         'stocks.project' => function ($q) use ($warehouseId) {
        //             if ($warehouseId) {$q->where('warehouse_id', $warehouseId);}
        //         },'projects'
        //     ]);
        $itemsQuery = Item::query()->with(['stocks.project', 'projects']);
        if ($type) {
            $itemsQuery->where('items_type', $type);
        }

        $items = $itemsQuery->get();

        $show = collect();

        foreach ($items as $item) {
            // $filteredStocks = $projectId
            //     ? $item->stocks->where('project_id', $projectId) : $item->stocks;
             $filteredStocks = $item->stocks;

                if ($warehouseId) {
                    $filteredStocks = $filteredStocks->where('warehouse_id', $warehouseId);
                }

                if ($projectId) {
                    $filteredStocks = $filteredStocks->where('project_id', $projectId);
                }
            if ($filteredStocks->isEmpty()) {
                $show->push([
                    'item_name'     => $item->item,
                    'item_type'     => $item->items_type ?? 'N/A',
                    'project_name'  => 'N/A',
                    'stock_in'      => 0,
                    'stock_out'     => 0,
                    'stock_issued'  => 0,
                    'total_stock'   => 0,
                    'total_houses'  => $item->projects->sum('pivot.quantity') ?? 0,
                    'total_remaining' => 0
                ]);
                continue;
            }

            $groupedStocks = $filteredStocks->groupBy('project_id');

            foreach ($groupedStocks as $pId => $stocks) {
                $stockIn     = $stocks->where('type', 'in')->sum('quantity');
                $stockOut    = $stocks->where('type', 'out')->sum('quantity');
                $stockReturn = $stocks->where('type', 'return')->sum('quantity');
                $stockIssued = $stocks->where('type', 'issued')->sum('issued_qty');
                $totalStock  = ($stockIn - $stockReturn) - ($stockOut);
                $projectName = $stocks->first()->project->project_name ?? 'N/A';
                $totalHouses = $item->projects()->where('project_id', $pId)->sum('item_project_pivot.quantity');
                $totalRemaining = $totalHouses - $stockOut;
                $show->push([
                    'item_name'     => $item->item,
                    'item_type'     => $item->items_type ?? 'N/A',
                    'project_name'  => $projectName,
                    'stock_in'      => $stockIn,
                    'stock_out'     => $stockOut,
                    'stock_issued'  => $stockIssued,
                    'total_stock'   => $totalStock,
                    'total_houses'  => $totalHouses,
                    'total_remaining'  => $totalRemaining,
                ]);
            }
        }

        $warehouses = Warehouse::all();
        $projects = Project::all();
        $types = Item::select('items_type')->distinct()->pluck('items_type');

        return view('stock.show', compact('show', 'warehouses', 'projects', 'types'));
    }


}
