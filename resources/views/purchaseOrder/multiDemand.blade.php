@extends('layout.master')
@section('title', 'Purchase Order')
@section('header-title', 'Purchase Order')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


               <form action="{{ route('purchaseOrder.mergeSelected') }}" method="POST">
        @csrf

        <table id="datatables-reponsive" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>PO #</th>
                    <th>Demand Number</th> #</th>
                    <th>Supplier</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrders as $po)
                    @foreach($po->items as $poItem)
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_pos[]" value="{{ $po->id }}-{{ $poItem->item_id }}">
                            </td>
                            <td>{{ $po->po_number }}</td>
                            <td>{{ $po->demand->demand_no ?? 'N/A' }}</td>
                            <td>{{ $po->supplier->name ?? 'N/A' }}</td>
                            <td>{{ $poItem->item->item }}</td>
                            <td>{{ $poItem->qty }}</td>
                            <td>{{ $poItem->rate }}</td>
                            <td>{{ $poItem->total }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Genrate PO</button>
    </form>
            </div>
        </div>
    </div>
</div>
@endsection
