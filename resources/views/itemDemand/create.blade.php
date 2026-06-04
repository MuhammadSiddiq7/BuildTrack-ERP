@extends('layout.master')
@section('title', 'Create Item Demand')
@section('header-title', 'Create Item Demand')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ route('itemDemand.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date</label>
                                <input type="date" name="date" class="form-control" required>
                                @error('date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Project</label>
                                <select name="project_id" id="projectSelect" class="form-select" required>
                                    <option value="">-- Select Project --</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div id="item-demand-wrapper">
                            <div class="row item-demand-row gx-3 gy-4">
                                <div class="col-md-4">
                                    <label class="form-label">Contractor</label>
                                    <select name="contractor_id[]" class="form-select contractor-select" required>
                                        <option value="">-- Select Contractor --</option>
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    <label class="form-label">Item</label>
                                    <select name="item_id[]" class="form-select item-select" required>
                                        <option value="">-- Select Item --</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}"
                                                data-allocated="{{ $item->projects->first()?->pivot?->quantity ?? 0 }}">
                                                {{ $item->item }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Size</label>
                                    <input type="text" class="form-control size-field" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Allocated Qty</label>
                                    <input type="number "name="allocated_qty[]" step="0.01" class="form-control allocated-field"
                                        readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Remaining Qty</label>
                                    <input type="number" step="0.01" name="remaining_qty[]" class="form-control remaining-field" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Request QTY</label>
                                    <input type="number"  name="item_qty[]" min="0" step="0.01"
                                        class="form-control qty-field" required>
                                </div>

                                <!-- Over & Above Qty -->
                                <div class="col-12 over-above-section p-3 rounded bg-light shadow-sm"
                                    style="display: none; border-left: 4px solid #0d6efd;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Over & Above Qty</label>
                                            <input type="number" name="over_qty[]" min="0" step="0.01"
                                                class="form-control over-qty-field" placeholder="Enter extra quantity">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Over & Above Description</label>
                                            <input type="text" name="over_description[]"
                                                class="form-control over-desc-field" placeholder="Enter reason or note">
                                        </div>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="col-12 mt-3 d-flex gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm add-over-btn">+ Add Over &
                                        Above</button>
                                    <button type="button"
                                        class="btn btn-outline-danger btn-sm remove-row d-none">Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="my-4">
                            <button type="button" id="addMoreBtn" class="btn btn-secondary btn-sm">➕ Add More</button>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-4">💾 Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const projectDropdown = document.getElementById('projectSelect');

    // Fetch contractors for project (your existing endpoint)
    function loadContractorsByProject(projectId, contractorSelectElement) {
        contractorSelectElement.innerHTML = '<option value="">Loading...</option>';
        fetch(`/itemDemand/project/${projectId}/contractors`)
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">-- Select Contractor --</option>';
                data.forEach(contractor => {
                    options += `<option value="${contractor.id}">${contractor.name}</option>`;
                });
                contractorSelectElement.innerHTML = options;
            })
            .catch(() => {
                contractorSelectElement.innerHTML = '<option value="">-- No Contractors Found --</option>';
            });
    }

    // Fetch items for project+contractor and include remaining returned by controller
    function loadItemsByContractor(projectId, contractorId, itemSelectElement) {
        itemSelectElement.innerHTML = '<option value="">Loading...</option>';

        fetch(`/itemDemand/get-items/${projectId}/${contractorId}`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Select Item --</option>';
                data.forEach(item => {
                    const name = item.item_name ?? item.name ?? item.item ?? '';
                    const size = item.size ?? '';
                    const allocated = item.allocated_qty ?? 0;
                    const remaining = item.remaining_qty ?? 0;

                    // Put both allocated and remaining as data attributes
                    options += `<option value="${item.id}"
                        data-size="${size}"
                        data-allocated="${allocated}"
                        data-remaining="${remaining}">
                        ${name} (Remaining: ${remaining})
                    </option>`;
                });
                itemSelectElement.innerHTML = options;
            })
            .catch(err => {
                console.error('Error fetching items:', err);
                itemSelectElement.innerHTML = '<option value="">-- No Items Found --</option>';
            });
    }

    // Helper: total requested (current input values) for a given itemId across all rows
    function getTotalRequestedForItem(itemId) {
        let total = 0;
        document.querySelectorAll('.item-demand-row').forEach(row => {
            const sel = row.querySelector('.item-select');
            const q = parseFloat(row.querySelector('.qty-field')?.value || 0);
            if (sel && sel.value && sel.value.toString() === itemId.toString()) {
                total += q;
            }
        });
        return total;
    }

    // Setup event listeners for a single row
    function setupItemRow(row) {
        const contractorSelect = row.querySelector('.contractor-select');
        const itemSelect = row.querySelector('.item-select');
        const sizeField = row.querySelector('.size-field');
        const allocatedField = row.querySelector('.allocated-field');
        const remainingField = row.querySelector('.remaining-field');
        const qtyField = row.querySelector('.qty-field');
        const removeBtn = row.querySelector('.remove-row');
        const addOverBtn = row.querySelector('.add-over-btn');
        const overAboveSection = row.querySelector('.over-above-section');

        // Show/Hide remove btn
        const allRows = document.querySelectorAll('.item-demand-row');
        if (removeBtn) removeBtn.classList.toggle('d-none', allRows.length === 1);

        // Add Over button
        if (addOverBtn && overAboveSection) {
            addOverBtn.addEventListener('click', function() {
                overAboveSection.style.display = 'flex';
                this.style.display = 'none';
            });
        }

        // When contractor changes -> load items for that project+contractor
        if (contractorSelect) {
            contractorSelect.addEventListener('change', function() {
                const contractorId = this.value;
                const projectId = document.getElementById('projectSelect').value;
                if (contractorId && projectId) {
                    loadItemsByContractor(projectId, contractorId, itemSelect);
                } else {
                    itemSelect.innerHTML = '<option value="">-- Select Item --</option>';
                    if (sizeField) sizeField.value = '';
                    if (allocatedField) allocatedField.value = '';
                    if (remainingField) remainingField.value = '';
                    if (qtyField) qtyField.value = '';
                }
            });
        }

        // When item changes -> set size, allocated, remaining
        if (itemSelect) {
            itemSelect.addEventListener('change', function() {
                const sel = itemSelect.selectedOptions[0];
                const size = sel?.getAttribute('data-size') ?? '';
                const allocated = sel?.getAttribute('data-allocated') ?? 0;
                const remaining = sel?.getAttribute('data-remaining') ?? 0;

                if (sizeField) sizeField.value = size;
                if (allocatedField) allocatedField.value = allocated;
                if (remainingField) remainingField.value = remaining;
                if (qtyField) qtyField.value = '';

                // Optionally set qtyField.max so browser hints max
                if (qtyField) qtyField.max = remaining;

            });
        }

        // Validate qty input so that total requested across rows does not exceed DB remaining
        if (qtyField) {
            qtyField.addEventListener('input', function() {
                const itemId = itemSelect?.value;
                if (!itemId) return;

                const selOpt = itemSelect.selectedOptions[0];
                const remaining = parseFloat(selOpt?.getAttribute('data-remaining') || 0);

                const totalRequested = getTotalRequestedForItem(itemId);

                if (totalRequested > remaining) {
                    // compute allowed for this field = remaining - (other rows total)
                    const currentVal = parseFloat(this.value || 0);
                    const otherTotals = totalRequested - currentVal;
                    const allowed = Math.max(0, remaining - otherTotals);

                    // set this field to allowed and notify user
                    this.value = allowed;
                    alert('Requested quantity exceeds remaining available (' + remaining + '). It has been adjusted.');
                }
            });
        }

        // Remove row
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

    // On project change -> load contractors for all rows
    projectDropdown.addEventListener('change', function() {
        const projectId = this.value;
        document.querySelectorAll('.item-demand-row').forEach(row => {
            const contractorSelect = row.querySelector('.contractor-select');
            const itemSelect = row.querySelector('.item-select');

            if (projectId) {
                loadContractorsByProject(projectId, contractorSelect);
            } else {
                contractorSelect.innerHTML = '<option value="">-- Select Contractor --</option>';
                if (itemSelect) itemSelect.innerHTML = '<option value="">-- Select Item --</option>';
            }
        });
    });

    // Setup existing rows
    document.querySelectorAll('.item-demand-row').forEach(row => setupItemRow(row));

    // Add row cloning logic (preserve listeners)
    const addBtn = document.getElementById('addMoreBtn');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            const wrapper = document.getElementById('item-demand-wrapper');
            const firstRow = wrapper.querySelector('.item-demand-row');
            const newRow = firstRow.cloneNode(true);

            // reset selects and inputs
            newRow.querySelectorAll('select, input').forEach(input => {
                if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                } else {
                    input.value = '';
                }
            });

            // hide over above section again if cloned
            const overAboveSection = newRow.querySelector('.over-above-section');
            const addOverBtn = newRow.querySelector('.add-over-btn');
            if (overAboveSection) overAboveSection.style.display = 'none';
            if (addOverBtn) addOverBtn.style.display = '';

            wrapper.appendChild(newRow);
            setupItemRow(newRow);

            // update remove button visibility on all rows
            document.querySelectorAll('.item-demand-row').forEach(r => {
                const removeBtn = r.querySelector('.remove-row');
                if (removeBtn) removeBtn.classList.toggle('d-none', document.querySelectorAll('.item-demand-row').length === 1);
            });
        });
    }
});
</script>

