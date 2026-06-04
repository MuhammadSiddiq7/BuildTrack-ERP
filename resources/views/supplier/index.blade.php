@extends('layout.master')
@section('title', 'Supplier')
@section('header-title', 'Supplier')

@section('content')
    <style>
        .table-actions a,
        .table-actions form {
            display: inline-block;
            margin-right: 5px;
        }

        .badge-status {
            padding: 0.4em 0.8em;
            font-size: 0.75rem;
            border-radius: 1rem;
        }

        .card-modern {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f3f9;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card card-modern">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-people-fill me-2 text-primary"></i>Suppliers List
                    </h5>

                    <div class="d-flex align-items-center gap-2">
                        @can('supplier_create')
                            <a href="{{ route('supplier.create') }}"  class="btn btn-sm btn-success">
                                <i class="bi bi-plus-circle me-1"></i> Create Supplier
                            </a>
                        @endcan
                        @can('supplier_trash_view')
                            <a href="{{ route('supplier.trash') }}" class="btn btn-sm btn-outline-danger position-relative"
                                title="Deleted Suppliers">
                                 <i class="bi bi-trash-fill me-1"></i>Trash
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $trashuser ?? 0 }}</span>
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatables-reponsive" class="table table-striped table-hover text-center"
                            style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-hash"></i> S:NO</th>
                                    <th><i class="bi bi-person-lines-fill"></i> Name</th>
                                    <th><i class="bi bi-geo-alt-fill"></i> Address</th>
                                    <th><i class="bi bi-card-list"></i> NTN</th>
                                    <th><i class="bi bi-telephone-fill"></i> Contact Number</th>
                                    <th><i class="bi bi-toggle-on"></i> Status</th>
                                    <th><i class="bi bi-gear-fill"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $supplier->name ?? 'N/A' }}</td>
                                        <td>{{ $supplier->address ?? 'N/A' }}</td>
                                        <td>{{ $supplier->ntn ?? 'N/A' }}</td>
                                        <td>{{ $supplier->contact ?? 'N/A' }}</td>
                                        <td>
                                            @if ($supplier->status == 'active')
                                                <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>
                                                    Active</span>
                                            @else
                                               <span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i>
                                                    Inactive</span>
                                            @endif
                                        </td>
                                        <td class="table-actions">
                                            @can('supplier_edit')
                                                <a href="{{ route('supplier.assignItem', $supplier->id) }}"
                                                    class="btn btn-sm btn-success btn-icon" title="Assign Items">
                                                    <i class="bi bi-box-arrow-up-right"></i> Assign
                                                </a>
                                            @endcan

                                            @can('supplier_edit')
                                                <a href="{{ route('supplier.edit', $supplier->id) }}"
                                                    class="btn btn-sm btn-primary btn-icon" title="Edit Supplier">
                                                    <i class="bi bi-pencil-square"></i> 
                                                </a>
                                            @endcan

                                            @can('supplier_trash')
                                                <form action="{{ route('supplier.delete', $supplier->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger btn-icon"
                                                        title="Delete Supplier">
                                                        <i class="bi bi-trash3"></i> 
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- table-responsive -->
                </div> <!-- card-body -->
            </div> <!-- card -->
        </div>
    </div>
@endsection
