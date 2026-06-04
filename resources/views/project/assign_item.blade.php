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

    .project-name-box {
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
        border-radius: 0.375rem;
    }

    .card {
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
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
                        <div class="project-name-box">Project: {{ $project->project_name }}</div>
                    </div>
                    <!-- Form -->
                    <form action="{{ route('project.storeAssignedItems', $project->id) }}" method="POST" id="itemAssignForm">
                        @csrf
                        <div class="row">
                            {{-- <div class="col-md-12 mb-4">
                                <label for="contractorSelect" class="section-title">🔨 Select Contractor</label>
                                <select id="contractorSelect" name="contractor_id" class="form-control">
                                <option value="">Select Contractor</option>
                                @foreach ($project->contractors as $contractor)
                                <option value="{{ $contractor->id }}">{{ $contractor->name }}</option>
                            @endforeach
                            </select>
                        </div>

                        <!-- Total Houses Display for Selected Contractor -->
                        <div class="col-md-12 mb-4" id="totalHousesSection" style="display:none;">
                            <label for="totalHouses" class="section-title">🏠 Total Houses</label>
                            <input type="text" id="totalHouses" class="form-control" disabled>
                        </div> --}}
                        <!-- Available Items -->
                        <div class="col-md-12 mb-4">
                            <div class="section-title">🟢 Available Items</div>
                            <button type="button" class="btn btn-sm btn-theme mb-2" onclick="selectAll('available')">Select All</button>
                            <div class="table-responsive">
                                <table id="datatables-reponsive-assigned" class="table table-bordered table-hover align-middle text-center table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Contractor</th>
                                            <th>Brand</th>
                                            <th>Vendor</th>
                                            <th>Item</th>
                                            <th>Size</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($allItems as $item)
                                        <tr data-id="{{ $item->id }}">
                                            <td>
                                                <input type="checkbox" class="available-checkbox" name="selected_items[]" value="{{ $item->id }}">
                                            </td>
                                            <td>
                                                <select name="contractor_id[{{ $item->id }}]" class="form-control mt-1">
                                                    <option value="">Select Contractor</option>
                                                    @foreach ($project->houseProjects as $houseProject)
                                                    @foreach ($houseProject->contractors as $contractor)
                                                    <option value="{{ $contractor->id }}">
                                                        houses:{{ $houseProject->total_houses }} - {{ $contractor->name }}
                                                    </option>
                                                    @endforeach
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="brand_id[{{ $item->id }}]" class="form-control">
                                                    <option value="">Select Brand</option>
                                                    @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>
                                                <select name="supplier_id[{{ $item->id }}]" class="form-control">
                                                    <option value="">Select Vendor</option>
                                                    @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>{{ $item->item }}</td>
                                            <td>{{ $item->size }}</td>

                                            <td>
                                                <input type="number" min="0" step="0.01" class="form-control quantity-input" name="allowed_qty[{{ $item->id }}]" value="">


                                            </td>
                                        </tr>
                                        @endforeach
                                        {{-- @foreach ($allItems as $item)
                                            @foreach ($project->houseProjects as $houseProject)
                                            @dd($houseProject);
                                            <tr data-id="{{ $item->id }}-{{ $houseProject->id }}">
                                        <td>
                                            <input type="checkbox" class="available-checkbox" name="selected_items[]" value="{{ $item->id }}-{{ $houseProject->id }}">
                                        </td>
                                        <td>
                                            <select name="brand_id[{{ $item->id }}-{{ $houseProject->id }}]" class="form-control">
                                                <option value="">Select Brand</option>
                                                @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="supplier_id[{{ $item->id }}-{{ $houseProject->id }}]" class="form-control">
                                                <option value="">Select Vendor</option>
                                                @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>{{ $item->item }}</td>
                                        <td>{{ $item->size }}</td>
                                        <td>
                                            <input type="number" min="0" step="0.01" class="form-control quantity-input" name="allowed_qty[{{ $item->id }}-{{ $houseProject->id }}]" value="">
                                            <input type="hidden" name="house_project_ids[{{ $item->id }}-{{ $houseProject->id }}]" value="{{ $houseProject->id }}">
                                        </td>
                                        </tr>
                                        @endforeach
                                        @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-success mt-2">✅ Assign</button>
                        </div>

                        <!-- Assigned Items -->
                        <div class="col-md-12 mt-3">
                            <div class="section-title">🔴 Assigned Items</div>
                            {{-- <button type="button" class="btn btn-sm btn-theme mb-2" onclick="selectAll('assigned')">Select All</button> --}}
                            <div class="table-responsive">
                                <table id="datatables-reponsive-assigned" class="table table-bordered table-hover align-middle text-center assignedTable">
                                    <thead>
                                        <tr>
                                            {{-- <th>Select</th> --}}
                                            <th>Contractor</th>
                                            <th>Brand</th>
                                            <th>Vendor</th>
                                            <th>Item</th>
                                            <th>Size</th>
                                            <th>Qty</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($project->items as $item)
                                        @php
                                        $pivot = $item->pivot;
                                        $houseProjectId = $pivot->house_project_id ?? null;
                                        $houseProject = \App\Models\HouseProject::find($houseProjectId);
                                        $contractor = \App\Models\Contractor::find($pivot->contractor_id);
                                        $brand = \App\Models\Brand::find($pivot->brand_id);
                                        $supplier = \App\Models\Supplier::find($pivot->supplier_id);
                                        @endphp
                                        {{-- @dd($pivot); --}}
                                        <tr data-id="{{ $item->id }}">
                                            {{-- <td><input type="checkbox" class="assigned-checkbox" checked></td> --}}
                                            {{-- <td>
                                                @if($project->contractors && $project->contractors->count())
                                                {{ $project->contractors->pluck('name')->join(', ') }}
                                                @else
                                                -
                                                @endif
                                            </td> --}}
                                            <td>{{ $contractor ? $contractor->name : 'N/A' }}</td>
                                            <td>{{ $brand ? $brand->name : 'N/A' }}</td>
                                            <td>{{ $supplier ? $supplier->name : 'N/A' }}</td>
                                            <td>{{ $item->item }}</td>
                                            <td>{{ $item->size }}</td>
                                            <td><input disabled class="form-control assigned-qty" value="{{ $pivot->quantity }}"></td>
                                            <td>{{ $pivot->created_at }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{-- <button type="button" class="btn btn-danger mt-2" onclick="unassignSelected()">❌
Unassign</button> --}}
                        </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const contractorSelect = document.getElementById('contractorSelect');
        const totalHousesSection = document.getElementById('totalHousesSection');
        const totalHousesInput = document.getElementById('totalHouses');

        contractorSelect.addEventListener('change', function() {
            const contractorId = contractorSelect.value;
            if (contractorId) {
                fetch('/project/get-contractor-house-projects/' + contractorId)
                    .then(response => response.json())
                    .then(data => {
                        let totalHouses = 0;
                        data.forEach(houseProject => {
                            totalHouses += houseProject.total_houses;
                        });
                        totalHousesInput.value = totalHouses;
                        totalHousesSection.style.display = 'block';
                    })
                    .catch(() => {
                        console.error('Error fetching house projects.');
                    });
            } else {
                totalHousesSection.style.display = 'none';
            }
        });
    });

</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Datatables Responsive
        $("#datatables-reponsive-assigned").DataTable({
            pageLength: 10
            , lengthMenu: [
                [10, 20, 40, 60, 80, 100]
                , [10, 20, 40, 60, 80, 100]
            ]
            , responsive: true
        });
    });

