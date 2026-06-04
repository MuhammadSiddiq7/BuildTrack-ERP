<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemDemand;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDirect;
use App\Models\PurchaseOrderDirectItem;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class PurchaseOrderController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('purchaseOrder_view');
        $trashPurchaseOrder = PurchaseOrder::onlyTrashed()->count();
        $purchaseOrders = PurchaseOrder::orderBy('created_at', 'desc')->get();
        // dd($projects);
        return view('purchaseOrder.index', compact('purchaseOrders', 'trashPurchaseOrder'));
    }
    public function create($id)
    {
        $this->authorize('purchaseOrder_create');

        $itemDemand = ItemDemand::with('items.purchaseOrderItems')->findOrFail($id);
        $allSuppliers = Supplier::with('items')->get();
        $itemsWithSuppliers = [];

        foreach ($itemDemand->items as $item) {
            $suppliers = $item->suppliers->sortBy('pivot.purchase_price');
            $purchaseOrderItem = $item->purchaseOrderItems->first();
            $recommendedSupplier = null;

            if ($purchaseOrderItem) {
                $recommendedSupplier = $allSuppliers->firstWhere('id', $purchaseOrderItem->supplier_id);
            } else {
                $recommendedSupplier = $suppliers->first();
            }

            $itemsWithSuppliers[] = [
                'item' => $item,
                'suppliers' => $suppliers,
                'recommended_supplier' => $recommendedSupplier,
                'is_po_generated' => $purchaseOrderItem ? true : false,
            ];
        }
        return view('purchaseOrder.create', compact('id', 'itemDemand', 'itemsWithSuppliers', 'allSuppliers'));
    }

    public function edit($id)
    {
        $this->authorize('purchaseOrder_edit');
        $id = $id;
        $items = Item::with('projects', 'itemDemands')->get();
        $itemDemand = ItemDemand::with('items')->findOrFail($id);
        $allSuppliers = Supplier::with('items')->get();
        $itemsWithSuppliers = [];

        foreach ($itemDemand->items as $item) {
            $suppliers = $item->suppliers->sortBy('pivot.purchase_price');
            $itemsWithSuppliers[] = [
                'item' => $item,
                'suppliers' => $suppliers,
                'recommended_supplier' => $suppliers->first(),
            ];
        }
        return view('purchaseOrder.edit', compact('id', 'items', 'itemDemand', 'itemsWithSuppliers', 'allSuppliers'));
    }
    public function directCreate($id)
    {
        $this->authorize('purchaseOrder_create');

        $itemDemand = ItemDemand::with(['items', 'project'])->findOrFail($id);
        $item = $itemDemand->items->first();
        $suppliers = DB::table('item_supplier')
            ->join('suppliers', 'suppliers.id', '=', 'item_supplier.supplier_id')->where('item_supplier.item_id', $item->id)
            ->select('suppliers.id', 'suppliers.name', 'item_supplier.purchase_price')->get();
        // dd($suppliers);
        return view('purchaseOrder.directCreate', compact('itemDemand', 'item', 'suppliers'));
    }
    public function getItemSuppliers($itemId, $demandId)
    {
        $pivot = DB::table('item_demand_item')
            ->where('item_demand_id', $demandId)
            ->where('item_id', $itemId)
            ->first();
        $item = DB::table('items')->where('id', $itemId)->first();
        // Suppliers
        $suppliers = DB::table('item_supplier')
            ->join('suppliers', 'suppliers.id', '=', 'item_supplier.supplier_id')
            ->where('item_supplier.item_id', $itemId)
            ->select('suppliers.id', 'suppliers.name', 'item_supplier.purchase_price')
            ->get();
        // dd($suppliers);
        return response()->json([
            'unit'      => $item ? $item->deno : null,
            'qty'       => $pivot ? $pivot->item_qty : 0,
            'suppliers' => $suppliers,
        ]);
    }


    public function generatePO(Request $request, $id)
    {
        // dd($request->all());
        $this->authorize('purchaseOrder_create');

        $itemDemand = ItemDemand::findOrFail($id);

        $po = new PurchaseOrderDirect();
        $po->item_demand_id = $itemDemand->id;
        $po->supplier_id    = $request->supplier_id;
        $po->po_date        = now();
        $po->delivery_date  = $request->delivery_date;
        $po->status         = 'open';
        // $po->po_number      = $poNumber; // Auto-generated PO number
        // $po->mou_no         = $request->mou_no;
        // dd($po);
        $po->save();

        $rate = DB::table('item_supplier')
            ->where('item_id', $request->item_id)
            ->where('supplier_id', $request->supplier_id)
            ->value('purchase_price');

        $qty   = $request->qty;
        $total = $rate * $qty;

        $po->items()->create([
            'item_id' => $request->item_id,
            'qty'     => $qty,
            'rate'    => $rate,
            'unit'    => $request->unit,
            'total'   => $total,
        ]);
        return redirect()->route('po.invoice.single', $po->id)
            ->with('success', 'Purchase Order created successfully.');
    }
    public function listForMergePo()
    {
        $this->authorize('purchaseOrder_view');
        $purchaseOrders = PurchaseOrderDirect::with(['items.item', 'supplier', 'demand.contractors'])
            ->where('status', 'open')->get();
        // dd($purchaseOrders);
        return view('purchaseOrder.multiDemand', compact('purchaseOrders'));
    }
    // public function mergeSelectedPo(Request $request)
    // {
    //     $this->authorize('purchaseOrder_create');

    //     $selected = $request->input('selected_pos', []);

    //     if (empty($selected)) {
    //         return back()->with('error', 'Please select at least one PO.');
    //     }

    //     $groupedSuppliers = [];
    //     $relatedDemands   = [];
    //     $processedPoIds   = [];

    //     foreach ($selected as $sel) {
    //         [$poId, $itemId] = explode('-', $sel);

    //         $po = PurchaseOrderDirect::with('items')->find($poId);
    //         $item = $po->items->where('item_id', $itemId)->first();

    //         if ($item) {
    //             $supplierId = $po->supplier_id;

    //             if (!isset($groupedSuppliers[$supplierId])) {
    //                 $groupedSuppliers[$supplierId] = [];
    //                 $relatedDemands[$supplierId]   = [];
    //             }

    //             $groupedSuppliers[$supplierId][] = [
    //                 'item_demand_id' => $po->item_demand_id,
    //                 'item_id' => $itemId,
    //                 'qty'     => $item->qty,
    //                 'rate'    => $item->rate,
    //                 'unit'    => $item->unit,
    //                 'total'   => $item->total,
    //             ];

    //             if ($po->item_demand_id) {
    //                 $relatedDemands[$supplierId][] = $po->item_demand_id;
    //             }

    //             $processedPoIds[] = $poId;
    //         }
    //     }

    //     $lastPo = null;

    //     foreach ($groupedSuppliers as $supplierId => $items) {
    //         $po = new PurchaseOrderDirect();
    //         $po->supplier_id   = $supplierId;
    //         $po->po_date       = now();
    //         $po->delivery_date = now()->addDays(7);
    //         $po->status        = 'approved';
    //         $po->save();

    //         foreach ($items as $data) {
    //             $po->items()->create([
    //                 'item_demand_id' => $data['item_demand_id'],
    //                 'item_id' => $data['item_id'],
    //                 'qty'     => $data['qty'],
    //                 'rate'    => $data['rate'],
    //                 'unit'    => $data['unit'],
    //                 'total'   => $data['total'],
    //             ]);
    //         }

    //         if (!empty($relatedDemands[$supplierId])) {
    //             $po->demands()->attach(array_unique($relatedDemands[$supplierId]));
    //         }

    //         $lastPo = $po;
    //     }

    //     if (!empty($processedPoIds)) {
    //         PurchaseOrderDirect::whereIn('id', array_unique($processedPoIds))
    //             ->update(['status' => 'approved']);
    //     }

    //     return redirect()->route('marge.po.invoice', $lastPo->id)
    //         ->with('success', 'Purchase Order merged successfully and selected POs approved.');
    // }
    public function mergeSelectedPo(Request $request)
    {

        $this->authorize('purchaseOrder_create');

        $selected = $request->input('selected_pos', []);
        if (empty($selected)) {
            return back()->with('error', 'Please select at least one PO.');
        }

        $groupedSuppliers = [];
        $relatedDemands   = [];
        $processedPoIds   = [];

        foreach ($selected as $sel) {
            [$poId, $itemId] = explode('-', $sel);

            $po = PurchaseOrderDirect::with('items')->find($poId);
            $item = $po->items->where('item_id', $itemId)->first();

            if ($item) {
                $supplierId = $po->supplier_id;

                if (!isset($groupedSuppliers[$supplierId])) {
                    $groupedSuppliers[$supplierId] = [];
                    $relatedDemands[$supplierId]   = [];
                }

                $groupedSuppliers[$supplierId][] = [
                    'item_demand_id' => $po->item_demand_id,
                    'item_id' => $itemId,
                    'qty'     => $item->qty,
                    'rate'    => $item->rate,
                    'unit'    => $item->unit,
                    'total'   => $item->total,
                ];

                if ($po->item_demand_id) {
                    $relatedDemands[$supplierId][] = $po->item_demand_id;
                }

                $processedPoIds[] = $poId;
            }
        }

        $lastPo = null;

        foreach ($groupedSuppliers as $supplierId => $items) {
            $po = new PurchaseOrderDirect();
            $po->supplier_id   = $supplierId;
            $po->po_date       = now();
            $po->delivery_date = now()->addDays(7);
            $po->status        = 'approved';
            $po->merged_from_ids = json_encode(array_unique($processedPoIds)); // ✅ store merged source IDs
            $po->save();

            foreach ($items as $data) {
                $po->items()->create([
                    'item_demand_id' => $data['item_demand_id'],
                    'item_id' => $data['item_id'],
                    'qty'     => $data['qty'],
                    'rate'    => $data['rate'],
                    'unit'    => $data['unit'],
                    'total'   => $data['total'],
                ]);
            }

            if (!empty($relatedDemands[$supplierId])) {
                $po->demands()->attach(array_unique($relatedDemands[$supplierId]));
            }

            $lastPo = $po;
        }

        // Mark processed POs as approved
        if (!empty($processedPoIds)) {
            PurchaseOrderDirect::whereIn('id', array_unique($processedPoIds))
                ->update(['status' => 'approved']);
        }

          //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'purchaseOrder_create',                   
                    'PO',                      
                    'PO Created ',           
                    'PO has been created successfully and selected POs approved by '.auth()->user()->name,
                    route('itemDemand.index')          
                );
            //END======================================================================================


        return redirect()->route('marge.po.invoice', $lastPo->id)
            ->with('success', 'Purchase Order merged successfully and selected POs approved.');
    }

    public function margeInvoice($id)
    {
        $poItem = PurchaseOrderDirect::with([
            'items.item',
            'supplier',
            'demands.contractors' => function ($q) {
                $q->withPivot('item_id', 'item_qty');
            }
        ])->findOrFail($id);

        $contractorItems = collect();
        foreach ($poItem->items as $item) {
            foreach ($poItem->demands as $demand) {
                foreach ($demand->contractors as $contractor) {
                    if ($contractor->pivot && $contractor->pivot->item_id == $item->item_id) {
                        $contractorItems->push([
                            'item'        => $item->item->item ?? '-',
                            'deno'        => $item->unit ?? '-',
                            'size'        => $item->item->size ?? '-',
                            'contractor'  => $contractor->name,
                            'qty'         => $contractor->pivot->item_qty,
                            'delivery'    => $poItem->delivery_date ?? '-',
                        ]);
                    }
                }
            }
        }

        $contractorItems = $contractorItems->unique(function ($c) {
            return $c['contractor'] . '-' . $c['item'];
        })->values();

        $pdf = Pdf::loadView('purchaseOrder.margeInvoice', compact('poItem', 'contractorItems'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('PO-Invoice-' . $poItem->po_number . '.pdf');
    }
    public function poList()
    {
        $this->authorize('purchaseOrder_view');

        $purchaseOrders = PurchaseOrderDirect::with(['items'])->where('item_demand_id', null)->get();

        foreach ($purchaseOrders as $po) {
            $hasReceivedItems = $po->items->where('received_qty', '>', 0)->isNotEmpty();
            $hasApprovedItem = $po->items->where('status', 'approved')->isNotEmpty();
            $po->can_cancel = !$hasApprovedItem && !$hasReceivedItems && $po->status != 'cancelled';
        }

        return view('purchaseOrder.poList', compact('purchaseOrders'));
    }
    public function poCancel($id)
    {
        $mergedPo = PurchaseOrderDirect::findOrFail($id);

        $mergedPo->update(['status' => 'cancelled']);

        PurchaseOrderDirectItem::where('purchase_order_direct_id', $id)->update(['status' => 'rejected']);
        if ($mergedPo->merged_from_ids) {
            $originalPoIds = json_decode($mergedPo->merged_from_ids, true);

            if (!empty($originalPoIds)) {
                PurchaseOrderDirect::whereIn('id', $originalPoIds)
                    ->update(['status' => 'open']);
            }
        }

        return back()->with('success', 'Merged PO cancelled successfully, related items rejected and original POs reopened.');
    }


    public function poViewList($id)
    {
        $this->authorize('purchaseOrder_items_list');
        $purchaseOrders = PurchaseOrderDirect::with(['items.item','items.itemDemand.contractors'])->find($id);
        // dd($purchaseOrders);

        return view('purchaseOrder.poViewList', compact('purchaseOrders'));
    }
    public function receiveItem(Request $request, $id)
    {

        $formType = $request->input('form_type');

        if ($formType === 'receive') {
            // ================== RECEIVE ITEM ==================
            $request->validate([
                'received_qty' => 'required|numeric|min:1',
                'dc_number' => 'required|string|max:255',
                'dc_copy' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            ]);

            $poItem = DB::table('purchase_order_direct_items')->where('id', $id)->first();

            if (!$poItem) {
                return back()->with('error', 'PO Item not found.');
            }

            $dcCopyPath = null;
            if ($request->hasFile('dc_copy')) {
                $dcCopyPath = $request->file('dc_copy')->store('dc_copies', 'public');
            }

            DB::table('purchase_order_direct_items')
                ->where('id', $id)
                ->update([
                    'status' => 'approved',
                    'received_qty' => $request->received_qty,
                    'dc_number' => $request->dc_number,
                    'dc_copy' => $dcCopyPath,
                    'updated_at' => now(),
                ]);

                $po = PurchaseOrderDirect::with(['demands.project','demands.contractors',
                'items.itemDemand'])->find($poItem->purchase_order_direct_id);

                $relatedDemand = $po?->demands?->first();
                $projectId = $relatedDemand->project_id ?? null;
                $contractorId = $relatedDemand->contractors->first()->id ?? null;
                $houseTypeId = $relatedDemand->house_type_id ?? null;

                DB::table('stocks')->insert([
                    'item_id' => $poItem->item_id,
                    'warehouse_id' => $request->warehouse_id ?? 1,
                    'project_id' => $projectId,
                    'contractor_id' => $contractorId,
                    'house_type_id' => $houseTypeId,
                    'purchase_order_number' => $poItem->purchase_order_direct_id,
                    'challan_number' => $request->dc_number,
                    'quantity' => $request->received_qty,
                    'price' => $poItem->rate,
                    'total' => $poItem->rate * $request->received_qty,
                    'type' => 'in',
                    'date' => now(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

             //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'purchaseOrder_view',                   
                    'Item Demand Received',                      
                    'Item Demand received successfully',           
                    'Item Demand "' . $request->received_qty . '" received successfully',
                    route('itemDemand.index')          
                );
            //END======================================================================================



            return back()->with('success', 'Item received and stock updated successfully.');

        } elseif ($formType === 'po_upload') {
            // ================== UPLOAD PO ==================
            $request->validate([
                'po_date' => 'required|date',
                'po_copy' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            ]);

            $poCopyPath = null;
            if ($request->hasFile('po_copy')) {
                $poCopyPath = $request->file('po_copy')->store('po_copies', 'public');
            }

            DB::table('purchase_order_direct_items')
                ->where('id', $id)
                ->update([
                    'po_date' => $request->po_date,
                    'po_copy' => $poCopyPath,
                    'updated_at' => now(),
                ]);

            
            //SEND NOTIFICATION CODE START FROM HERE==================================================
                sendPermissionNotification(
                    'purchaseOrder_view',                   
                    'PO Uploaded',                      
                    'PO Uploaded successfully',           
                    'Item Demand "' . $request->received_qty . '" received successfully',
                    route('itemDemand.index')          
                );
            //END======================================================================================


            return back()->with('success', 'PO uploaded successfully.');
        }

        return back()->with('error', 'Invalid form type.');
    }
    public function generateInvoiceSingle($id)
    {
        $poItem = PurchaseOrderDirect::with(['items.item', 'supplier', 'demand.contractors'])->findOrFail($id);
        // $poItem = PurchaseOrderDirect::with(['items.item','supplier','items.itemDemand.contractors'])->find($id);

        $contractors = $poItem->demand->contractors ?? collect();
        // dd($poItem);
        $pdf = Pdf::loadView('purchaseOrder.singleInvoice', compact('poItem', 'contractors'))->setPaper('A4', 'portrait');

        return $pdf->stream('PO-Invoice-' . $poItem->po_number . '.pdf');
    }
     public function generateInvoice($id)
    {
        $poItem = \App\Models\PurchaseOrderDirectItem::with([
            'purchaseOrder.supplier',
            'purchaseOrder.items.item',
            'itemDemand.contractors'
        ])->findOrFail($id);

        // Related PO record
        $purchaseOrder = $poItem->purchaseOrder;

        // Contractor data (itemDemand table ke through)
        $contractors = $poItem->itemDemand && $poItem->itemDemand->contractors
            ? $poItem->itemDemand->contractors->pluck('name')->unique()->join(', ')
            : 'N/A';

        $contractorItems = collect();

        if ($poItem->itemDemand && $poItem->itemDemand->contractors) {
            foreach ($poItem->itemDemand->contractors as $contractor) {
                $contractorItems->push([
                    'item'        => $poItem->item->item ?? '-',
                    'deno'        => $poItem->unit ?? '-',
                    'size'        => $poItem->item->size ?? '-',
                    'contractor'  => $contractor->name ?? '-',
                    'qty'         => $poItem->qty ?? '-',
                    'delivery'    => $purchaseOrder->delivery_date ?? '-',
                ]);
            }
        }

        // Remove duplicates
        $contractorItems = $contractorItems->unique(function ($c) {
            return $c['contractor'] . '-' . $c['item'];
        })->values();

        // PDF generation
        $pdf = Pdf::loadView('purchaseOrder.invoice', [
            'poItem'          => $poItem,
            'purchaseOrder'   => $purchaseOrder,
            'contractorItems' => $contractorItems,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('PO-Invoice-' . $purchaseOrder->po_number . '.pdf');
    //     return $pdf->stream('PO-Invoice-' . $poItem->po_number . '.pdf');

    }

    // public function generateInvoice($id)
    // {
    //     $poItem = PurchaseOrderDirect::with([
    //         'items.item','supplier','demands.contractors' => function ($q) {
    //         $q->withPivot('item_id', 'item_qty');
    //     }
    //     ])->findOrFail($id);
    //     dd($poItem);
    // $contractorItems = collect();
    // foreach ($poItem->items as $item) {
    //     foreach ($poItem->demands as $demand) {
    //         foreach ($demand->contractors as $contractor) {
    //             if ($contractor->pivot && $contractor->pivot->item_id == $item->item_id) {
    //                 $contractorItems->push([
    //                     'item'        => $item->item->item ?? '-',
    //                     'deno'        => $item->unit ?? '-',
    //                     'size'        => $item->item->size ?? '-',
    //                     'contractor'  => $contractor->name,
    //                     'qty'         => $contractor->pivot->item_qty,
    //                     'delivery'    => $poItem->delivery_date ?? '-',
    //                 ]);
    //             }
    //         }
    //     }
    // }
    // $contractorItems = $contractorItems->unique(function ($c) {
    //     return $c['contractor'] . '-' . $c['item'];
    // })->values();
    // $pdf = Pdf::loadView('purchaseOrder.invoice', compact('poItem', 'contractorItems'))->setPaper('A4', 'portrait');
    //     return $pdf->stream('PO-Invoice-' . $poItem->po_number . '.pdf');
    // }
}
