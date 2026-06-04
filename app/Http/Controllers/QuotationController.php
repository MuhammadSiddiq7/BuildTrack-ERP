<?php

namespace App\Http\Controllers;

use App\Models\ItemDemand;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class QuotationController extends Controller
{
     use AuthorizesRequests;

    public function index()
    {
        // $this->authorize('quotation_view');

        $trashquotation = Quotation::onlyTrashed()->count();
        $quotations = Quotation::with('item')->orderBy('created_at', 'desc')->get();
        // dd($quotations);
        return view('quotation.index', compact('quotations', 'trashquotation'));
    }
    public function create($id)
    {
        $itemDemand = ItemDemand::with('items')->findOrFail($id);

        return view('quotation.create', compact('itemDemand'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'item_demand_id' => 'required|exists:item_demands,id',
            'quotations' => 'required|array',
        ]);

        foreach ($request->quotations as $itemId => $supplierRows) {
            $hasValidSupplier = false;

            foreach ($supplierRows as $row) {
                // Check valid supplier row
                if (!empty($row['supplier_name']) && !empty($row['rate']) && !empty($row['quantity'])) {
                    $hasValidSupplier = true;

                    Quotation::create([
                        'item_demand_id' => $request->item_demand_id,
                        'item_id' => $itemId,
                        'supplier_name' => $row['supplier_name'],
                        'description' => $row['description'] ?? null,
                        'deno' => $row['deno'] ?? null,
                        'quantity' => $row['quantity'],
                        'rate' => $row['rate'],
                        'amount' => $row['amount'] ?? ($row['quantity'] * $row['rate']),
                    ]);
                }
            }

            // ✅ Agar ek bhi valid supplier nahi mila, to is item ke liye kuch insert na ho
            if (!$hasValidSupplier) {
                continue;
            }
        }

        return redirect()
            ->route('comparativeStatement.create', $request->item_demand_id)
            ->with('success', 'Quotations saved successfully.');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'item_demand_id' => 'required|exists:item_demands,id',
    //         'quotations' => 'required|array',
    //     ]);

    //     foreach ($request->quotations as $itemId => $supplierRows) {
    //         foreach ($supplierRows as $row) {
    //             Quotation::create([
    //                 'item_demand_id' => $request->item_demand_id,
    //                 'item_id' => $itemId,
    //                 'supplier_name' => $row['supplier_name'],
    //                 'description' => $row['description'] ?? null,
    //                 'deno' => $row['deno'] ?? null,
    //                 'quantity' => $row['quantity'],
    //                 'rate' => $row['rate'],
    //                 'amount' => $row['amount'],
    //                 // 'remarks' => $row['remarks'] ?? null,
    //             ]);
    //         }
    //     }

    //     return redirect()->route('comparativeStatement.create', $request->item_demand_id)->with('success', 'Quotations saved successfully.');
    // }

    public function show($id)
    {
        $itemDemand = ItemDemand::with('items')->findOrFail($id);

        $quotations = \App\Models\Quotation::where('item_demand_id', $id)
            ->orderBy('rate', 'asc')->get()->groupBy('item_id');

        return view('comparativeStatement.show', compact('itemDemand', 'quotations'));
    }

}
