@extends('layout.master')
@section('title', 'Stock Out')
@section('header-title', 'Stock Out')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-box-arrow-up me-2 text-primary"></i>Stock Out Records
                        </h5>
                        <div class="d-flex gap-2">
                            @can('stockOut_create')
                                <button class="btn btn-sm btn-success" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#addStockOutForm" aria-expanded="false" aria-controls="addStockOutForm">
                                    <i class="bi bi-plus-circle me-1"></i> Add Stock Out
                                </button>
                            @endcan
                            @can('stockout_trash_view')
                                <a href="{{ route('stock.out.trash') }}"
                                    class="btn btn-outline-danger d-flex align-items-center">
                                    <i class="bi bi-trash-fill me-1"></i> Trash
                                    <span class="badge bg-danger text-white ms-2">
                                        {{ $trashstockout ?? 0 }}</span>
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatables-reponsive" class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-hash"></i> S.NO</th>
                                        <th><i class="bi bi-calendar3"></i> Date</th>
                                        <th><i class="bi bi-box"></i> Item</th>
                                        <th><i class="bi bi-person-badge"></i> Contractor</th>
                                        {{-- <th><i class="bi bi-currency-dollar"></i> Price</th> --}}
                                        <th><i class="bi bi-123"></i> Quantity</th>
                                        <th><i class="bi bi-person-circle"></i> Created By</th>
                                        <th><i class="bi bi-gear-fill"></i> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stocks as $i => $stock)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $stock->date }}</td>
                                            <td>{{ $stock->item->item ?? 'N/A' }}</td>
                                            <td>{{ $stock->contractor->name ?? 'N/A' }}</td>
                                            {{-- <td>{{ $stock->price ?? '—' }}</td> --}}
                                            <td>{{ $stock->quantity }}</td>
                                            <td>{{ $stock->creator->name ?? 'No Creator' }}</td>
                                            <td class="d-flex justify-content-center gap-1 flex-wrap">
                                                @can('stockOut_edit')
                                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $stock->id }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                @endcan
                                                @can('stockOut_trash')
                                                    <form action="{{ route('stock.out.delete', $stock->id) }}" method="POST"
                                                        onsubmit="return confirm('Delete this entry?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can('stockOut_create')
                <div class="col-12">
                    <div class="collapse" id="addStockOutForm">
                        <div class="card shadow-sm rounded-3">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Add Stock Out</h5>
                            </div>
                            <form method="POST" action="{{ route('stock.out.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Date</label>
                                            <input type="date" name="date" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Warehouse</label>
                                            <select name="warehouse_id" class="form-select" required>
                                                <option value="">Select Warehouse</option>
                                                @foreach ($warehouses as $w)
                                                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Contractor</label>
                                            <select name="contractor_id" class="form-select" required>
                                                <option value="">Select Contractor</option>
                                                @foreach ($contractors as $c)
                                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>House Type</label>
                                            <select name="house_type_id" id="houseTypeSelectOut" class="form-select" required>
                                                <option value="" disabled selected>Select House Type</option>
                                                <option value="ATH">ATH</option>
                                                <option value="BTH">BTH</option>
                                                <option value="CTH">CTH</option>
                                                <option value="DTH">DTH</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Item </label>
                                            <select name="item_id" id="itemSelectOut" class="form-select" required>
                                                <option value="" disabled selected>Select Item</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Price</label>
                                            <input type="number" name="price" class="form-control" step="0.01" step="any">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Delivery Challan Number</label>
                                            <input type="number" name="challan_number" class="form-control" step="any">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Purchase Order Number</label>
                                            <input type="number" name="purchase_order_number" class="form-control" step="any">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Demand Number</label>
                                            <input type="number" name="demand_number" class="form-control" step="any">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" name="quantity" class="form-control" min="0" step="0.01" step="any" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Comment / Feedback</label>
                                            <textarea name="comment" class="form-control" ></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan

            {{-- Edit Modals --}}
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
                                    <label class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control"
                                        value="{{ $stock->date }}" required>
                                </div>



                                <div class="mb-3">
                                    <label class="form-label">Warehouse</label>
                                    <select name="warehouse_id" class="form-select" required>
                                        <option value="">Select Warehouse</option>
                                        @foreach ($warehouses as $w)
                                            <option value="{{ $w->id }}"
                                                {{ $stock->warehouse_id == $w->id ? 'selected' : '' }}>
                                                {{ $w->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contractor</label>
                                    <select name="contractor_id" class="form-select" required>
                                        <option value="">Select Contractor</option>
                                        @foreach ($contractors as $c)
                                            <option value="{{ $c->id }}"
                                                {{ $stock->contractor_id == $c->id ? 'selected' : '' }}>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Item</label>
                                    <select name="item_id" class="form-select" required>
                                        <option value="">Select Item</option>
                                        @foreach ($items as $it)
                                            <option value="{{ $it->id }}"
                                                {{ $stock->item_id == $it->id ? 'selected' : '' }}>
                                                {{ $it->item }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="number" name="price" class="form-control" step="0.01"
                                        value="{{ $stock->price }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" min="0" step="0.01"
                                        value="{{ $stock->quantity }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    {{-- <script>
        document.getElementById('houseTypeSelect').addEventListener('change', function() {
            let houseType = this.value;

            fetch(`/stock/get-items/${houseType}`)
                .then(response => response.json())
                .then(data => {
                    let itemSelect = document.getElementById('itemSelect');
                    itemSelect.innerHTML = '<option value="" disabled selected>Select Item</option>';

                    data.forEach(item => {
                        let option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.item; // 👈 yahan "item" column use karein
                        itemSelect.appendChild(option);
                    });
                });
        });
    </script> --}}

<script>
document.getElementById('houseTypeSelectOut').addEventListener('change', function() {
    let houseType = this.value;

    fetch(`/stock/get-items/${houseType}`)
        .then(response => response.json())
        .then(data => {
            let itemSelect = document.getElementById('itemSelectOut');
            itemSelect.innerHTML = '<option value="" disabled selected>Select Item</option>';

            data.forEach(item => {
                let option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.item;
                itemSelect.appendChild(option);
            });
        });
});
</script>


@endsection
