@extends('layout.master')
@section('title', 'Purchase Order')
@section('header-title', 'Purchase Order')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{-- @can('project_create')
                        <a href="{{ route('purchaseOrder.create') }}" class="btn btn-primary">Create PO</a>
                    @endcan --}}

                    @can('purchaseOrder_trash_view')
                        <a href="{{ route('purchaseOrder.trash') }}" class="btn btn-danger d-flex align-items-center gap-2 ms-auto"
                            title="Deleted Users">
                            <i class="bi bi-trash-fill"></i>
                            <span>Trash</span>
                            <span class="badge bg-light text-dark">{{ $trashPurchaseOrder ?? 0 }}</span>
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S:NO</th>
                                <th>PO Number</th>
                                <th>PO Date</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseOrders as $purchaseOrder)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $purchaseOrder->po_number ?? 'N/A' }}</td>
                                    <td>{{ $purchaseOrder->po_date ?? 'N/A' }}</td>
                                    <td>{{ $purchaseOrder->grand_total ?? 'N/A' }}</td>
                                    <td>{{ $purchaseOrder->approve_status ?? 'N/A' }}</td>
                                    <td>{{ $purchaseOrder->creator->name ?? 'N/A' }}</td>
                                    {{-- <td>
                                        @if ($purchaseOrder->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td> --}}
                                    <td>
                                        {{-- @can('purchaseOrder_edit') --}}
                                            <a class="btn btn-sm btn-primary">PDF</a>
                                        {{-- @endcan --}}
                                        {{-- @can('purchaseOrder_edit')
                                            <a href="{{ route('purchaseOrder.edit', $purchaseOrder->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                        @endcan
                                        @can('purchaseOrder_trash')
                                            <form action="{{ route('purchaseOrder.delete', $purchaseOrder->id) }}" method="POST"
                                                style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this purchase order?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endcan --}}
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
