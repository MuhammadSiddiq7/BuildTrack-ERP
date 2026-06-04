@extends('layout.master')
@section('title', 'Purchase Order')
@section('header-title', 'Purchase Order')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.NO #</th>
                            <th>PO #</th>
                            <th>Delivery Date</th>
                            <th>Supplier</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrders as $po)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $po->po_number }}</td>
                            <td>{{ $po->delivery_date }}</td>
                            <td>{{ $po->supplier->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('po.view.list', $po->id) }}" class="btn btn-sm btn-primary">View</a>

                                @if ($po->status === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @elseif (!$po->can_cancel)
                                    <span class="badge bg-warning text-dark"></span>
                                @else
                                    @can('purchaseOrder_view_button')
                                        <a href="{{ route('po.cancel', $po->id) }}" class="btn btn-sm btn-danger">Cancel</a>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
