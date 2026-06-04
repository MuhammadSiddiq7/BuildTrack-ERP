@extends('layout.master')
@section('title', 'Warehouse')
@section('header-title', 'Warehouses')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow rounded-3">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2 text-primary"></i>Warehouse List</h5>

                    <div class="d-flex gap-2">
                        @can('warehouse_create')
                            <a href="{{ route('warehouse.create') }}" class="btn btn-sm btn-success">
                                <i class="bi bi-plus-circle me-1"></i> Create Warehouse
                            </a>
                        @endcan

                        @can('warehouse_trash_view')
                            <a href="{{ route('warehouse.trash') }}"
                               class="btn btn-sm btn-outline-danger position-relative"
                                title="Deleted Warehouses">
                                 <i class="bi bi-trash-fill me-1"></i>Trash
                                <span class="badge bg-danger text-white ms-2">{{ $trashwarehouse ?? 0 }}</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatables-reponsive" class="table table-hover align-middle text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-hash"></i> S.NO</th>
                                    <th><i class="bi bi-building"></i> Warehouse Name</th>
                                    <th><i class="bi bi-geo-alt"></i> Address</th>
                                    <th><i class="bi bi-toggle-on"></i> Status</th>
                                    <th><i class="bi bi-gear"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($warehouses as $warehouse)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $warehouse->name ?? 'N/A' }}</td>
                                        <td>{{ $warehouse->address ?? 'N/A' }}</td>
                                        <td>
                                            @if ($warehouse->status == 'active')
                                                <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>
                                                    Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="bi bi-x-circle me-1"></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @can('warehouse_edit')
                                                    <a href="{{ route('warehouse.edit', $warehouse->id) }}"
                                                        class="btn btn-sm btn-primary me-1" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                @endcan
                                                @can('warehouse_trash')
                                                    <form action="{{ route('warehouse.delete', $warehouse->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this warehouse?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="bi bi-trash3-fill"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> 
                </div> 
            </div>
        </div>
    </div>
@endsection
