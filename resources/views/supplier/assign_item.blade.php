@extends('layout.master')
@section('title', 'Assign Item')
@section('header-title', 'Assign Item')
@section('content')


    <style>
        .assign-header {
            background: linear-gradient(to right, #153d77, #1e4e9c);
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            color: white;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .supplier-name-box {
            background: white;
            color: #153d77;
            font-weight: 600;
            padding: 0.4rem 1rem;
            border-radius: 25px;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-weight: 600;
            font-size: 1.1rem;
            border-bottom: 2px solid #153d77;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            color: #153d77;
        }

        .btn-theme {
            background-color: #153d77;
            color: white;
            border: none;
        }

        .btn-theme:hover {
            background-color: #0f2b50;
            color: white;
        }

        table th {
            background-color: #f1f5f9;
            font-weight: 600;
        }

        table td,
        table th {
            vertical-align: middle !important;
        }

        .form-control {
            height: 32px;
            font-size: 0.9rem;
        }

        .btn {
            font-size: 0.875rem;
        }
    </style>


    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="container-fluid">
                        <!-- Header -->
                        <div class="assign-header">
                            <h5 class="mb-0" style="color: white;">📦 Assign Items</h5>
                            <div class="supplier-name-box">Supplier: {{ $supplier->name }}</div>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('supplier.storeAssignedItems', $supplier->id) }}" method="POST"
                            id="itemAssignForm">
                            @csrf
                            <div class="row">
                                <!-- Available Items -->
                                <div class="col-lg-12 mb-4">
                                    <div class="section-title">🟢 Available Items</div>
                                    <button type="button" class="btn btn-sm btn-theme mb-2"
                                        onclick="selectAll('available')">Select All</button>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="datatables-reponsive-supplier">
                                            <thead>
                                                <tr>
                                                    <th>Select</th>
                                                    <th>Item</th>
                                                    <th>Type</th>
                                                    <th>Size</th>
                                                    <th>Rate</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($allItems as $item)
                                                    @if (!in_array($item->id, $assignedItem))
                                                        <tr data-id="{{ $item->id }}">
                                                            <td>
                                                                <input type="checkbox" class="available-checkbox"
                                                                    name="item_ids[]" value="{{ $item->id }}">
                                                            </td>
                                                            <td>{{ $item->item }}</td>
                                                            <td>{{ $item->items_type }}</td>
                                                            <td>{{ $item->size }}</td>
                                                            <td>
                                                                <input type="number" min="0" step="0.01" step="1"
                                                                    class="form-control rate-input"
                                                                    name="purchase_price[{{ $item->id }}]">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control remarks-input"
                                                                    name="remarks[{{ $item->id }}]">
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="submit" class="btn btn-success mt-2" onclick="assignSelected()">✅
                                        Assign</button>
                                </div>

                                <!-- Assigned Items -->
                                <div class="col-lg-12">
                                    <div class="section-title">🔴 Assigned Items</div>
                                    <button type="button" class="btn btn-sm btn-theme mb-2"
                                        onclick="selectAll('assigned')">Select All</button>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered assignedTable" id="datatables-reponsive-unassigned">
                                            <thead>
                                                <tr>
                                                    <th>Select</th>
                                                    <th>Item</th>
                                                    <th>Size</th>
                                                    <th>Rate</th>
                                                    <th>Remarks</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($supplier->items as $item)
                                                    <tr data-id="{{ $item->id }}">
                                                        <td><input type="checkbox" class="assigned-checkbox" checked></td>
                                                        <td>{{ $item->item }}</td>
                                                        <td>{{ $item->size }}</td>
                                                        <td><input disabled class="form-control assigned-price"
                                                                value="{{ $item->pivot->purchase_price }}"></td>
                                                        <td><input disabled class="form-control assigned-remarks"
                                                                value="{{ $item->pivot->remarks }}"></td>
                                                        <td>{{ $item->pivot->date }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- <button type="button" class="btn btn-danger mt-2" onclick="unassignSelected()">❌
                                        Unassign</button>
                                </div> --}}
                                    <button type="button" class="btn btn-danger mt-2"
                                        onclick="unassignSelected('{{ route('supplier.unassignItems', $supplier->id) }}')">
                                        ❌ Unassign </button>
                                </div>
                        </form>
                        <!-- End form -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive-supplier").DataTable({
                pageLength: 20
                , lengthMenu: [
                    [20, 40, 60, 80, 100]
                    , [20, 40, 60, 80, 100]
                ]
                , responsive: true
            });
        });

    </script>
     <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive-unassigned").DataTable({
                pageLength: 20
                , lengthMenu: [
                    [20, 40, 60, 80, 100]
                    , [20, 40, 60, 80, 100]
                ]
                , responsive: true
            });
        });

    </script>
    <script>
        document.getElementById('itemAssignForm').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('.available-checkbox:checked');
            let valid = false;

            checkboxes.forEach(function(checkbox) {
                const row = checkbox.closest('tr');
                const priceInput = row.querySelector('.rate-input');
                const value = parseFloat(priceInput.value);

                if (!isNaN(value) && value > 0) {
                    valid = true;
                }
            });

            if (!valid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No Items Assigned',
                    text: 'Please assign at least one item with a valid rate before submitting.',
                });
            }
        });

        function assignSelected() {
            const checkboxes = document.querySelectorAll('.available-checkbox:checked');
            if (checkboxes.length === 0) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No items selected',
                    text: 'Please select at least one item to assign!',
                });
            }
        }

        function unassignSelected(unassignUrl) {
            const checkboxes = document.querySelectorAll('.assigned-checkbox:checked');
            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No items selected',
                    text: 'Please select at least one item to unassign!',
                });
                return;
            }

            const items = [];
            checkboxes.forEach(row => {
                const tr = row.closest('tr');
                const itemId = tr.getAttribute('data-id');
                if (itemId) {
                    items.push({
                        id: itemId
                    });
                }
            });

            fetch(unassignUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        items
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Unassigned!',
                            text: 'Items unassigned successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed!',
                            text: 'Something went wrong during unassigning.',
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while unassigning items.',
                    });
                });
        }

        // selectAll function to toggle checkboxes codde
        function selectAll(type) {
            const selector = type === 'available' ? '.available-checkbox' : '.assigned-checkbox';
            const checkboxes = document.querySelectorAll(selector);
            let allChecked = true;

            checkboxes.forEach(cb => {
                if (!cb.checked) {
                    allChecked = false;
                }
            });

            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
            });
        }
    </script>



@endsection
