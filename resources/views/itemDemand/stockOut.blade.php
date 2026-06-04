@extends('layout.master')
@section('title', 'Stock Out')
@section('header-title', 'Stock Out')
@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Stock Out Table -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    @can('stockOut_create')
                    <button id="showFormBtn" class="btn btn-primary">+ Add Stock Out</button>
                    @endcan
                    @can('stockout_trash_view')
                    <a href="{{ route('stock.out.trash') }}" class="btn btn-danger d-flex align-items-center gap-2 ms-auto" title="Deleted Users">
                        <i class="bi bi-trash-fill"></i>
                        <span>Trash</span>
                        <span class="badge bg-light text-dark">{{ $trashstockout ?? 0 }}</span>
                    </a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped">
                        <thead>
                            <tr>
                                <th>S.NO</th>
                                <th>Date</th>
                                {{-- <th>Item</th> --}}
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stocks as $key=> $stock)
                            <tr>
                                <td>{{ $key +1 }}</td>
                                <td>{{ $stock->date }}</td>
                                {{-- <td>{{ $stock->product->name ?? 'N/A' }}</td> --}}
                                <td>{{ $stock->price ?? '—' }}</td>
                                <td>{{ $stock->quantity ?? 'N/A' }}</td>
                                <td>{{ $stock->creator->name ?? 'No Creator' }}</td>
                                <td>
                                    @can('stockOut_edit')
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $stock->id }}">
                                        Edit
                                    </button>
                                    @endcan
                                     @can('stockOut_trash')
                                            <form action="{{ route('stock.out.delete', $stock->id) }}" method="POST"
                                                style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this stockout?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- EDIT MODALS --}}
            @foreach ($stocks as $stock)
            <div class="modal fade" id="editModal{{ $stock->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('stock.out.update', $stock->id) }}" class="modal-content">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Stock Out #{{ $stock->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" value="{{ $stock->date }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Warehouse</label>
                                <select name="warehouse_id" class="form-control" required>
                                    <option value="">-- Select Warehouse --</option>
                                    @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ $stock->warehouse_id == ''.$warehouse->id.'' ? 'selected' : '' }}> {{ $warehouse->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Item</label>
                                <select name="item_id" class="form-control" required>
                                    <option value="">-- Select Item --</option>
                                    @foreach ($items as $item)
                                    <option value="{{ $item->id }}" {{ $stock->item_id == ''.$item->id.'' ? 'selected' : '' }}> {{ $item->item }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Price</label>
                                <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ $stock->price }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Quantity</label>
                                <input type="number" name="quantity" class="form-control" min="0" step="0.01" value="{{ $stock->quantity }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Add Stock Out Form -->
        <div class="col-md-4">
            <div class="card" id="formContainer" style="display:none;">
                <div class="card-header">
                    <h5>Add Stock Out</h5>
                </div>
                <form method="POST" action="{{ route('stock.out.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Warehouse</label>
                                <select name="warehouse_id" class="form-control" required>
                                    <option value="">-- Select Warehouse --</option>
                                    @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}"> {{ $warehouse->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Item</label>
                                <select name="item_id" class="form-control" required>
                                    <option value="">-- Select Item --</option>
                                    @foreach ($items as $item)
                                    <option value="{{ $item->id }}"> {{ $item->item }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Price</label>
                                <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label>Quantity</label>
                                <input type="number" name="quantity" class="form-control" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Add Stock Out</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('showFormBtn').addEventListener('click', function() {
        document.getElementById('formContainer').style.display = 'block';
    });

</script>
@endsection