</script>


<script>
    function selectAll(type) {
        const checkboxes = document.querySelectorAll(`.${type}-checkbox`);
        checkboxes.forEach(cb => cb.checked = true);
    }

    function selectAll(type) {
        const checkboxes = document.querySelectorAll(`.${type}-checkbox`);
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

    function assignSelected() {
        const rows = document.querySelectorAll('#datatables-reponsive tbody tr');

        rows.forEach(row => {
            const checkbox = row.querySelector('input[type="checkbox"]');
            if (checkbox && checkbox.checked) {
                const id = row.getAttribute('data-id');

                if (document.getElementById('input-' + id)) {
                    return;
                }

                row.remove();
                checkbox.classList.remove('available-checkbox');
                checkbox.classList.add('assigned-checkbox');
                checkbox.checked = true;
                document.querySelector('.assignedTable tbody').appendChild(row);

                const quantityInput = row.querySelector('.quantity-input');
                if (quantityInput) {
                    quantityInput.setAttribute('name', 'allowed_qty[' + id + ']');
                }

                const itemInput = document.createElement('input');
                itemInput.type = 'hidden';
                itemInput.name = 'item_ids[]';
                itemInput.value = id;
                itemInput.id = 'input-' + id;
                document.getElementById('itemAssignForm').appendChild(itemInput);

                const houseCell = row.querySelector('td[data-house-id]');
                if (houseCell) {
                    const houseId = houseCell.getAttribute('data-house-id');

                    const houseInput = document.createElement('input');
                    houseInput.type = 'hidden';
                    houseInput.name = `house_project_ids[${id}]`;
                    houseInput.value = houseId;
                    houseInput.id = 'house-input-' + id;
                    document.getElementById('itemAssignForm').appendChild(houseInput);
                }
            }
        });
    }

    function unassignSelected() {
        let selectedItems = [];
        const rows = document.querySelectorAll('.assignedTable tbody tr');

        rows.forEach(row => {
            const checkbox = row.querySelector('input[type="checkbox"]');
            const id = row.getAttribute('data-id');

            if (checkbox && checkbox.checked) {
                const qtyInput = row.querySelector('.assigned-qty');
                const qty = qtyInput ? parseFloat(qtyInput.value) : 0;

                selectedItems.push({
                    id
                    , quantity: qty
                });
            }
        });

        if (selectedItems.length === 0) {
            Swal.fire({
                icon: 'warning'
                , title: 'No Items Selected'
                , text: 'Please select items to unassign.'
            });
            return;
        }

        fetch(`{{ route('project.unassignItems', $project->id) }}`, {
                method: 'POST'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
                , body: JSON.stringify({
                    items: selectedItems
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success'
                        , title: 'Unassigned!'
                        , text: 'Selected items have been unassigned successfully.'
                        , timer: 1500
                        , showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error'
                        , title: 'Error'
                        , text: 'Failed to unassign items. Please try again.'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error'
                    , title: 'Error'
                    , text: 'Something went wrong while unassigning.'
                });
            });
    }

</script>
<script>
    document.getElementById('itemAssignForm').addEventListener('submit', function(e) {
        const qtyInputs = document.querySelectorAll('.quantity-input');
        let valid = false;

        qtyInputs.forEach(function(input) {
            const quantity = parseInt(input.value);
            const checkbox = input.closest('tr').querySelector('.available-checkbox');
            if (checkbox.checked && quantity > 0) {
                valid = true;
            }
        });

        if (!valid) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning'
                , title: 'No Items Assigned'
                , text: 'Please assign at least one item with quantity > 0 before submitting.'
            });
        }
    });

</script>


@endsection
