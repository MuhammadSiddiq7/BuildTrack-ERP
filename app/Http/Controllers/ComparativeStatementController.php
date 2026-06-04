<?php

namespace App\Http\Controllers;

use App\Models\ComparativeStatement;
use App\Models\ComparativeStatementAttachment;
use App\Models\ComparativeStatementDetail;
use App\Models\ComparativeStatementItem;
use App\Models\Item;
use App\Models\ItemDemand;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComparativeStatementController extends Controller
{
    use AuthorizesRequests;

    // public function index()
    // {
    //     $this->authorize('comparativeStatement_view');
    //     $trashComparativeStatement = ComparativeStatement::onlyTrashed()->count();
    //     $comparativeStatements = ComparativeStatement::with('comparativeStatementItems')->orderBy('created_at', 'desc')->get();
    //     // dd($comparativeStatements);
    //     return view('comparativeStatement.index', compact('comparativeStatements', 'trashComparativeStatement'));
    // }
    public function approveStatus($id)
    {
        $statement = ComparativeStatement::find($id);

        if (!$statement) {
            return response()->json(['success' => false, 'message' => 'Record not found.']);
        }

        if ($statement->approve_status != 'pending') {
            return response()->json(['success' => false, 'message' => 'Already approved.']);
        }

        $statement->approve_status = 'approved';
        $statement->save();

        return response()->json(['success' => true, 'message' => 'Status updated to approved.']);
    }

    public function index()
    {
        $this->authorize('comparativeStatement_view');

        $trashComparativeStatement = ComparativeStatement::onlyTrashed()->count();
        $comparativeStatements = ComparativeStatement::orderBy('created_at', 'desc')->get();

        return view('comparativeStatement.index', compact('comparativeStatements', 'trashComparativeStatement'));
    }
    public function create($id)
    {
        $this->authorize('comparativeStatement_create');

        $itemDemand = ItemDemand::with([
            'items.quotations.supplier'
        ])->findOrFail($id);

        return view('comparativeStatement.create', compact('itemDemand'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'item_demand_id' => 'required|exists:item_demands,id',
            'selected_supplier' => 'required|array'
        ]);
        // dd($request->all());
        DB::transaction(function () use ($request) {
            $itemDemand = ItemDemand::with('items.quotations')->findOrFail($request->item_demand_id);

            $cs = ComparativeStatement::create([
                'item_demand_id' => $itemDemand->id,
                'project_id'     => $itemDemand->project_id,
                'created_by'     => Auth::id(),
                'status'         => 'in_active',
            ]);
            foreach ($itemDemand->items as $item) {
                foreach ($item->quotations as $quotation) {
                    $total = $quotation->rate * $quotation->quantity;

                    ComparativeStatementDetail::create([
                        'comparative_statement_id' => $cs->id,
                        'item_id'       => $item->id,
                        'supplier_name' => $quotation->supplier_name,
                        'rate'          => $quotation->rate,
                        'quantity'      => $quotation->quantity,
                        'total'         => $total,
                    ]);

                }
            }


            $grandTotal = 0;

            foreach ($request->selected_supplier as $itemId => $quotationId) {
                $quotation = Quotation::findOrFail($quotationId);

                $total = $quotation->rate * $quotation->quantity;
                $grandTotal += $total;

                // Save CS item
                $csItem = ComparativeStatementItem::create([
                    'comparative_statement_id' => $cs->id,
                    'item_id'        => $itemId,
                    'supplier_id'    => $quotation->supplier_id,
                    'requested_qty'  => $quotation->quantity,
                    'rate'           => $quotation->rate,
                    'total'          => $total,
                    'remarks'        => $request->remarks[$itemId][$quotationId] ?? null,
                ]);
                if ($request->hasFile("attachments.$itemId.$quotationId")) {
                    foreach ($request->file("attachments.$itemId.$quotationId") as $file) {
                        $path = $file->store('comparative_statements', 'public');
                        ComparativeStatementAttachment::create([
                            'cs_item_id' => $csItem->id,
                            'file_path' => $path,
                        ]);
                    }
                }
            }

            $cs->update(['grand_total' => $grandTotal]);
        });

        return redirect()->route('comparativeStatement.index')
            ->with('success', 'Comparative Statement Stored Successfully!');
    }


    public function view($id)
    {
        $this->authorize('itemDemand_view');
        $itemDemand = ItemDemand::with(
            'items',
            'contractor',
            'project',
            'creator',
            'purchaseOrder',
            'comparativeStatement',
            'comparativeStatementItems'
        )->findOrFail($id);
        // dd($itemDemand);
        return view('comparativeStatement.view', compact('itemDemand'));
    }
    public function viewPo(Request $request)
    {
        $this->authorize('comparativeStatement_create');

        $itemDemand = ItemDemand::with(['items' => function ($query) use ($request) {
            $query->where('item_id', $request->item_id);
        }])->findOrFail($request->item_demand_id);

        $comparativeStatementId = ComparativeStatement::where('item_demand_id', $itemDemand->id)->first()?->id;

        $itemsWithSuppliers = [];

        foreach ($itemDemand->items as $item) {
            $suppliersData = ComparativeStatementDetail::where('comparative_statement_id', $comparativeStatementId)
                ->where('item_id', $item->id)
                ->get();

            $lowestRate = $suppliersData->min('rate');
            $recommendedSupplier = $suppliersData->where('rate', $lowestRate)->first()?->supplier_name;

            $suppliersData = $suppliersData->map(function ($supplier) use ($comparativeStatementId, $item) {
                $csItem = ComparativeStatementItem::where('comparative_statement_id', $comparativeStatementId)
                    ->where('item_id', $item->id)
                    ->where('supplier_id', $supplier->supplier_id)
                    ->with('attachments')
                    ->first();

                $supplier->attachments = $csItem?->attachments ?? collect();
                return $supplier;
            });


            $itemsWithSuppliers[] = [
                'item' => $item,
                'suppliers' => $suppliersData,
                'recommended_supplier' => $recommendedSupplier,
            ];
        }

        return view('comparativeStatement.po_view', compact('itemDemand', 'itemsWithSuppliers', 'comparativeStatementId'));
    }

    public function generateCS(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'comparative_statement_id' => 'required|exists:comparative_statements,id',
            'item_id' => 'required|exists:items,id',
            'contractor_id' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            $comparativeStatement = ComparativeStatement::findOrFail($request->comparative_statement_id);
            $item = Item::findOrFail($request->item_id);

            // dd($comparativeStatement);
            $csi = ComparativeStatementItem::where('comparative_statement_id', $comparativeStatement->id)
                ->where('item_id', $item->id)->where('contractor_id', $request->contractor_id)->first();

            if (!$csi) {
                throw new \Exception('Comparative statement item not found.');
            }

            $poNumber = 'PO-' . strtoupper(uniqid());
            $po = PurchaseOrder::create([
                'comparative_statement_id' => $comparativeStatement->id,
                'project_id' => $comparativeStatement->project_id,
                'po_number' => $poNumber,
                'po_date' => now(),
                'grand_total' => $csi->total,
                'approve_status' => 'pending',
                'created_by' => Auth::id(),
                'status' => 'active',
            ]);

            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'contractor_id' => $request->contractor_id,
                'item_id' => $item->id,
                'supplier_id' => $csi->supplier_id,
                'quantity' => $csi->quantity,
                'rate' => $csi->rate,
                'total' => $csi->total,
            ]);

            logUserActivity('Purchase Order', 'Generated PO for item ID ' . $item->id, $po->id, 'Purchase Order');
        });

        return redirect()->route('comparativeStatement.index')->with('success', 'Purchase Order generated successfully.');
    }
    public function generateInvoice(Request $request)
    {
        $csId   = $request->comparative_statement_id;
        $itemId = $request->item_id;
        $itemDemandId = $request->item_demand_id;
        $item_demand_name = $request->item_demand_name;
        $cs = ComparativeStatement::with('itemDemand')->findOrFail($csId);
        $details = Quotation::where('item_demand_id', $itemDemandId)->where('item_id', $itemId)->get();
            // dd($details);
        $pdf = Pdf::loadView('comparativeStatement.invoice', compact('cs', 'details', 'item_demand_name'))

        ->setPaper('A4', 'portrait');

        return $pdf->stream('Invoice-CS-' . $cs->id . '-Item-' . $itemId . '.pdf');
    }
    // public function generateInvoice(Request $request)
    // {
    //     dd($request->all());
    //     $request->validate([
    //         'item_id' => 'required|exists:items,id',
    //         'contractor_id' => 'required|exists:house_projects,id',
    //     ]);

    //     $poItem = \App\Models\PurchaseOrderItem::where('item_id', $request->item_id)
    //         ->where('contractor_id', $request->contractor_id)
    //         ->with(['item', 'supplier', 'purchaseOrder'])->firstOrFail();

    //     $po = $poItem->purchaseOrder;
    //     $pdf = Pdf::loadView('purchaseOrder.invoice', compact('po', 'poItem'));

    //     return $pdf->stream('PO-Invoice-' . $po->po_number . '.pdf');
    //     // return $pdf->download('PO-Invoice-' . $po->po_number . '.pdf');
    // }
}
