<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use App\Models\HouseProject;
use App\Models\Item;
use App\Models\User;
use App\Models\ItemDemand;
use App\Models\ItemProject;
use App\Models\Project;
use App\Models\Stock;
use App\Models\Notification;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemDemandController extends Controller
{

    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('itemDemand_view');
        $user = Auth::User();
        $trashItemDemand = ItemDemand::onlyTrashed()->count();
        $ItemDemands = ItemDemand::with(
        'items','contractor','project','creator','quotation',
        'purchaseOrderItems','purchaseOrder',
        'comparativeStatementItems','comparativeStatement')->latest();

    if ($user->department == 'Store-Manager') {
        $ItemDemands = $ItemDemands->where('approved_by_pm', 1);
    } elseif ($user->department == 'Manager-of-Procurement') {
        $ItemDemands = $ItemDemands->where('approved_by_pm', 1)
                                   ->where('approved_by_sm', 1);
    }

    $ItemDemands = $ItemDemands->get();
        $items = Item::with('projects')->get();
        $projects = Project::all();
        $contractors = Contractor::all();
        $itemProjects = ItemProject::all();
        $houseProjects = HouseProject::all();
        return view('itemDemand.itemDemand', compact(
            'ItemDemands',
            'projects',
            'user',
            'contractors',
            'items',
            'trashItemDemand',
            'itemProjects',
            'houseProjects'
        ));
    }

    public function getProjectContractors($projectId)
    {
        $project = Project::with(['contractors' => function ($q) {
            $q->wherePivot('status', 'active'); // sirf active wale hi laane ke liye
        }])->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project->contractors);
    }


    public function getProjectHouses($id)
    {
        $houses = HouseProject::with('contractors')->where('project_id', $id)->get();
        return response()->json($houses);
    }
    public function getHouseContractors($houseProjectId)
    {
        $contractors = Contractor::whereHas('houseProjects', function ($query) use ($houseProjectId) {
            $query->where('house_projects.id', $houseProjectId)->where('status', 'active');
        })->get();
        return response()->json($contractors);
    }

    public function getProjectItems($projectId)
    {

        $items = Item::whereHas('itemProjects', function ($q) use ($projectId) {
            $q->where('project_id', $projectId);
        })->with(['itemProjects' => function ($q) use ($projectId) {
            $q->where('project_id', $projectId);
        }])->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->item,
                'size' => $item->size,
                'allocated_qty' => $item->itemProjects->first()->quantity ?? 0,
            ];
        });
        // dd($items);
        return response()->json($items);
    }

    // house items
    public function getItemsByHouse($houseId)
    {
        $houseProject = HouseProject::with('items.item')->find($houseId);

        if (!$houseProject) {
            return response()->json(['message' => 'House not found'], 404);
        }

        $items = $houseProject->items->filter(function ($ip) {
            return $ip->item !== null;
        })->map(function ($itemProject) {
            return [
                'id' => $itemProject->item->id,
                'name' => $itemProject->item->item,
                'size' => $itemProject->item->size,
                'allocated_qty' => $itemProject->quantity,
            ];
        });

        return response()->json($items);
    }

    // public function getItems($projectId, $contractorId)
    // {
    //     $items = DB::table('item_project_pivot')
    //         ->join('items', 'item_project_pivot.item_id', '=', 'items.id')
    //         ->where('item_project_pivot.project_id', $projectId)
    //         ->where('item_project_pivot.contractor_id', $contractorId)
    //         ->select(
    //             'items.id',
    //             'items.item as item_name',
    //             'items.size',
    //             'item_project_pivot.quantity as allocated_qty'
    //         )
    //         ->distinct()
    //         ->get();

    //     return response()->json($items);
    // }
    // In your controller (e.g. ItemDemandController)
    public function getItems($projectId, $contractorId)
    {
        // Pull project+contractor specific items and allocated quantities
        $items = DB::table('item_project_pivot')
            ->join('items', 'item_project_pivot.item_id', '=', 'items.id')
            ->where('item_project_pivot.project_id', $projectId)
            ->where('item_project_pivot.contractor_id', $contractorId)
            ->select(
                'items.id',
                'items.item as item_name',
                'items.size',
                'item_project_pivot.quantity as allocated_qty'
            )
            ->distinct()
            ->get();

        // For each item compute issued (DB) and remaining
        $items = $items->map(function ($item) use ($contractorId) {
            $issued = DB::table('item_demand_item')
                ->where('item_id', $item->id)
                ->where('contractor_id', $contractorId)
                ->whereNull('deleted_at')
                ->sum('current_issued'); // sum of already issued amounts (previous demands)

            $remaining = (float) ($item->allocated_qty ?? 0) - (float) $issued;
            if ($remaining < 0) $remaining = 0;

            return [
                'id' => $item->id,
                'item_name' => $item->item_name,
                'size' => $item->size,
                'allocated_qty' => (float) $item->allocated_qty,
                'issued_qty' => (float) $issued,
                'remaining_qty' => (float) $remaining,
            ];
        });

        return response()->json($items);
    }


    // public function view($id)
    // {
    //     $itemDemand = ItemDemand::with(['items', 'project', 'creator'])->findOrFail($id);
    //     $contractorIds = $itemDemand->items->pluck('pivot.contractor_id')->unique()->filter();
    //     $contractors = Contractor::whereIn('id', $contractorIds)->get();
    //     return view('itemDemand.view', compact('itemDemand', 'contractors'));
    // }
    public function view($id)
    {
        $itemDemand = ItemDemand::with(['items', 'project', 'creator'])->findOrFail($id);

        $contractorIds = $itemDemand->items->pluck('pivot.contractor_id')->unique()->filter();
        $contractors = Contractor::whereIn('id', $contractorIds)->get();
        foreach ($itemDemand->items as $item) {
            $contractorId = $item->pivot->contractor_id;

            $previousIssuedTotal = DB::table('item_demand_item')
                ->where('item_id', $item->id)->where('contractor_id', $contractorId)
                ->where('item_demand_id', '!=', $id)
                ->sum('previous_issued');
            // dd($previousIssuedTotal);
            $item->calculated_previous_issued = $previousIssuedTotal;
            $item->calculated_progressive_total = $previousIssuedTotal + ($item->pivot->current_issued ?? 0);
            $item->calculated_balance_qty = ($item->pivot->allocated_qty ?? 0) - $item->calculated_progressive_total;
        }

        return view('itemDemand.view', compact('itemDemand', 'contractors'));
    }
    // public function issue($id)
    // {
    //     $itemDemand = ItemDemand::with(['items', 'project', 'creator'])->findOrFail($id);
    //     $contractorIds = $itemDemand->items->pluck('pivot.contractor_id')->unique()->filter();
    //     $contractors = Contractor::whereIn('id', $contractorIds)->get();
    //     return view('itemDemand.issue', compact('itemDemand', 'contractors'));
    // }
    public function issue($id)
    {
        $itemDemand = ItemDemand::with(['items', 'project', 'creator'])->findOrFail($id);

        $contractorIds = $itemDemand->items->pluck('pivot.contractor_id')->unique()->filter();
        $contractors = Contractor::whereIn('id', $contractorIds)->get();
        foreach ($itemDemand->items as $item) {
            $contractorId = $item->pivot->contractor_id;

            $previousIssuedTotal = DB::table('item_demand_item')
                ->where('item_id', $item->id)->where('contractor_id', $contractorId)
                ->where('item_demand_id', '!=', $id)
                ->sum('previous_issued');
            // dd($previousIssuedTotal);
            $item->calculated_previous_issued = $previousIssuedTotal;
            $item->calculated_progressive_total = $previousIssuedTotal + ($item->pivot->current_issued ?? 0);
            $item->calculated_balance_qty = ($item->pivot->allocated_qty ?? 0) - $item->calculated_progressive_total;
        }

        return view('itemDemand.issue', compact('itemDemand', 'contractors'));
    }


    public function contractorViewDemand($contractorId)
    {
        $contractor = Contractor::findOrFail($contractorId);

        $itemDemands = ItemDemand::whereHas('items', function ($query) use ($contractorId) {
            $query->where('item_demand_item.contractor_id', $contractorId);
        })
        ->with([
            'items' => function ($query) use ($contractorId) {
                $query->where('item_demand_item.contractor_id', $contractorId);
            },
            'project','creator'
        ])->get();
        foreach ($itemDemands as $itemDemand) {
            foreach ($itemDemand->items as $item) {
                $contractor_id = $item->pivot->contractor_id ?? $contractorId;
                $previousIssuedTotal = DB::table('item_demand_item')
                    ->where('item_id', $item->id)->where('contractor_id', $contractor_id)
                    ->where('item_demand_id', '!=', $itemDemand->id)->sum('previous_issued');

                $item->calculated_previous_issued = $previousIssuedTotal;
                $item->calculated_progressive_total = $previousIssuedTotal + ($item->pivot->current_issued ?? 0);
                $item->calculated_balance_qty = ($item->pivot->allocated_qty ?? 0) - $item->calculated_progressive_total;
            }
        }
        return view('contractor.demands', compact('contractor', 'itemDemands'));
    }

