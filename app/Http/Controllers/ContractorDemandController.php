<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use App\Models\HouseProject;
use App\Models\Item;
use App\Models\ItemDemand;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractorDemandController extends Controller
{

    public function demand()
    {
        $items = Item::with('projects')->where('status', 'active')->get();
        $projects = Project::with('houseProjects')->get();
        $contractors = Contractor::where('status', 'active')->get();
        $houseProjects = HouseProject::all();

        return view('itemDemand.openDemand', compact(
            'projects',
            'contractors',
            'houseProjects',
            'items'
        ));
    }
    public function store(Request $request)
    {

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
            ]);
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
                DB::rollBack();
                return back()->with('swal_error', 'Please select at least one item with quantity.');
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
            return back()->with('swal_success', 'Item Demand created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('swal_error', 'Error creating Item Demand.');
        }
    }
    public function receiveView()
    {
        return view('itemDemand.receivedView');
    }
    public function getContractorDemands($code)
    {
        $contractor = Contractor::where('code', $code)->first();
        // dd($contractor);
        if (!$contractor) {
            return response()->json(['status' => 'error', 'message' => 'Contractor not found']);
        }

        $demands = ItemDemand::with(['project', 'items'])
            ->whereHas('contractors', function ($q) use ($contractor) {
                $q->where('contractor_id', $contractor->id);
            })
            ->latest()
            ->get();

        $data = $demands->map(function ($demand) use ($contractor) {
            return [
                'demand_no' => $demand->demand_no,
                'date'      => $demand->date,
                'project'   => $demand->project->project_name ?? '',
                'items'     => $demand->items->filter(fn($i) => $i->pivot->contractor_id == $contractor->id)
                                ->map(fn($i) => [
                                    'item'      => $i->item,
                                    'qty'       => $i->pivot->item_qty,
                                    'over_qty'  => $i->pivot->over_qty,
                                    'balance'   => $i->pivot->balance_qty,
                                ]),
                                'token' => $demand->public_token,
                                'status' => $demand->status,
            ];
        });

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function receive($token)
    {
        $itemDemand = ItemDemand::where('public_token', $token)
        ->with(['items', 'project', 'creator'])->firstOrFail();
        $contractorIds = $itemDemand->items->pluck('pivot.contractor_id')->unique()->filter();
        $contractors = Contractor::whereIn('id', $contractorIds)->get();
        return view('itemDemand.received', compact('itemDemand', 'contractors'));
    }
    public function received($token)
    {
        $itemDemand = ItemDemand::where('public_token', $token)->firstOrFail();
        // dd($itemDemand);
        if ($itemDemand->status !== 'issued') {
            return back()->with('error', 'Demand not yet issued by store manager.');
        }

        $itemDemand->update(['status' => 'received']);

        return back()->with('swal_success','Demand Received Successfully');
    }
    public function receiveAndStockOut($token, $itemId)
    {
        $itemDemand = ItemDemand::where('public_token', $token)
            ->with(['items', 'project', 'contractors'])->firstOrFail();

        $item = $itemDemand->items->where('id', $itemId)->first();

        if (!$item) {
            return back()->with('error', 'Item not found for this demand.');
        }

        $projectId = $itemDemand->project_id ?? null;
        $contractorId = $itemDemand->contractors->first()->id ?? $itemDemand->contractor_id ?? null;
        $houseTypeId = $itemDemand->house_type_id ?? null;

        $issuedQty = $item->pivot->current_issued ?? 0;
        $rate = $item->pivot->rate ?? $item->rate;

        if (empty($rate) || $rate == 0) {
            $lastStock = DB::table('stocks')
                ->where('item_id', $item->id)
                ->where('type', 'in')
                ->orderByDesc('id')
                ->first();

            $rate = $lastStock->price ?? 0;
        }
        $total = $issuedQty * $rate;
        
        DB::table('stocks')->insert([
            'item_id' => $item->id,
            'warehouse_id' => 1,
            'project_id'   => $projectId,
            'contractor_id' => $contractorId,
            'house_type_id' => $houseTypeId,
            'demand_number' => $itemDemand->demand_number ?? null,
            'purchase_order_number' => null,
            'challan_number' => null,
            'quantity' => $issuedQty,
            'price' => $rate,
            'total' => $total,
            'type' => 'out',
            'date' => now(),
            'created_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $itemDemand->update(['status' => 'received']);
        $itemDemand->items()->updateExistingPivot($itemId, [
            'status' => 'received',
        ]);

        // ===== SEND NOTIFICATION =====
        sendPermissionNotification(
            'purchaseOrder_view',
            'Item Stock Out',
            'Item Stock Out successfully',
            'Item "' . $item->item . '" stock out successfully with amount ' . number_format($total, 2),
            route('itemDemand.index')
        );

        return back()->with('success', 'Item stock out successfully. Total amount recorded: ' . number_format($total, 2));
    }

    public function getProjectContractors($projectId)
    {
        $project = Project::with(['contractors' => function ($q) {
            $q->wherePivot('status', 'active');
        }])->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }
        $contractors = $project->contractors->map(function ($contractor) {
            return [
                'id' => $contractor->id,
                'name' => $contractor->name,
                'code' => $contractor->code,
            ];
        });
        return response()->json($contractors);
    }

//     public function getContractorByCode($code)
// {
//     $contractor = Contractor::where('code', $code)
//         ->where('status', 'active')
//         ->first();

//     if (!$contractor) {
//         return response()->json(['status' => 'error', 'message' => 'Invalid contractor code']);
//     }

//     return response()->json([
//         'status' => 'success',
//         'data' => [
//             'id' => $contractor->id,
//             'name' => $contractor->name,
//             'code' => $contractor->code,
//         ],
//     ]);
// }


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
    // public function getHouseTypes($contractorId , $projectId)
    // {
    //     $houseTypes = HouseProject::where('project_id', $projectId)
    //     ->whereHas('contractors', function ($q) use ($contractorId) {
    //         $q->where('contractor_id', $contractorId);
    //     })
    //     ->select('id', 'house_type_id')->get();
    //     return response()->json($houseTypes);
    // }

    public function getItems($projectId, $contractorId)
    {
        // Pull project+contractor specific items and allocated quantities
        $items = DB::table('item_project_pivot')
            ->join('items', 'item_project_pivot.item_id', '=', 'items.id')
            ->where('item_project_pivot.project_id', $projectId)
            ->where('item_project_pivot.contractor_id', $contractorId)
            ->select('items.id','items.item as item_name',
                'items.size','item_project_pivot.quantity as allocated_qty')
            ->distinct()->get();

        $items = $items->map(function ($item) use ($contractorId) {
            $issued = DB::table('item_demand_item')
                ->where('item_id', $item->id)->where('contractor_id', $contractorId)
                ->whereNull('deleted_at')->sum('current_issued');

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

    public function contractorCodeForm()
    {
        return view('itemDemand.contractorCode');
    }
    public function showDemands($code)
    {
        $contractor = Contractor::where('code', $code)->first();

        if (!$contractor) {
            if (request()->ajax()) {
                return response()->json(['exists' => false]);
            }
            return back()->with('error', 'Invalid contractor code.');
        }

        if (request()->ajax()) {
            return response()->json(['exists' => true]);
        }

        $itemDemands = ItemDemand::whereHas('items', function ($q) use ($contractor) {
                $q->where('contractor_id', $contractor->id);
            })
            ->with([
                'items' => function ($q) use ($contractor) {
                    $q->where('contractor_id', $contractor->id);
                },
                'project',
                'creator'
            ])
            ->orderBy('id', 'desc')
            ->get();

        // 🧮 Add same calculations like in view()
        foreach ($itemDemands as $itemDemand) {
            foreach ($itemDemand->items as $item) {
                $contractorId = $item->pivot->contractor_id ?? $contractor->id;

                // Total issued before this demand
                $previousIssuedTotal = DB::table('item_demand_item')
                    ->where('item_id', $item->id)
                    ->where('contractor_id', $contractorId)
                    ->where('item_demand_id', '!=', $itemDemand->id)
                    ->sum('previous_issued');

                // 🧾 Calculated fields
                $item->calculated_previous_issued = $previousIssuedTotal;
                $item->calculated_progressive_total = $previousIssuedTotal + ($item->pivot->current_issued ?? 0);
                $item->calculated_balance_qty = ($item->pivot->allocated_qty ?? 0) - $item->calculated_progressive_total;
            }
        }

        return view('itemDemand.contractorDemandList', compact('contractor', 'itemDemands'));
    }

    // public function showDemands($code)
    // {
    //     $contractor = Contractor::where('code', $code)->first();

    //     if (!$contractor) {
    //         if (request()->ajax()) {
    //             return response()->json(['exists' => false]);
    //         }
    //         return back()->with('error', 'Invalid contractor code.');
    //     }

    //     if (request()->ajax()) {return response()->json(['exists' => true]);}

    //     $itemDemands = ItemDemand::whereHas('items', function ($q) use ($contractor) {
    //             $q->where('contractor_id', $contractor->id);
    //         })
    //         ->with([
    //             'items' => function ($q) use ($contractor) { $q->where('contractor_id', $contractor->id);},'project','creator'
    //         ])->orderBy('id', 'desc')->get();

    //     return view('itemDemand.contractorDemandList', compact('contractor', 'itemDemands'));
    // }


}