{{-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const projectDropdown = document.getElementById('projectSelect');

    // ================= Load Contractors by Project =================
    function loadContractorsByProject(projectId, contractorSelectElement) {
        contractorSelectElement.innerHTML = '<option value="">Loading...</option>';
        fetch(`/itemDemand/project/${projectId}/contractors`)
            .then(res => res.json())
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

    // ================= Load Items by Project + Contractor =================
    function loadItemsByContractor(projectId, contractorId, itemSelectElement) {
        itemSelectElement.innerHTML = '<option value="">Loading...</option>';

        fetch(`/itemDemand/get-items/${projectId}/${contractorId}`)
            .then(response => response.json())
            .then(data => {
                console.log('fetched items for project', projectId, 'contractor', contractorId, data);
                let options = '<option value="">-- Select Item --</option>';
                data.forEach(item => {
                    // support different possible key names defensively
                    const name = item.item_name ?? item.name ?? item.item ?? '';
                    const size = (item.size !== undefined && item.size !== null) ? item.size : '';
                    const allocated = (item.allocated_qty !== undefined && item.allocated_qty !== null)
                        ? item.allocated_qty
                        : (item.quantity ?? 0);

                    options += `<option value="${item.id}" data-size="${size}" data-allocated="${allocated}">${name}</option>`;
                });
                itemSelectElement.innerHTML = options;

            })
            .catch(err => {
                console.error('Error fetching items:', err);
                itemSelectElement.innerHTML = '<option value="">-- No Items Found --</option>';
            });
    }

    // ================= Row Setup =================
    function setupItemRow(row) {
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
        if (removeBtn) removeBtn.classList.toggle('d-none', allRows.length === 1);

        // Over Above Show
        if (addOverBtn && overAboveSection) {
            addOverBtn.addEventListener('click', function() {
                overAboveSection.style.display = 'flex';
                this.style.display = 'none';
            });
        }

        // Contractor Change → Load Items (uses project + contractor)
        if (contractorSelect) {
            contractorSelect.addEventListener('change', function() {
                const contractorId = this.value;
                const projectId = document.getElementById('projectSelect').value;
                if (contractorId && projectId) {
                    loadItemsByContractor(projectId, contractorId, itemSelect);
                } else {
                    itemSelect.innerHTML = '<option value="">-- Select Item --</option>';
                    if (sizeField) sizeField.value = '';
                    if (allocatedField) allocatedField.value = 0;
                    if (qtyField) { qtyField.value = ''; qtyField.max = 0; }
                }
            });
        }

        // Item Select Change → Fill size / allocated
        if (itemSelect) {
            itemSelect.addEventListener('change', function() {
                const selected = itemSelect.selectedOptions[0];
                const size = selected?.getAttribute('data-size') ?? '';
                const allocated = selected?.getAttribute('data-allocated') ?? 0;
                if (sizeField) sizeField.value = size;
                if (allocatedField) allocatedField.value = allocated;
                if (qtyField) {
                    qtyField.max = allocated ?? 0;
                    qtyField.value = '';
                }
            });
        }

        // Quantity Validation
        if (qtyField) {
            qtyField.addEventListener('input', function() {
                const max = parseFloat(allocatedField.value) || 0;
                const entered = parseFloat(qtyField.value) || 0;
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

    // ================= Project Change → Load Contractors =================
    projectDropdown.addEventListener('change', function() {
        const projectId = this.value;
        document.querySelectorAll('.item-demand-row').forEach(row => {
            const contractorSelect = row.querySelector('.contractor-select');
            const itemSelect = row.querySelector('.item-select');

            if (projectId) {
                loadContractorsByProject(projectId, contractorSelect);
            } else {
                contractorSelect.innerHTML = '<option value="">-- Select Contractor --</option>';
                if (itemSelect) itemSelect.innerHTML = '<option value="">-- Select Item --</option>';
            }
        });
    });

    // Setup all existing rows
    document.querySelectorAll('.item-demand-row').forEach(row => setupItemRow(row));

    // Add New Row
    const addBtn = document.getElementById('addMoreBtn');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            const wrapper = document.getElementById('item-demand-wrapper');
            const firstRow = wrapper.querySelector('.item-demand-row');
            const newRow = firstRow.cloneNode(true);

            // reset fields
            newRow.querySelectorAll('select, input').forEach(input => {
                if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                } else {
                    input.value = '';
                }
            });

            // reset Over Above section
            const overAboveSection = newRow.querySelector('.over-above-section');
            const addOverBtn = newRow.querySelector('.add-over-btn');
            if (overAboveSection) overAboveSection.style.display = 'none';
            if (addOverBtn) addOverBtn.style.display = '';

            wrapper.appendChild(newRow);
            setupItemRow(newRow);
        });
    }
});
</script> --}}

@endsection