public function contractorDemands($contractorCode)
{
    $contractor = DB::table('contractors')->where('code', $contractorCode)->first();

    if (!$contractor) {
        return response()->json([
            'status' => 'error',
            'message' => 'Contractor not found.'
        ]);
    }

    $projects = DB::table('contractor_project')
        ->leftJoin('projects', 'contractor_project.project_id', '=', 'projects.id')
        ->select('projects.id', 'projects.project_name')
        ->where('contractor_project.contractor_id', $contractor->id)
        ->distinct()
        ->get();

    return response()->json([
        'status' => 'success',
        'contractors' => [
            ['id' => $contractor->id, 'name' => $contractor->name]
        ],
        'projects' => $projects
    ]);
}
// public function getProjectDetails(Request $request)
// {
//     $contractorId = $request->contractor_id;
//     $projectId = $request->project_id;

//     // Example: fetch project details joined with contractor_project
//     $project = DB::table('contractor_project')
//         ->join('projects', 'contractor_project.project_id', '=', 'projects.id')
//         ->where('contractor_project.contractor_id', $contractorId)
//         ->where('contractor_project.project_id', $projectId)
//         ->select('projects.id', 'projects.project_name', 'contractor_project.contract_number', 'contractor_project.status')
//         ->first();

//     if ($project) {
//         return response()->json([
//             'status' => 'success',
//             'data' => $project
//         ]);
//     } else {
//         return response()->json([
//             'status' => 'error',
//             'message' => 'No matching project found.'
//         ]);
//     }
// }

