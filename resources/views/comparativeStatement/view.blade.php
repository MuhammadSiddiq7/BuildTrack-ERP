@extends('layout.master')
@section('title', 'View Comparative Statement')
@section('header-title', 'View Comparative Statement')

@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h4 class="mb-0">Comparative Statement</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive rounded shadow-sm mt-3">
                        <table id="datatables-reponsive" class="table table-hover align-middle text-center border">
                            <thead class="bg-light text-dark fw-bold border-bottom">
                                <tr>
                                    {{-- <th class="text-uppercase small">House Number</th> --}}
                                    <th class="text-uppercase small">Item Name</th>
                                    <th class="text-uppercase small">Size</th>
                                    <th class="text-uppercase small">Allocated Quantity</th>
                                    <th class="text-uppercase small">Demand Quantity</th>
                                    {{-- <th class="text-uppercase small">Over & Above Qty</th> --}}
                                    <th class="text-uppercase small">Contractor</th>
                                    <th class="text-uppercase small">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
    @if($itemDemand && $itemDemand->items)
        @foreach($itemDemand->items as $item)
            @php
                $houseProject = \App\Models\HouseProject::find($item->pivot->house_project_id);
                $contractor = \App\Models\Contractor::find($item->pivot->contractor_id);
                $allocatedQty = $item->projects->first()?->pivot?->quantity ?? 0;
                $hasActivePO = \App\Models\PurchaseOrderItem::where('item_id', $item->id)
                    ->where('contractor_id', $item->pivot->contractor_id)
                    ->whereHas('purchaseOrder', function($q) {
                        $q->where('status', 'active');
                    })->exists();
            @endphp
            <tr>
                <td>{{ $item->item ?? 'N/A' }}</td>
                <td>{{ $item->size ?? 'N/A' }}</td>
                <td>{{ $allocatedQty ?? 0 }}</td>
                <td>{{ $item->pivot->item_qty ?? 0 }}</td>
                <td>{{ $contractor?->name ?? 'N/A' }}</td>
                <td>
                    @if($hasActivePO)
                        <a href="{{ route('purchaseOrder.invoice', ['item_id' => $item->id, 'contractor_id' => $item->pivot->contractor_id]) }}" class="btn btn-sm btn-success"><b>Invoice</b></a>
                    @endif
                    <a href="{{ route('comparativeStatement.view.po', [
                        'item_id' => $item->id,
                        'item_demand_id' => $itemDemand->id,
                        'supplier_id' => $item->pivot->supplier_id ?? null,
                        'contractor_id' => $item->pivot->contractor_id
                    ]) }}" class="btn btn-sm btn-primary"><b>View</b></a>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" class="text-center text-danger">No Items Found</td>
        </tr>
    @endif
</tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
