<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use App\Models\Item;
use App\Models\ItemDemand;
use App\Models\Project;
use App\Models\Stock;
use App\Models\ContractorProject;

use App\Models\HouseProjectHouse;
use App\Models\User;
use PhpParser\Node\Expr\Clone_;

class DashboardController extends Controller
{

public function index()
{
    $projects = Project::all();
    $projects_count = Project::count();
    $contractors = Contractor::with([
    'houseProjects.houses' => function ($query) {
        $query->select('id', 'house_project_id', 'house_number');
    },
    'houseProjects.project:id,project_name'
])->get();

    $contractors_count = Contractor::count();
    $items = Item::count();
    $itemDemand = ItemDemand::count();
    $stockIn = Stock::where('type', 'in')->sum('total');
    $stockOut = Stock::where('type', 'out')->sum('total');
    $itemDemands = ItemDemand::latest()->take(10)->get();

$itemTypes = [
    'Steel'   => ['deno' => ['M/Ton']],
    'Cement'  => ['deno' => ['Kgs', 'Bags']],
    'Block'  => ['deno' => ['Nos']],
    'Tiles'   => ['deno' => ['Sqmtrs', 'SFT']],
    'Bitumen' => ['deno' => ['Kgs','Drum']],
];

$stockData = [];

foreach ($itemTypes as $key => $config) {
    // ✅ Fetch matching items for given type + deno + item name
    $items = Item::where('item', 'like', "%{$key}%")
        ->whereIn('deno', $config['deno'])
        ->where('items_type', 'DTH')
        ->pluck('id');

    if ($items->isEmpty()) {
        // If no items match, continue
        $stockData[$key] = ['in' => 0, 'out' => 0, 'total' => 0];
        continue;
    }

    // ✅ Get stock entries for these items
    $stocks = Stock::whereIn('item_id', $items)->get();

    // ✅ Calculate in/out/total
    $stock_in = $stocks->where('type', 'in')->sum('quantity');
    $stock_out = $stocks->where('type', 'out')->sum('quantity');
    $total_stock = $stock_in - $stock_out;

    $stockData[$key] = [
        'in' => $stock_in,
        'out' => $stock_out,
        'total' => $total_stock,
    ];
}


    if (auth()->user()->role === 'admin' || auth()->user()->role === 'Manager Procurement' || auth()->user()->role === 'Project Manager') {

        return view('dashboard_simple', compact(
            'projects_count',
            'contractors_count',
            'items',
            'itemDemand',
            'stockIn',
            'stockOut'
        ));
    } else {
        return view('dashboard', compact(
            'projects',
            'projects_count',
            'contractors',
            'contractors_count',
            'items',
            'itemDemand',
            'stockIn',
            'stockOut',
            'itemDemands',
            'stockData'

        ));
        // Simple dashboard
    }
}



}