// public function getProjectDetails(Request $request)
// {
//     $contractorId = $request->contractor_id;
//     $projectId = $request->project_id;

//     // Get project details
//     $project = DB::table('contractor_project')
//         ->join('projects', 'contractor_project.project_id', '=', 'projects.id')
//         ->where('contractor_project.contractor_id', $contractorId)
//         ->where('contractor_project.project_id', $projectId)
//         ->select(
//             'projects.id',
//             'projects.project_name',
//             'contractor_project.contract_number',
//             'contractor_project.status'
//         )
//         ->first();

//     // Get house types for this project
//     $houseTypes = DB::table('house_projects')
//         ->where('project_id', $projectId)
//         ->select('id', 'house_type_id', 'site_square_yard', 'description')
//         ->get();

//     if ($project) {
//         return response()->json([
//             'status' => 'success',
//             'project' => $project,
//             'house_types' => $houseTypes
//         ]);
//     } else {
//         return response()->json([
//             'status' => 'error',
//             'message' => 'No matching project found.'
//         ]);
//     }
// }

public function getProjectDetails(Request $request)
{
    $contractorId = $request->contractor_id;
    $projectId = $request->project_id;

    // Get project details for the selected contractor
    $project = DB::table('contractor_project')
        ->join('projects', 'contractor_project.project_id', '=', 'projects.id')
        ->where('contractor_project.contractor_id', $contractorId)
        ->where('contractor_project.project_id', $projectId)
        ->select(
            'projects.id',
            'projects.project_name',
            'contractor_project.contract_number',
            'contractor_project.status'
        )
        ->first();

    // Get house types for this project filtered by selected contractor
    $houseTypes = DB::table('house_projects')
        ->where('house_projects.project_id', $projectId)
        ->whereExists(function ($query) use ($contractorId, $projectId) {
            $query->select(DB::raw(1))
                  ->from('contractor_project')
                  ->whereColumn('contractor_project.project_id', 'house_projects.project_id')
                  ->where('contractor_project.contractor_id', $contractorId);
        })
        ->select(
            'house_projects.id',
            // 'house_projects.house_type_id',
            'house_projects.project_id',
            'house_projects.site_square_yard',
            'house_projects.description'
        )
        ->get();

    if ($project) {
        return response()->json([
            'status' => 'success',
            'project' => $project,
            'house_types' => $houseTypes
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'No matching project found.'
        ]);
    }
}




    public function issued(Request $request, $id)
    {

        DB::beginTransaction();

        try {
            $itemDemand = ItemDemand::with(['items', 'project', 'contractors'])->findOrFail($id);

            // Fetch related IDs
            $projectId = $itemDemand->project_id ?? null;
            $contractorId = $itemDemand->contractors->first()->id ?? null;
            $houseTypeId = $itemDemand->house_type_id ?? null;

            foreach ($request->current_issued as $itemId => $qty) {
                $qty = floatval($qty);
                $comment = $request->comments[$itemId] ?? null;

                if ($qty <= 0) {
                    continue;
                }
                $pivotItem = $itemDemand->items()->where('item_id', $itemId)->first();
                if (!$pivotItem) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Item ID {$itemId} not found in this demand.");
                }

                $pivot = $pivotItem->pivot;
                if ($pivot->previous_issued > 0) {
                    continue;
                }
                $stockIn = Stock::where('item_id', $itemId)->where('type', 'in')->sum('quantity');
                $stockIssued = Stock::where('item_id', $itemId)->where('type', 'issued')->sum('issued_qty');

                $availableStock = $stockIn - $stockIssued;

                if ($availableStock <= 0) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Item ID {$itemId} has no available stock.");
                }

                if ($qty > $availableStock) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Item ID {$itemId}: Issued qty ($qty) exceeds available stock ($availableStock).");
                }
                $previousIssued = $pivot->previous_issued ?? 0;
                $progressiveTotal = $pivot->progressive_total ?? 0;
                $allocatedQty = $pivot->allocated_qty ?? 0;
                $newPreviousIssued = $previousIssued + $qty;
                $newProgressiveTotal = $progressiveTotal + $qty;
                $newBalanceQty = $allocatedQty - $newPreviousIssued;

                $itemDemand->items()->updateExistingPivot($itemId, [
                    'previous_issued'    => $newPreviousIssued,
                    'current_issued'     => $qty,
                    'progressive_total'  => $newProgressiveTotal,
                    'balance_qty'        => $newBalanceQty,
                    'comments'   => $comment,
                    'updated_at'         => now(),
                ]);

                Stock::create([
                    'item_id'       => $itemId,
                    'warehouse_id'  => $itemDemand->warehouse_id ?? 1,
                    'project_id'     => $projectId,
                    'contractor_id'  => $contractorId,
                    'house_type_id'  => $houseTypeId,
                    'date'          => now(),
                    'type'          => 'issued',
                    'issued_qty'    => $qty,
                    'demand_number' => $itemDemand->demand_no,
                    'created_by'    => Auth::id(),
                ]);
            }

            $itemDemand->update(['status' => 'issued']);

            //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'itemDemand_issue',
                    'Item Demand Issued',
                    'Item Demand Issued',
                    'Item Demand Issued for Project "' . $projectId . '" has been marked as issued by ' . auth()->user()->name,
                    route('itemDemand.index')
                );
            //END======================================================================================


            DB::commit();

            return redirect()->route('itemDemand.view', $id)->with('success', 'Demand issued successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error occurred while issuing: ' . $e->getMessage());
        }
    }

    // public function issued(Request $request, $id)
    // {
    //     $itemDemand = ItemDemand::with('items')->findOrFail($id);
    //     $comments = $request->input('comments', []);
    //     foreach ($request->current_issued as $itemId => $qty) {

    //         if (!$qty || $qty <= 0) {
    //             continue;
    //         }
    //             $stockIn = Stock::where('item_id', $itemId)->where('type', 'in')->sum('quantity');
    //             $stockOut = Stock::where('item_id', $itemId)->where('type', 'out')->sum('quantity');
    //             $stockIssued = Stock::sum('issued_qty');
    //             $availableStock = $stockIn - $stockIssued;

    //         if ($availableStock <= 0) {
    //             return redirect()->back()->with('error', "Item ID {$itemId} has no available stock. Issue cannot be processed.");
    //         }

    //         if ($qty > $availableStock) {
    //             return redirect()->back()->with('error', "Item ID {$itemId} the required quantity ($qty) is greater than available stock ($availableStock).");
    //         }
    //         $comment = $comments[$itemId] ?? null;
    //         $itemDemand->items()->updateExistingPivot($itemId, [
    //             'previous_issued'    => DB::raw("COALESCE(previous_issued, 0) + $qty"),
    //             'current_issued'     => $qty,
    //             'progressive_total'  => DB::raw("COALESCE(progressive_total, 0) + $qty"),
    //             'balance_qty'        => DB::raw("allocated_qty - (COALESCE(previous_issued, 0) + $qty)"),
    //             'comments'           => $comment,
    //         ]);

    //         Stock::create([
    //             'item_id'      => $itemId,
    //             'warehouse_id' => $itemDemand->warehouse_id ?? 1,
    //             'date'         => now(),
    //             'type'         => 'issued',
    //             'issued_qty'   => $qty,
    //             'demand_number'=> $itemDemand->demand_no,
    //             'created_by'   => Auth::id(),
    //         ]);
    //     }

    //     // Demand status update
    //     $itemDemand->update(['status' => 'issued']);

    //     return redirect()->route('itemDemand.view', $id)->with('success', 'Demand Issued Successfully');
    // }
    public function create()
    {
        $this->authorize('itemDemand_view');

        $items = Item::with('projects')->where('status', 'active')->get();
        $projects = Project::with('houseProjects')->get();
        $contractors = Contractor::where('status', 'active')->get();
        $houseProjects = HouseProject::all();

        return view('itemDemand.create', compact(
            'projects',
            'contractors',
            'houseProjects',
            'items'
        ));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'project_id'        => 'required|exists:projects,id',
            'date'              => 'required|date',
            'contractor_id'     => 'required|array',
            'contractor_id.*'   => 'required|exists:contractors,id',
            'item_id'           => 'required|array',
            'item_id.*'         => 'required|exists:items,id',
            'item_qty'          => 'required|array',
            'item_qty.*'        => 'required',
            'house_project_id'  => 'nullable|array',
            'house_series_id'   => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();
            $latestId = ItemDemand::max('id') + 1;
            $demandNo = 'D-' . str_pad($latestId, 5, '0', STR_PAD_LEFT);

            $itemDemand = ItemDemand::create([
                'project_id' => $request->project_id,
                'demand_no'  => $demandNo,
                'date'       => $request->date,
                'created_by' => auth()->id(),
            ]);

            logUserActivity('ItemDemand', 'Create Item Demand ' . $itemDemand->id, $itemDemand->id, 'ItemDemand');

            $attached = false;

            foreach ($request->item_id as $key => $itemId) {
                $qty = $request->item_qty[$key] ?? null;
                $contractorId = $request->contractor_id[$key] ?? null;

                if ($qty && $contractorId) {
                    $itemDemand->items()->attach($itemId, [
                        'contractor_id'    => $contractorId,
                        'house_project_id' => $request->house_project_id[$key] ?? null,
                        'house_series_id'  => $request->house_series_id[$key] ?? null,
                        'item_qty'         => $qty,
                        'allocated_qty'    => $request->allocated_qty[$key] ?? 0,
                        'over_qty'         => $request->over_qty[$key] ?? 0,
                        'over_description' => $request->over_description[$key] ?? null,
                    ]);
                    $attached = true;
                }
            }

            if (!$attached) {
                return back()->with('error', 'Please select at least one item with quantity.');
            }


            //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'itemDemand_create',
                    'Item Demand',
                    'New Item Demand Created',
                    'Item Demand "' . $itemDemand->project_id . '" has been created By.' . auth()->user()->name,
                    route('itemDemand.index')
                );
            //END======================================================================================


            DB::commit();
            return redirect()->route('itemDemand.index')->with('success', 'Item Demand created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Item Demand Create Error: ' . $e->getMessage());
            return back()->with('error', 'Error creating Item Demand.');
        }
    }
    public function destroy($id)
    {
        $itemDemand = ItemDemand::findOrFail($id);
        $itemDemand->delete();
        logUserActivity('ItemDemand', 'Delete Item Demand ' . $itemDemand->id, $itemDemand->id, 'ItemDemand');
        return redirect()->route('itemDemand.index')->with('success', 'ItemDemand deleted!');
    }
    public function trash()
    {
        $this->authorize('itemDemand_trash_view');
        $itemDemands = ItemDemand::with('items', 'contractor', 'project', 'creator')->onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('ItemDemand.trash', compact('itemDemands'));
    }

    public function restore($id)
    {
        try {
            $ItemDemand = ItemDemand::withTrashed()->findOrFail($id);
            $ItemDemand->restore();
            return redirect()->route('itemDemand.index')->with('success', 'ItemDemand restored successfully.');
        } catch (Exception $e) {
            Log::error('ItemDemand restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring ItemDemand: ' . $e->getMessage());
        }
    }
    public function approve(Request $request, $id)
    {
        // dd($request->all());
        $demand = ItemDemand::findOrFail($id);
        $user = auth()->user();
        // return $user;
        $action = $request->input('action');
        $remarks = $request->input('remarks');

        $value = $action === 'approve' ? 1 : -1;

        if ($user->department == 'Project-Manager' && $demand->approved_by_pm == 0) {
            $demand->approved_by_pm = $value;
            $demand->remarks_by_pm = $remarks;

        } elseif ($user->department == 'Store-Manager' && $demand->approved_by_pm == 1 && $demand->approved_by_sm == 0) {
            $demand->approved_by_sm = $value;
            $demand->remarks_by_sm = $remarks;
        } elseif ($user->department == 'Manager-of-Procurement' && $demand->approved_by_pm == 1 && $demand->approved_by_sm == 1 && $demand->approved_by_mp == 0) {
            $demand->approved_by_mp = $value;
            $demand->remarks_by_mp = $remarks;
        } else {
            return back()->with('error', 'Already processed or not allowed!');
        }

        $demand->save();

        //SEND NOTIFICATION CODE START FROM HERE==================================================
        if($user->department == 'Project-Manager'){
        $usersWithPermission = User::whereIn('department', ['Store-Manager', 'CEO'])->get();
        }elseif($user->department == 'Store-Manager'){
            $usersWithPermission = User::whereIn('department', ['Manager-of-Procurement', 'CEO'])->get();
        }


        $notification = Notification::create([
            'type' => 'Item Demand',
            'subject' => 'Item Demand ' . $action . 'ed',
            'message' => 'Item Demand "' . $demand->project_id . '" has been ' . $action . 'ed By ' . auth()->user()->name,
            'is_global' => false,
            'url' => route('itemDemand.index'),
        ]);

        foreach ($usersWithPermission as $user) {
            $user->notifications()->attach($notification->id, ['is_read' => false]);
        }
        //END======================================================================================

        return back()->with('success', 'demand request ' . $action . 'ed successfully!');
    }
}
