@extends('layout.master')
@section('title', 'Edit Item Demand')
@section('header-title', 'Edit Item Demand')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('itemDemand.update', $itemDemand->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date</label>
                                <input type="date" name="date" value="{{ old('date', $itemDemand->date) }}"
                                    class="form-control" required>
                                @error('date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Project</label>
                                <select name="project_id" id="projectSelect" class="form-select" required>
                                    <option value="">-- Select Project --</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ old('project_id', $itemDemand->project_id) == $project->id ? 'selected' : '' }}>
                                            {{ $project->project_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div id="item-demand-wrapper">
                            @foreach ($itemDemand->items as $index => $item)
                                <div class="row item-demand-row gx-3 gy-4 mb-4">
                                    <input type="hidden" name="item_demand_item_ids[]"
                                        value="{{ $item->pivot->id ?? '' }}">

                                    {{-- <div class="col-md-4">
                                        <label class="form-label">House Number</label>
                                        <select name="house_project_id[]" class="form-select house-select" required>
                                            <option value="">-- Select House --</option>
                                            @foreach ($houseProjects as $house)
                                                <option value="{{ $house->id }}"
                                                    {{ $item->projects->first()?->pivot?->houseProject_id == $house->id ? 'selected' : '' }}
                                                    data-project-id="{{ $house->project_id }}">
                                                    {{ $house->house_number }}
                                                </option>

                                            @endforeach
                                        </select>
                                    </div> --}}

                                    {{-- House Dropdown --}}
                                    <div class="col-md-4">
                                        <label class="form-label">House Number</label>
                                        <select name="house_project_id[]" class="form-select house-select" required>
                                            <option value="">-- Select House --</option>
                                            @foreach ($houseProjects as $houseProject)
                                                <option value="{{ $houseProject->id }}"
                                                    @if (old('house_Project_id[]', $item->pivot->house_project_id ?? '') == $houseProject->id) selected @endif>
                                                    {{ $houseProject->house_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-4">
                                        <label class="form-label">Item</label>
                                        <select name="item_id[]" class="form-select item-select" required>
                                            <option value="">-- Select Item --</option>
                                            @foreach ($items as $itm)
                                                <option value="{{ $itm->id }}" data-size="{{ $itm->size }}"
                                                    data-allocated="{{ $itm->projects->first()?->pivot?->quantity ?? 0 }}"
                                                    {{ $item->id == $itm->id ? 'selected' : '' }}>
                                                    {{ $itm->item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Size</label>
                                        <input type="text" value="{{ $item->size ?? '' }}"
                                            class="form-control size-field" readonly>
                                    </div>


                                    {{--sk allocated qty start --}}
                                    @php
                                        $projectQty =
                                            optional(
                                                $item->projects->first(function ($project) use ($itemDemand, $item) {
                                                    return $project->id == $itemDemand->project_id &&
                                                        $project->pivot->house_project_id ==
                                                            $item->pivot->house_project_id;
                                                }),
                                            )->pivot->quantity ?? 0;
                                    @endphp

                                    <div class="col-md-4">
                                        <label class="form-label">Allocated Qty</label>
                                        <input type="number" step="0.01" class="form-control allocated-field"
                                            value="{{ $projectQty }}" readonly>
                                    </div>

                                        {{--sk allocated qty start --}}

                                    <div class="col-md-4">
                                        <label class="form-label">Contractor</label>
                                        <select name="contractor_id[]" class="form-select contractor-select" required>
                                            <option value="">-- Select Contractor --</option>
                                            @foreach ($contractors as $contractor)
                                                <option value="{{ $contractor->id }}"
                                                    {{ $item->pivot->contractor_id == $contractor->id ? 'selected' : '' }}>
                                                    {{ $contractor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Qty</label>
                                        <input type="number" step="0.01" name="item_qty[]" value="{{ $item->pivot->item_qty }}"
                                            class="form-control qty-field" required>
                                    </div>

                                    <div class="col-12 over-above-section p-3 rounded bg-light shadow-sm"
                                        style="{{ $item->pivot->over_qty ? '' : 'display:none;' }} border-left: 4px solid #0d6efd;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Over & Above Qty</label>
                                                <input type="number" name="over_qty[]" min="0" step="0.01"
                                                    value="{{ $item->pivot->over_qty }}"
                                                    class="form-control over-qty-field">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Over & Above Description</label>
                                                <input type="text" name="over_description[]"
                                                    value="{{ $item->pivot->over_description }}"
                                                    class="form-control over-desc-field">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3 d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm add-over-btn">+ Add
                                            Over & Above</button>
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm remove-row {{ $loop->first ? 'd-none' : '' }}">Remove</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="my-4">
                            <button type="button" id="addMoreBtn" class="btn btn-secondary btn-sm">➕ Add More</button>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-4">💾 Update</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const projectDropdown = document.getElementById('projectSelect');

            projectDropdown.addEventListener('change', function() {
                const projectId = this.value;

                document.querySelectorAll('.item-demand-row').forEach(row => {
                    const houseSelect = row.querySelector('.house-select');
                    if (projectId) {
                        loadHouses(projectId, houseSelect);
                    } else {
                        houseSelect.innerHTML = '<option value="">-- Select House --</option>';
                    }
                });
            });

            function loadHouses(projectId, houseSelectElement) {
                houseSelectElement.innerHTML = '<option value="">Loading...</option>';
                fetch(`/itemDemand/project/${projectId}/houses`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">-- Select House --</option>';
                        data.forEach(house => {
                            options += `<option value="${house.id}">${house.house_number}</option>`;
                        });
                        houseSelectElement.innerHTML = options;
                    })
                    .catch(() => {
                        houseSelectElement.innerHTML = '<option value="">-- No Houses Found --</option>';
                    });
            }

            function loadContractors(houseId, contractorSelectElement) {
                contractorSelectElement.innerHTML = '<option value="">Loading...</option>';
                fetch(`/itemDemand/house/${houseId}/contractors`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">-- Select Contractor --</option>';
                        data.forEach(contractor => {
                            options += `<option value="${contractor.id}">${contractor.name}</option>`;
                        });
                        contractorSelectElement.innerHTML = options;
                    })
                    .catch(() => {
                        contractorSelectElement.innerHTML =
                            '<option value="">-- No Contractors Found --</option>';
                    });
            }

            function loadItemsByHouse(houseId, itemSelectElement) {
                itemSelectElement.innerHTML = '<option value="">Loading...</option>';
                fetch(`/itemDemand/house/${houseId}/items`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">-- Select Item --</option>';
                        data.forEach(item => {
                            options +=
                                `<option value="${item.id}" data-size="${item.size}" data-allocated="${item.allocated_qty}">${item.name}</option>`;
                        });
                        itemSelectElement.innerHTML = options;
                    })
                    .catch(() => {
                        itemSelectElement.innerHTML = '<option value="">-- No Items Found --</option>';
                    });
            }

            function setupItemRow(row) {
                const houseSelect = row.querySelector('.house-select');
                const contractorSelect = row.querySelector('.contractor-select');
                const itemSelect = row.querySelector('.item-select');
                const sizeField = row.querySelector('.size-field');
                const allocatedField = row.querySelector('.allocated-field');
                const qtyField = row.querySelector('.qty-field');
                const removeBtn = row.querySelector('.remove-row');
                const addOverBtn = row.querySelector('.add-over-btn');
                const overAboveSection = row.querySelector('.over-above-section');

                // Remove Button Visibility
                const allRows = document.querySelectorAll('.item-demand-row');
                removeBtn.classList.toggle('d-none', allRows.length === 1);

                // Over Above Show
                if (addOverBtn && overAboveSection) {
                    addOverBtn.addEventListener('click', function() {
                        overAboveSection.style.display = 'flex';
                        this.style.display = 'none';
                    });
                }

                // Load Contractors + Items
                if (houseSelect) {
                    houseSelect.addEventListener('change', function() {
                        const houseId = this.value;
                        if (houseId) {
                            loadContractors(houseId, contractorSelect);
                            loadItemsByHouse(houseId, itemSelect);
                        } else {
                            contractorSelect.innerHTML =
                                '<option value="">-- Select Contractor --</option>';
                            itemSelect.innerHTML = '<option value="">-- Select Item --</option>';
                        }
                    });
                }

                // Item Select Change
                if (itemSelect) {
                    itemSelect.addEventListener('change', function() {
                        const selected = itemSelect.selectedOptions[0];
                        const size = selected.getAttribute('data-size');
                        const allocated = selected.getAttribute('data-allocated');
                        sizeField.value = size ?? '';
                        allocatedField.value = allocated ?? 0;
                        qtyField.max = allocated ?? 0;
                        qtyField.value = '';
                    });
                }

                // Quantity Validation
                if (qtyField) {
                    qtyField.addEventListener('input', function() {
                        const max = parseFloat(allocatedField.value);
                        const entered = parseFloat(qtyField.value);
                        if (entered > max) {
                            alert('Demanded quantity cannot exceed allocated quantity (' + max + ')');
                            qtyField.value = max;
                        }
                    });
                }

                // Remove Row
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        const rows = document.querySelectorAll('.item-demand-row');
                        if (rows.length > 1) {
                            row.remove();
                        } else {
                            alert('At least one item row is required.');
                        }
                    });
                }
            }

            // Setup all existing rows
            document.querySelectorAll('.item-demand-row').forEach(row => setupItemRow(row));

            // Add New Row
            document.getElementById('addMoreBtn').addEventListener('click', function() {
                const wrapper = document.getElementById('item-demand-wrapper');
                const firstRow = wrapper.querySelector('.item-demand-row');
                const newRow = firstRow.cloneNode(true);

                newRow.querySelectorAll('select, input').forEach(input => {
                    if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    } else {
                        input.value = '';
                    }
                });

                const overAboveSection = newRow.querySelector('.over-above-section');
                const addOverBtn = newRow.querySelector('.add-over-btn');
                if (overAboveSection) overAboveSection.style.display = 'none';
                if (addOverBtn) addOverBtn.style.display = '';

                wrapper.appendChild(newRow);
                setupItemRow(newRow);
            });

            // hosuse selected

            //             document.addEventListener("DOMContentLoaded", function () {
            //     const projectSelects = document.querySelectorAll('.project-select');

            //     projectSelects.forEach((projectSelect, index) => {
            //         const houseSelect = document.querySelectorAll('.house-select')[index];
            //         const selectedHouseId = houseSelect.getAttribute('data-selected');

            //         const projectId = projectSelect.value;
            //         if (projectId) {
            //             fetch(`/get-houses/${projectId}`)
            //                 .then(res => res.json())
            //                 .then(houses => {
            //                     houseSelect.innerHTML = '<option value="">-- Select House --</option>';
            //                     houses.forEach(house => {
            //                         const selected = house.id == selectedHouseId ? 'selected' : '';
            //                         houseSelect.innerHTML += `<option value="${house.id}" ${selected}>${house.house_number}</option>`;
            //                     });
            //                 });
            //         }
            //     });
            // });

        });
    </script>

@endsection
