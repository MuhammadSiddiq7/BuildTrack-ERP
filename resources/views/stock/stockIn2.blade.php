@extends('layout.master')
@section('title', 'Stock In')
@section('header-title', 'Stock In')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Full Width Column -->
            <div class="col-12">
                <div class="card shadow-sm rounded-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-box-arrow-in-down me-2 text-primary"></i>Stock In List
                        </h5>

                        <div class="d-flex gap-2">
                            @can('stock_create')
                                <button id="showFormBtn" class="btn btn-sm btn-success">
                                    <i class="bi bi-plus-circle me-1"></i> Add Stock In
                                </button>
                            @endcan

                            @can('stock_trash_view')
                                <a href="{{ route('stock.trash') }}" class="btn btn-sm btn-outline-danger position-relative"
                                    title="Trash">
                                    <i class="bi bi-trash-fill me-1"></i> Trash
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $trashstock ?? 0 }}</span>
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatables-reponsive" class="table table-hover text-nowrap align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-hash"></i> S.NO</th>
                                        <th><i class="bi bi-calendar"></i> Date</th>
                                        <th><i class="bi bi-box"></i> Item</th>
                                        <th><i class="bi bi-currency-dollar"></i> Price</th>
                                        <th><i class="bi bi-123"></i> Quantity</th>
                                        <th><i class="bi bi-person-circle"></i> Created By</th>
                                        <th><i class="bi bi-gear"></i> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stocks as $key => $stock)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $stock->date }}</td>
                                            <td>{{ $stock->item->item ?? 'N/A' }}</td>
                                            <td>{{ $stock->price ?? '—' }}</td>
                                            <td>{{ $stock->quantity }}</td>
                                            <td>{{ $stock->creator->name ?? 'No Creator' }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    @can('stock_edit')
                                                        <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                                            data-bs-target="#editModal{{ $stock->id }}">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                    @endcan
                                                    @can('stock_trash')
                                                        <form action="{{ route('stock.delete', $stock->id) }}" method="POST"
                                                            onsubmit="return confirm('Are you sure?');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="bi bi-trash"></i>
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

                        <!-- Form: Add Stock In -->
                        <div class="card mt-4 shadow-sm" id="formContainer" style="display:none;">
                            <div class="card-header">
                                <h5><i class="bi bi-plus-circle me-1 text-primary"></i> Add Stock In</h5>
                            </div>
                            <form method="POST" action="{{ route('stock.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label>Date</label>
                                            <input type="date" name="date" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Warehouse</label>
                                            <select name="warehouse_id" class="form-control" required>
                                                <option value="">-- Select Warehouse --</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">House Type <span
                                                    class="text-danger">*</span></label>
                                            <select name="house_type_id" id="houseTypeSelect" class="form-select">
                                                <option value="" disabled selected>Select House Type</option>
                                                <option value="ATH">ATH</option>
                                                <option value="BTH">BTH</option>
                                                <option value="CTH">CTH</option>
                                                <option value="DTH">DTH</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Item</label>
                                            <select name="item_id"id="itemSelect" class="form-control" required>
                                                <option value="">-- Select Item --</option>
                                                @foreach ($items as $item)
                                                    <option value="{{ $item->id }}">{{ $item->item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Price</label>
                                            <input type="number" name="price" class="form-control" step="0.01"
                                                step="any">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Delivery Challan Number</label>
                                            <input type="number" name="challan_number" class="form-control" step="any">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Purchase Order Number</label>
                                            <input type="number" name="purchase_order_number" class="form-control"
                                                step="any">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Demand Number</label>
                                            <input type="number" name="demand_number" class="form-control" step="any">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Quantity</label>
                                            <input type="number" name="quantity" class="form-control" step="any"
                                                required>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Comment/Feedback</label>
                                            <textarea name="comment" class="form-control" ></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="bi bi-check-circle me-1"></i>
                                        Add</button>
                                </div>
                            </form>
                        </div>

                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col-12 -->
        </div>
    </div>

    <script>
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
    </script>


    <script>
        document.getElementById('showFormBtn').addEventListener('click', function() {
            document.getElementById('formContainer').style.display = 'block';
            window.scrollTo({
                top: document.getElementById('formContainer').offsetTop - 100,
                behavior: 'smooth'
            });
        });
    </script>


@endsection
