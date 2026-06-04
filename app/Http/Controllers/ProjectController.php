<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CompanyBank;
use App\Models\Contractor;
use App\Models\HouseProject;
use App\Models\HouseType;
use App\Models\Item;
use App\Models\ItemProject;
use App\Models\Project;
use App\Models\Warehouse;
use App\Models\HouseSeries;
use App\Models\Supplier;
use App\Models\HouseProjectHouse;
use App\Models\ItemDemand;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('project_view');

        $trashproject = Project::onlyTrashed()->count();
        $projects = Project::with('contractors')->orderBy('created_at', 'desc')->get();

        return view('project.index', compact('projects', 'trashproject'));
    }

    public function categories_create($projectId)
    {
        $this->authorize('project_create');
        $projects = Project::findOrFail($projectId);
        $houseProjects = HouseProject::with('houseSeries')->where('project_id', $projectId)->get();
        $contractors = Contractor::all();
        $warehouses = Warehouse::all();
        $houseTypes = HouseType::all();
        $companyBanks = CompanyBank::all();

        return view('project.categories_create', compact(
            'projects',
            'houseProjects',
            'contractors',
            'warehouses',
            'houseTypes',
            'companyBanks'
        ));
    }
    public function categories_store(Request $request, $projectId)
    {
        // dd($request->all());
        $request->validate([
            'house_type_id'    => 'required',
            'site_square_yard' => 'required|array',
            'contractor_id'    => 'required|array',
            'warehouse_id'     => 'required|array',
            'houses'           => 'required|array',
            'description'      => 'nullable|array',
        ]);

        $project = Project::findOrFail($projectId);

        DB::beginTransaction();
        try {
            foreach ($request->house_type_id as $index => $houseTypeId) {
                // create house_project
                $houseProject = HouseProject::create([
                    'project_id'       => $project->id,
                    'house_type_id'    => $houseTypeId,
                    'site_square_yard' => $request->site_square_yard[$index] ?? null,
                    'warehouse_id'     => $request->warehouse_id[$index] ?? null,
                    'description'      => $request->description[$index] ?? null,
                    'total_houses'     => isset($request->houses[$index]) ? count($request->houses[$index]) : 0,
                ]);

                // selected houses save
                $selectedHouses = $request->houses[$index] ?? [];
                foreach ($selectedHouses as $houseNumber) {
                    HouseProjectHouse::create([
                        'house_project_id' => $houseProject->id,
                        'house_number'     => (int) $houseNumber,
                    ]);
                }

                // store house series again (if required)
                foreach ($selectedHouses as $houseNumber) {
                    HouseSeries::create([
                        'house_project_id' => $houseProject->id,
                        'total_of_houses'  => (int) $houseNumber,
                    ]);
                }

                // contractor assign
                if (!empty($request->contractor_id[$index])) {
                    $contract_number = 'CN-' . mt_rand(10000000, 99999999);
                    foreach ((array)$request->contractor_id[$index] as $contractorId) {
                        DB::table('contractor_project')->insert([
                            'project_id'       => $project->id,
                            'house_project_id' => $houseProject->id,
                            'contractor_id'    => $contractorId,
                            'contract_number'  => $contract_number,
                            'status'           => 'active',
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);
                    }
                }

                // 🔹 Check house type (example: DTH)
                if (in_array($houseTypeId, ['ATH', 'BTH', 'CTH', 'DTH'])) {
                    $items = Item::where('items_type', $houseTypeId)->get();

                    foreach ($items as $item) {
                        $finalQty = (float) ($item->per_house_qty ?? 0) * (int) ($houseProject->total_houses ?? 0);

                        DB::table('item_project_pivot')->insert([
                            'house_project_id' => $houseProject->id,
                            'item_id'          => $item->id,
                            'project_id'       => $project->id,
                            'contractor_id'    => $contractorId,
                            'quantity'         => $finalQty,
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('project.index')->with('success', 'Categories with Houses added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Category creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating category: ' . $e->getMessage());
        }
    }
    // create project
  public function create()
    {
        $this->authorize('project_create');

        $companyBanks = CompanyBank::all();
        return view('project.create', compact('companyBanks'));
    }

public function view($id)
{
    $this->authorize('project_view'); // optional if you use permissions

    // Load main project with relationships
    $project = Project::with([
        'bank', // or 'companyBank' if you renamed it
        'houseProjects.houseSeries',
        'houseProjects.contractors',
        'houseProjects.houses'
    ])->findOrFail($id);

    // Get related collections (for display/reference)
    $contractors = Contractor::all();
    $warehouses = Warehouse::all();
    $houseTypes = HouseType::all();
    $companyBanks = CompanyBank::all();
    $houseSeries = HouseSeries::all();

    // Fetch house projects for this project
    $houseProjects = HouseProject::with(['houses', 'houseSeries', 'contractors'])
        ->where('project_id', $id)
        ->orderBy('sort_order', 'asc')
        ->get();

    $allHouses = range(1, $project->number_of_houses);

    // Attach available items just like in edit (for consistent display)
    foreach ($houseProjects as $houseProject) {
        $houseTypeId = $houseProject->house_type_id;

        if ($houseTypeId) {
            $existingItemIds = DB::table('item_project_pivot')
                ->where('house_project_id', $houseProject->id)
                ->pluck('item_id')
                ->toArray();

            $availableItems = Item::where('items_type', $houseTypeId)
                ->whereNotIn('id', $existingItemIds)
                ->get();
        } else {
            $availableItems = collect();
        }

        $houseProject->available_items = $availableItems;
    }

    return view('project.view', compact(
        'project',
        'contractors',
        'warehouses',
        'houseTypes',
        'companyBanks',
        'houseSeries',
        'houseProjects',
        'allHouses'
    ));
}

public function projectsList()
{
    $projects = Project::with('contractors')->latest()->get();
    return view('project.list', compact('projects'));
}


// public function projectDashboard()
// {

//     $projects = Project::with(['bank', 'contractors', 'houseProjects.available_items'])->get();
//     $houseProjects = HouseProject::with(['available_items', 'contractors'])->get();
//     $warehouses = Warehouse::all();

//     // Stock example
//     $stockIn = 0; // yahan apna logic lagao total stock in calculate karne ka
//     $stockOut = 0; // total stock out

//     return view('project.project_dashboard', compact('projects', 'houseProjects', 'warehouses', 'stockIn', 'stockOut'));
// }

// public function projectDashboard($id)
// {
//      $project = Project::with([
//         'bank',
//         'contractors.houseProjects.project',
//         'contractors.houseProjects.houses',
//         'houseProjects.contractors',
//         // 'contractors.houseProjects.houseType',
//         'houseProjects.houses'

//     ])->findOrFail($id);

//       $houseProjects = $project->houseProjects;
//     $warehouses = Warehouse::all();

//     // demanded items logic same rehne do
//     foreach ($houseProjects as $houseProject) {
//         $houseProject->demanded_items = DB::table('item_demand_item')
//             ->join('items', 'item_demand_item.item_id', '=', 'items.id')
//             ->where('item_demand_item.house_project_id', $houseProject->id)
//             ->select('item_demand_item.*', 'items.item as item_name')
//             ->get();
//     }

//     return view('project.project_dashboard', compact('project', 'houseProjects', 'warehouses'));
// }

// public function projectDashboard($id)
// {
//      $project = Project::with([
//         'bank',
//         'contractors.houseProjects.project',
//         'contractors.houseProjects.houses',
//         'houseProjects.contractors',
//         // 'contractors.houseProjects.houseType',
//         'houseProjects.houses'

//     ])->findOrFail($id);
//     $project = Project::with([
//         // 'bank',
//         'contractors' => function ($q) use ($id) {
//             $q->with(['houseProjects' => function ($q2) use ($id) {
//                 $q2->where('house_projects.project_id', $id)
//                     ->with([
//                         'project',
//                         'houseType',
//                         'houses',
//                     ]);
//             }]);
//         },
//     ])->findOrFail($id);
//         // dd($project->contractors->pluck('houseProjects'));


//     $houseProjects = \App\Models\HouseProject::with(['houses', 'houseType'])
//         ->where('project_id', $id)
//         ->get();

//     $warehouses = \App\Models\Warehouse::all();

//     foreach ($houseProjects as $houseProject) {
//         $houseProject->demanded_items = \DB::table('item_demand_item')
//             ->join('items', 'item_demand_item.item_id', '=', 'items.id')
//             ->where('item_demand_item.house_project_id', $houseProject->id)
//             ->select('item_demand_item.*', 'items.item as item_name')
//             ->get();
//     }
//     return view('project.project_dashboard', compact('project', 'houseProjects', 'stockData', 'warehouses', 'stock_in','stock_out','total_stock'));
// }

public function projectDashboard($id)
{
    // ✅ Load project + contractor + house projects
    $project = Project::with([
        'contractors.houseProjects.houseType',
        'houseProjects.houseType',
        'houseProjects.houses',
    ])->findOrFail($id);

    // ✅ Get all houseProjects for this project
    $houseProjects = HouseProject::with(['houses', 'houseType'])
        ->where('project_id', $id)
        ->get();

    $warehouses = Warehouse::all();

    // ✅ Define item categories and allowed denos
    $itemTypes = [
        'Steel'   => ['deno' => ['M/Ton']],
        'Cement'  => ['deno' => ['Kgs', 'Bags']],
        'Block'   => ['deno' => ['Nos']],
        'Tiles'   => ['deno' => ['Sqmtrs', 'SFT']],
        'Bitumen' => ['deno' => ['Kgs', 'Drum']],
    ];

    $stockData = [];

    // ✅ Loop through each type and calculate totals project-wise
    foreach ($itemTypes as $key => $config) {

        // 1️⃣ Find items that belong to this category (by name + deno + DTH)
        $items = Item::where('item', 'like', "%{$key}%")
            ->whereIn('deno', $config['deno'])
            ->where('items_type', 'DTH')
            ->pluck('id');

        if ($items->isEmpty()) {
            $stockData[$key] = ['in' => 0, 'out' => 0, 'total' => 0];
            continue;
        }

        // 2️⃣ Fetch all stock movements for this project's items
        // (matching both item_id and project_id)
        $stocks = Stock::whereIn('item_id', $items)
            ->where('project_id', $id)
            ->get();

        // 3️⃣ Calculate stock in/out for this project
        $stock_in = $stocks->where('type', 'in')->sum('quantity');
        $stock_out = $stocks->where('type', 'out')->sum('quantity');
        $total_stock = $stock_in - $stock_out;
        
        $stockData[$key] = [
            'in' => $stock_in,
            'out' => $stock_out,
            'total' => $total_stock,
        ];
    }

    // ✅ Optionally attach demanded items (for detail display)
    foreach ($houseProjects as $houseProject) {
        $houseProject->demanded_items = \DB::table('item_demand_item')
            ->join('items', 'item_demand_item.item_id', '=', 'items.id')
            ->where('item_demand_item.house_project_id', $houseProject->id)
            ->select('item_demand_item.*', 'items.item as item_name')
            ->get();
    }

    // ✅ Return all data to view
    return view('project.project_dashboard', compact(
        'project',
        'houseProjects',
        'warehouses',
        'stockData'
    ));
}



    public function contractorDemandDashboard($contractorId)
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









    public function store(Request $request)
{
    $request->validate([
        'project_name'      => 'required|string|max:255',
        'project_number'    => 'nullable',
        'number_of_houses'  => 'nullable|min:1',
        'project_location'  => 'nullable',
        'company_bank_id'   => 'required',
    ]);
    DB::beginTransaction();
    try {
        // 1. Project create
        $project = Project::create([
            'project_name'     => $request->project_name,
            'project_number'   => $request->project_number,
            'number_of_houses' => $request->number_of_houses,
            'project_location' => $request->project_location,
            'company_bank_id'  => $request->company_bank_id,
        ]);
        $houseProject = HouseProject::create([
            'project_id'     => $project->id,
            'house_type_id'  => null,
            'site_square_yard' => null,
            'warehouse_id'   => null,
            'description'    => null,
        ]);
        // 2. House series create (1 to 1000)
         for ($i = 1; $i <= 1000; $i++) {
            HouseSeries::create([
                'house_project_id' => $houseProject->id,
                'total_of_houses'  => $i,
            ]);
        }
        logUserActivity('Project', 'Create Project ' . $project->project_name, $project->id, 'Project');

        DB::commit();

        return redirect()->route('project.index')->with('success', 'Project created successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Project creation failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error creating Project: ' . $e->getMessage());
    }
}

// edit project
public function editold($id)
{
    $this->authorize('project_edit');

    $project = Project::with(['houseProjects.houseSeries', 'houseProjects.contractors'])->findOrFail($id);
    $contractors = Contractor::all();
    $warehouses = Warehouse::all();
    $houseTypes = HouseType::all();
    $companyBanks = CompanyBank::all();
    // $houseProjects = HouseProject::with('houseSeries')->where('project_id', $id)->get();
    $houseSeries = HouseSeries::all();
    // $available_items = [];

     $houseProjects = HouseProject::with(['houses', 'houseSeries', 'contractors'])
        ->where('project_id', $id)
        ->orderBy('sort_order', 'asc')
        ->get();

        $allHouses = range(1, $project->number_of_houses);

        foreach ($houseProjects as $houseProject) {
            $houseTypeId = $houseProject->house_type_id;

            $existingItemIds = DB::table('item_project_pivot')
                ->where('house_project_id', $houseProject->id)
                ->pluck('item_id')
                ->toArray();

            if ($houseTypeId) {
                $availableItems = Item::where('items_type', $houseTypeId)
                    ->whereNotIn('id', $existingItemIds)
                    ->get();
            } else {
                $availableItems = collect(); // empty collection if no type
            }

            // Attach directly to the houseProject
            $houseProject->available_items = $availableItems;
        }

        // dd($houseProjects);

        // dd($availableItems);
        // dd($houseProject->available_items);





    return view('project.edit', compact(
        'project',
        'contractors',
        'warehouses',
        'houseTypes',
        'companyBanks',
        'houseSeries',
        'houseProjects'
        ,'allHouses'
    ));
}
public function edit($id)
{
    $this->authorize('project_edit');

    $project = Project::with(['houseProjects.houseSeries', 'houseProjects.contractors'])
        ->findOrFail($id);

    $contractors = Contractor::all();
    $warehouses = Warehouse::all();
    $houseTypes = HouseType::all();
    $companyBanks = CompanyBank::all();
    $houseSeries = HouseSeries::all();

    // Get house projects for this project
    $houseProjects = HouseProject::with(['houses', 'houseSeries', 'contractors'])
        ->where('project_id', $id)
        ->orderBy('sort_order', 'asc')
        ->get();

    $allHouses = range(1, $project->number_of_houses);

    foreach ($houseProjects as $houseProject) {
        $houseTypeId = $houseProject->house_type_id;

        if ($houseTypeId) {
            // Get already attached items (for this house project)
            $existingItemIds = DB::table('item_project_pivot')
                ->where('house_project_id', $houseProject->id)
                ->pluck('item_id')
                ->toArray();

            // Get available items: same type, not already attached
            $availableItems = Item::where('items_type', $houseTypeId)
                ->whereNotIn('id', $existingItemIds)
                ->get();
        } else {
            // If no type defined, set empty collection
            $availableItems = collect();
        }

        // ✅ Attach to model so Blade can access it
        $houseProject->available_items = $availableItems;
    }

    // Debug check (optional)
    // dd($houseProjects->pluck('available_items'));

    return view('project.edit', compact(
        'project',
        'contractors',
        'warehouses',
        'houseTypes',
        'companyBanks',
        'houseSeries',
        'houseProjects',
        'allHouses'
    ));
}


// without delete
// public function update(Request $request, $id)
// {
//     $request->validate([
//         'project_name'      => 'required|string|max:255',
//         'project_number'    => 'nullable',
//         'number_of_houses'  => 'nullable|integer|min:1',
//         'project_location'  => 'nullable',
//         'company_bank_id'   => 'required',
//         'rows'              => 'nullable|array',
//         'rows.*.house_type_id' => 'nullable|string',
//         'rows.*.site_square_yard' => 'nullable|integer',
//         'rows.*.warehouse_id' => 'nullable|integer',
//         'rows.*.contractor_id' => 'nullable|array',
//         'rows.*.house_ids' => 'nullable|array',
//         'rows.*.house_project_id' => 'nullable|integer',
//     ]);

//     DB::beginTransaction();
//     try {
//         $project = Project::findOrFail($id);

//         // 1️⃣ Update main project
//         $project->update([
//             'project_name'     => $request->project_name,
//             'project_number'   => $request->project_number,
//             'number_of_houses' => $request->number_of_houses,
//             'project_location' => $request->project_location,
//             'company_bank_id'  => $request->company_bank_id,
//         ]);

//         // 2️⃣ Loop through request rows
//         if ($request->rows) {
//             foreach ($request->rows as $index => $row) {
//                 $houseProjectId = $row['house_project_id'] ?? null;
//                 $selectedHouses = $row['house_ids'] ?? [];

//                 // 🛑 Ignore default null-entry
//                 if (
//                     empty($row['house_type_id']) &&
//                     empty($row['site_square_yard']) &&
//                     empty($row['warehouse_id']) &&
//                     empty($row['description'])
//                 ) {
//                     continue;
//                 }

//                 if ($houseProjectId) {
//                     // ✅ Update existing record
//                     $houseProject = HouseProject::findOrFail($houseProjectId);
//                     $houseProject->update([
//                         'house_type_id'    => $row['house_type_id'],
//                         'site_square_yard' => $row['site_square_yard'] ?? null,
//                         'warehouse_id'     => $row['warehouse_id'] ?? null,
//                         'description'      => $row['description'] ?? null,
//                         'total_houses'     => count($selectedHouses),
//                         'sort_order'       => $index,
//                     ]);
//                 } else {
//                     // ✅ Insert new record (only if new row is added)
//                     $houseProject = HouseProject::create([
//                         'project_id'       => $project->id,
//                         'house_type_id'    => $row['house_type_id'],
//                         'site_square_yard' => $row['site_square_yard'] ?? null,
//                         'warehouse_id'     => $row['warehouse_id'] ?? null,
//                         'description'      => $row['description'] ?? null,
//                         'total_houses'     => count($selectedHouses),
//                         'sort_order'       => $index,
//                     ]);
//                 }

//                 // 🔄 Reset houses
//                 $houseProject->houses()->delete();
//                 foreach ($selectedHouses as $houseNumber) {
//                     HouseProjectHouse::create([
//                         'house_project_id' => $houseProject->id,
//                         'house_number'     => (int) $houseNumber,
//                     ]);
//                 }

//                 // 🔄 Reset house series
//                 $houseProject->houseSeries()->delete();
//                 foreach ($selectedHouses as $houseNumber) {
//                     HouseSeries::create([
//                         'house_project_id' => $houseProject->id,
//                         'total_of_houses'  => (int) $houseNumber,
//                     ]);
//                 }

//                 // 🔄 Reset contractors
//                 DB::table('contractor_project')
//                     ->where('house_project_id', $houseProject->id)
//                     ->delete();

//                 if (!empty($row['contractor_id'])) {
//                     $contract_number = 'CN-' . mt_rand(10000000, 99999999);
//                     foreach ((array) $row['contractor_id'] as $contractorId) {
//                         DB::table('contractor_project')->insert([
//                             'project_id'       => $project->id,
//                             'house_project_id' => $houseProject->id,
//                             'contractor_id'    => $contractorId,
//                             'contract_number'  => $contract_number,
//                             'status'           => 'active',
//                             'created_at'       => now(),
//                             'updated_at'       => now(),
//                         ]);
//                     }
//                 }
//             }
//         }

//         DB::commit();
//         return redirect()->route('project.index')->with('success', 'Project updated successfully.');
//     } catch (\Exception $e) {
//         DB::rollBack();
//         Log::error('Project update failed: ' . $e->getMessage());
//         return redirect()->back()->with('error', 'Error updating project: ' . $e->getMessage());
//     }
// }
public function update(Request $request, $id)
{
    $request->validate([
        'project_name'      => 'required|string|max:255',
        'project_number'    => 'nullable',
        'number_of_houses'  => 'nullable|integer|min:1',
        'project_location'  => 'nullable',
        'company_bank_id'   => 'required',
        'rows'              => 'nullable|array',
        'rows.*.house_type_id' => 'nullable|string',
        'rows.*.site_square_yard' => 'nullable|integer',
        'rows.*.warehouse_id' => 'nullable|integer',
        'rows.*.contractor_id' => 'nullable|array',
        'rows.*.house_ids' => 'nullable|array',
        'rows.*.house_project_id' => 'nullable|integer',
    ]);

    DB::beginTransaction();
    try {
        $project = Project::findOrFail($id);

        // 1️⃣ Update main project
        $project->update([
            'project_name'     => $request->project_name,
            'project_number'   => $request->project_number,
            'number_of_houses' => $request->number_of_houses,
            'project_location' => $request->project_location,
            'company_bank_id'  => $request->company_bank_id,
        ]);

        $incomingIds = [];

        // 2️⃣ Loop through request rows
        if ($request->rows) {
            foreach ($request->rows as $index => $row) {
                $houseProjectId = $row['house_project_id'] ?? null;
                $selectedHouses = $row['house_ids'] ?? [];

                // 🛑 Ignore default null-entry (skip processing)
                if (
                    empty($row['house_type_id']) &&
                    empty($row['site_square_yard']) &&
                    empty($row['warehouse_id']) &&
                    empty($row['description'])
                ) {
                    continue;
                }

                if ($houseProjectId) {
                    // ✅ Update existing record
                    $houseProject = HouseProject::findOrFail($houseProjectId);
                    $houseProject->update([
                        'house_type_id'    => $row['house_type_id'],
                        'site_square_yard' => $row['site_square_yard'] ?? null,
                        'warehouse_id'     => $row['warehouse_id'] ?? null,
                        'description'      => $row['description'] ?? null,
                        'total_houses'     => count($selectedHouses),
                        'sort_order'       => $index,
                    ]);
                } else {
                    // ✅ Insert new record
                    $houseProject = HouseProject::create([
                        'project_id'       => $project->id,
                        'house_type_id'    => $row['house_type_id'],
                        'site_square_yard' => $row['site_square_yard'] ?? null,
                        'warehouse_id'     => $row['warehouse_id'] ?? null,
                        'description'      => $row['description'] ?? null,
                        'total_houses'     => count($selectedHouses),
                        'sort_order'       => $index,
                    ]);
                }

                // track IDs jo form se aaye hain
                $incomingIds[] = $houseProject->id;

                // 🔄 Reset houses
                $houseProject->houses()->delete();
                foreach ($selectedHouses as $houseNumber) {
                    HouseProjectHouse::create([
                        'house_project_id' => $houseProject->id,
                        'house_number'     => (int) $houseNumber,
                    ]);
                }

                // 🔄 Reset house series
                $houseProject->houseSeries()->delete();
                foreach ($selectedHouses as $houseNumber) {
                    HouseSeries::create([
                        'house_project_id' => $houseProject->id,
                        'total_of_houses'  => (int) $houseNumber,
                    ]);
                }

                // 🔄 Reset contractors
                DB::table('contractor_project')
                    ->where('house_project_id', $houseProject->id)
                    ->delete();

                if (!empty($row['contractor_id'])) {
                    $contract_number = 'CN-' . mt_rand(10000000, 99999999);
                    foreach ((array) $row['contractor_id'] as $contractorId) {
                        DB::table('contractor_project')->insert([
                            'project_id'       => $project->id,
                            'house_project_id' => $houseProject->id,
                            'contractor_id'    => $contractorId,
                            'contract_number'  => $contract_number,
                            'status'           => 'active',
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);
                    }
                }
            }
        }

        // 3️⃣ Delete rows not present in request, but ignore null-entry
        HouseProject::where('project_id', $project->id)
            ->whereNotIn('id', $incomingIds)
            ->whereNotNull('house_type_id') // ✅ null-entry safe rahegi
            ->each(function ($hp) {
                $hp->houses()->delete();
                $hp->houseSeries()->delete();
                DB::table('contractor_project')
                    ->where('house_project_id', $hp->id)
                    ->delete();
                $hp->delete();
            });

        DB::commit();
        return redirect()->route('project.index')->with('success', 'Project updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Project update failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error updating project: ' . $e->getMessage());
    }
}

public function update_items($id, $request)
    {
        foreach ($request->house_type_id as $index => $houseTypeId) {
                // create house_project
                $houseProject = HouseProject::create([
                    'project_id'       => $project->id,
                    'house_type_id'    => $houseTypeId,
                    'site_square_yard' => $request->site_square_yard[$index] ?? null,
                    'warehouse_id'     => $request->warehouse_id[$index] ?? null,
                    'description'      => $request->description[$index] ?? null,
                    'total_houses'     => isset($request->houses[$index]) ? count($request->houses[$index]) : 0,
                ]);

                // selected houses save
                $selectedHouses = $request->houses[$index] ?? [];
                foreach ($selectedHouses as $houseNumber) {
                    HouseProjectHouse::create([
                        'house_project_id' => $houseProject->id,
                        'house_number'     => (int) $houseNumber,
                    ]);
                }

                // store house series again (if required)
                foreach ($selectedHouses as $houseNumber) {
                    HouseSeries::create([
                        'house_project_id' => $houseProject->id,
                        'total_of_houses'  => (int) $houseNumber,
                    ]);
                }

                // contractor assign
                if (!empty($request->contractor_id[$index])) {
                    $contract_number = 'CN-' . mt_rand(10000000, 99999999);
                    foreach ((array)$request->contractor_id[$index] as $contractorId) {
                        DB::table('contractor_project')->insert([
                            'project_id'       => $project->id,
                            'house_project_id' => $houseProject->id,
                            'contractor_id'    => $contractorId,
                            'contract_number'  => $contract_number,
                            'status'           => 'active',
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);
                    }
                }

                // 🔹 Check house type (example: DTH)
                if (in_array($houseTypeId, ['ATH', 'BTH', 'CTH', 'DTH'])) {
                    $items = Item::where('items_type', $houseTypeId)->get();

                    foreach ($items as $item) {
                        $finalQty = (float) ($item->per_house_qty ?? 0) * (int) ($houseProject->total_houses ?? 0);

                        DB::table('item_project_pivot')->insert([
                            'house_project_id' => $houseProject->id,
                            'item_id'          => $item->id,
                            'project_id'       => $project->id,
                            'contractor_id'    => $contractorId,
                            'quantity'         => $finalQty,
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);
                    }
                }
            }
        return redirect()->route('project.index')->with('success', 'Project deleted successfully.');
    }





    public function contractorStatus(Request $request)
    {
        // dd($request->all());
        $projectId = $request->project_id;
        $contractorId = $request->contractor_id;

        $project = Project::with(['contractors' => function ($q) use ($contractorId) {
            $q->where('contractor_id', $contractorId);
        }])->findOrFail($projectId);

        $contractor = $project->contractors->first();

        if ($contractor) {
            $currentStatus = $contractor->pivot->status;
            $newStatus = $currentStatus === 'active' ? 'inactive' : 'active';

            $project->contractors()->updateExistingPivot($contractorId, ['status' => $newStatus]);

            return response()->json(['status' => $newStatus]);
        }

        return response()->json(['error' => 'Contractor not found'], 404);
    }


//     public function edit($id)
//     {
//         $this->authorize('project_edit');
//         $project = Project::with('contractors', 'houseProjects')->findOrFail($id);

//         // dd($project->houseProjects);

//         $contractors = Contractor::all();
//         $warehouses = Warehouse::all();
//         $houseTypes = HouseType::all();
//         return view('project.edit', compact('project', 'contractors','warehouses', 'houseTypes'));
//     }

//   public function update(Request $request, $id)
// {
//     DB::beginTransaction();
//     try {
//         // 1. Project update
//         $project = Project::findOrFail($id);
//         $project->project_name = $request->project_name;
//         $project->project_number = $request->project_number;
//         $project->project_location = $request->project_location;
//         $project->save();

//         // 2. House Projects update
//         if ($request->house_number) {
//             foreach ($request->house_number as $index => $houseNumber) {

//                 // House project create/update
//                 $houseProject = HouseProject::updateOrCreate(
//                     [
//                         'project_id' => $project->id,
//                         'id' => $request->house_project_id[$index] ?? null,
//                     ],
//                     [
//                         'number_of_houses' => $houseNumber,
//                         'house_type_id' => $request->house_type_id[$index] ?? null,
//                         'site_square_yard' => $request->site_square_yard[$index] ?? null,
//                         'warehouse_id' => $request->warehouse_id[$index] ?? null,
//                         'description' => $request->description[$index] ?? null,
//                     ]
//                 );

//                 // 3. Contractors sync (yahan multiple assign honge)
//                 if (!empty($request->contractor_ids[$index])) {
//                     $houseProject->contractors()->sync($request->contractor_ids[$index]);
//                 } else {
//                     $houseProject->contractors()->sync([]);
//                 }
//             }
//         }

//         DB::commit();
//     return redirect()->route('project.index')->with('success', 'Project updated successfully');
//     } catch (\Exception $e) {
//         DB::rollBack();
//         return redirect()->back()->with('error', 'Error updating Project: ' . $e->getMessage());
//     }
// }
    public function getContractorHouseProjects($contractorId)
    {
        $contractor = Contractor::findOrFail($contractorId);
        $houseProjects = $contractor->houseProjects;
        return response()->json($houseProjects);
    }


    public function assignItem($id)
    {
        $this->authorize('project_assign_item');
        $project = Project::with(['items.itemProjects', 'houseProjects','houseProjects.contractors'])->findOrFail($id);
        $brands = Brand::all();
        $suppliers = Supplier::all();
        $assignedItemHouse = DB::table('item_project_pivot')->where('project_id', $project->id)->get()
            ->map(function ($row) {
                return $row->item_id . '-' . $row->house_project_id;
            })->toArray();
        $allItems = Item::all();
        // dd($project);
        return view('project.assign_item', compact('project', 'allItems',
        'assignedItemHouse','brands','suppliers'));
    }
//     public function storeAssignedItems(Request $request, $id)
// {
//     $project = Project::findOrFail($id);

//     if (!isset($request->allowed_qty) || !is_array($request->allowed_qty)) {
//         return redirect()->back()->with('error', 'No items selected for assignment.');
//     }

//     foreach ($request->allowed_qty as $compositeId => $quantity) {
//         if ($quantity > 0) {
//             // compositeId format: itemId-houseProjectId
//             [$itemId, $houseProjectId , $contractorId] = explode('-', $compositeId);

//             // ✅ validate that house_project_id match ho raha hai
//             if (!isset($request->house_project_ids[$compositeId]) || $request->house_project_ids[$compositeId] != $houseProjectId) {
//                 continue;
//             }

//             $brandId    = $request->brand_id[$compositeId] ?? null;
//             $supplierId = $request->supplier_id[$compositeId] ?? null;
//             $contractorId = $request->contractor_id ?? null;

//             // ✅ Item assign karte waqt contractor_id bhi save karo
//             ItemProject::create([
//                 'house_project_id' => $houseProjectId,
//                 'project_id'       => $project->id,
//                 'contractor_id'    => $contractorId, // 👈 yeh add kiya
//                 'brand_id'         => $brandId,
//                 'supplier_id'      => $supplierId,
//                 'item_id'          => $itemId,
//                 'quantity'         => $quantity,
//             ]);

//             // ✅ Item quantity update
//             $item = Item::find($itemId);
//             if ($item) {
//                 $item->available_qty = $item->available_qty - $quantity; // 👈 minus karo stock se
//                 $item->save();

//                 logUserActivity(
//                     'Project',
//                     'Assigned item "' . $item->item . '" (Qty: ' . $quantity . ') to Contractor ID ' . $contractorId,
//                     $item->id,
//                     'Item'
//                 );
//             }
//         }
//     }

//     return redirect()->back()->with('success', 'Items assigned successfully.');
// }

    public function storeAssignedItems(Request $request, $id)
{
    $project = Project::findOrFail($id);

    if (!isset($request->allowed_qty) || !is_array($request->allowed_qty)) {
        return redirect()->back()->with('error', 'No items selected for assignment.');
    }

    DB::beginTransaction();
    try {
        foreach ($request->allowed_qty as $compositeId => $quantity) {
            $quantity = floatval($quantity);
            if ($quantity <= 0) {
                continue;
            }

            // compositeId could be:
            // - "123" (just itemId)
            // - "123-45" (itemId-houseProjectId)
            // - "123-45-6" (itemId-houseProjectId-contractorId)
            $parts = explode('-', (string) $compositeId);
            $itemId = $parts[0] ?? null;
            $houseProjectId = $parts[1] ?? null;
            $contractorId = $parts[2] ?? null;

            // try fallback lookups from request arrays (support multiple naming patterns)
            if (!$houseProjectId) {
                $houseProjectId = $request->house_project_ids[$compositeId] ?? $request->house_project_ids[$itemId] ?? null;
            }
            if (!$contractorId) {
                $contractorId = $request->contractor_ids[$compositeId] ?? $request->contractor_ids[$itemId] ?? $request->contractor_id[$itemId] ?? null;
            }

            // if item id still missing skip
            if (!$itemId) {
                continue;
            }

            // brand/supplier fallbacks
            $brandId = $request->brand_id[$compositeId] ?? $request->brand_id[$itemId] ?? null;
            $supplierId = $request->supplier_id[$compositeId] ?? $request->supplier_id[$itemId] ?? null;

            // OPTIONAL: Prevent duplicate assignment (same project,item,house_project,contractor)
            $exists = ItemProject::where('project_id', $project->id)
                ->where('item_id', $itemId)
                ->when($houseProjectId, fn($q) => $q->where('house_project_id', $houseProjectId))
                ->when($contractorId, fn($q) => $q->where('contractor_id', $contractorId))
                ->exists();

            if ($exists) {
                // skip or update - here we skip duplicates
                continue;
            }

            ItemProject::create([
                'house_project_id' => $houseProjectId,
                'project_id'       => $project->id,
                'contractor_id'    => $contractorId,
                'brand_id'         => $brandId,
                'supplier_id'      => $supplierId,
                'item_id'          => $itemId,
                'quantity'         => $quantity,
            ]);

            // update item stock if needed
            $item = Item::find($itemId);
            if ($item) {
                $item->available_qty = max(0, ($item->available_qty ?? 0) - $quantity);
                $item->save();

                logUserActivity(
                    'Project',
                    'Assigned item "' . $item->item . '" (Qty: ' . $quantity . ') to Contractor ID ' . ($contractorId ?? 'N/A'),
                    $item->id,
                    'Item'
                );
            }
        }

        DB::commit();
        return redirect()->back()->with('success', 'Items assigned successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Assign items error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error assigning items: ' . $e->getMessage());
    }
}




    // public function storeAssignedItems(Request $request, $id)
    // {
    //     dd($request->all());
    //     $project = Project::findOrFail($id);

    //     if (!isset($request->allowed_qty) || !is_array($request->allowed_qty)) {
    //         return redirect()->back()->with('error', 'No items selected for assignment.');
    //     }

    //     foreach ($request->allowed_qty as $compositeId => $quantity) {
    //         if ($quantity > 0) {
    //             [$itemId, $houseProjectId] = explode('-', $compositeId);

    //             if (!isset($request->house_project_ids[$compositeId]) || $request->house_project_ids[$compositeId] != $houseProjectId) {
    //                 continue;
    //             }

    //             $brandId = $request->brand_id[$compositeId] ?? null;
    //             $supplierId = $request->supplier_id[$compositeId] ?? null;

    //             ItemProject::create([
    //                 'house_project_id' => $houseProjectId,
    //                 'project_id'       => $project->id,
    //                 'brand_id'         => $brandId,
    //                 'supplier_id'      => $supplierId,
    //                 'item_id'          => $itemId,
    //                 'quantity'         => $quantity,
    //             ]);

    //             $item = Item::find($itemId);
    //             if ($item) {
    //                 $item->available_qty = $quantity;
    //                 $item->save();

    //                 logUserActivity('Project', 'Assigned item "' . $item->item . '" to Project', $item->id, 'Item');
    //             }
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Items assigned successfully.');
    // }

    public function unassignItems(Request $request, $id)
    {
        // dd($request->all());
        $project = Project::findOrFail($id);
        $items = $request->input('items', []);

        foreach ($items as $entry) {
            $itemId = $entry['id'];
            $unassignQty = floatval($entry['quantity']);

            $item = Item::find($itemId);
            $existingPivot = $project->items()->where('item_id', $itemId)->first();
            if ($item && $existingPivot) {
                $currentQty = $existingPivot->pivot->quantity;

                if ($unassignQty < $currentQty) {
                    $project->items()->updateExistingPivot($itemId, [
                        'quantity' => $currentQty - $unassignQty
                    ]);
                } else {
                    $project->items()->detach($itemId);
                }

                $item->available_qty = '0';
                $item->save();
                logUserActivity('Project', 'Unassign Item from Project ' . $item->item, $item->id, 'item');
            }
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            logUserActivity('Project', 'Delete Project ' . $project->site_name, $project->id, 'Project');
            $project->delete();
            return redirect()->route('project.index')->with('success', 'Project deleted successfully.');
        } catch (Exception $e) {
            Log::error('Project deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting Project: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $this->authorize('project_trash_view');
        $projects = Project::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('project.trash', compact('projects'));
    }

    public function restore($id)
    {
        try {
            $project = Project::withTrashed()->findOrFail($id);
            $project->restore();
            return redirect()->route('project.index')->with('success', 'Project restored successfully.');
        } catch (Exception $e) {
            Log::error('Project restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring Project: ' . $e->getMessage());
        }
    }
}

