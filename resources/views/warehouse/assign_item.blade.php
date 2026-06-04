@extends('layout.master')
@section('title', 'Assign Item')
@section('header-title', 'Assign Item')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <form action="{{ route('warehouse.storeAssignedItems', $warehouse->id) }}" method="POST" id="itemAssignForm">
                            @csrf
                            <div class="row">
                                <!-- Available Items -->
                                <div class="col-6">
                                    <h5>Available Items</h5>
                                    <button type="button" class="btn btn-sm mb-2" onclick="selectAll('available')" style="background-color: #153d77; color: white;">Select All</button>
                                    <table class="table table-bordered" id="availableTable">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Item</th>
                                                <th>Size</th>
                                                <th>Available Qty</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>QTY</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allItems as $item)
                                            @if ($item->available_qty > 0)
                                            <tr data-id="{{ $item->id }}">
                                                <td><input type="checkbox" class="available-checkbox"></td>
                                                <td>{{ $item->item }}</td>
                                                <td>{{ $item->size }}</td>
                                                <td>{{ $item->available_qty }}</td>
                                                <td>{{ $item->qty }}</td>
                                                <td>{{ $item->rate }}</td>
                                                <td>
                                                    <input type="number" min="0" step="1" name="quantities[{{ $item->id }}]" class="form-control quantity-input" data-item-id="{{ $item->id }}" data-available="{{ $item->available_qty }}">
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-success" onclick="assignSelected()">Assign </button>
                                </div>
                                <!-- Assigned Items -->
                                <div class="col-6">
                                    <h5>Assigned Items</h5>
                                    <button type="button" class="btn btn-sm btn-primary mb-2" onclick="selectAll('assigned')" style="background-color: #153d77; color: white;">Select All</button>
                                    <table class="table table-bordered" id="assignedTable">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Item</th>
                                                <th>Size</th>
                                                <th>Rate</th>
                                                <th>QTY</th>
                                                <th>Assign Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($warehouse->items as $item)
                                            <tr data-id="{{ $item->id }}">
                                                <td><input type="checkbox" class="assigned-checkbox" checked></td>
                                                <td>{{ $item->item }}</td>
                                                <td>{{ $item->size }}</td>
                                                <td>{{ $item->rate }}</td>
                                                <td>{{ $item->pivot->quantity }}</td>
                                                <td>
                                                    <input type="number"
                                                        min="0"
                                                        step="1"
                                                        class="form-control assigned-qty"
                                                        value="{{ $item->pivot->quantity }}"
                                                        data-id="{{ $item->id }}"
                                                        data-available="{{ $item->available_qty }}">
                                                    </td>
                                                {{-- <td>
                                                    <input type="number" min="0" step="1" name="quantities[{{ $item->id }}]" class="form-control quantity-input" data-item-id="{{ $item->id }}" data-available="{{ $item->available_qty }}">
                                                </td> --}}
                                                <td>{{ $item->pivot->created_at }}</td>
                                            </tr>
                                            <input type="hidden" name="item_ids[]" value="{{ $item->id }}" id="input-{{ $item->id }}">
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-danger" onclick="unassignSelected()"> Unassign</button>
                                </div>
                            </div>
                            {{-- <button type="submit" class="btn btn-primary mt-3">Save Assignments</button> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', function() {
            const maxQty = parseFloat(this.dataset.available);
            const enteredQty = parseFloat(this.value);

            if (enteredQty > maxQty) {
                alert(`You cannot assign more than ${maxQty}`);
                this.value = maxQty;
            }
        });
    });
    document.querySelectorAll('.assigned-qty').forEach(input => {
        input.addEventListener('input', function() {
            const maxQty = parseFloat(this.dataset.available);
            const enteredQty = parseFloat(this.value);

            if (!isNaN(maxQty) && enteredQty > maxQty) {
                alert(`You cannot assign more than ${maxQty}`);
                this.value = maxQty;
            }
        });
    });


    function selectAll(type) {
        const checkboxes = document.querySelectorAll(`.${type}-checkbox`);
        checkboxes.forEach(cb => cb.checked = true);
    }

    function assignSelected() {
        const rows = document.querySelectorAll('#availableTable tbody tr');
        rows.forEach(row => {
            const checkbox = row.querySelector('input[type="checkbox"]');
            if (checkbox && checkbox.checked) {
                const id = row.getAttribute('data-id');

                row.remove();
                checkbox.classList.remove('available-checkbox');
                checkbox.classList.add('assigned-checkbox');
                checkbox.checked = true;
                document.querySelector('#assignedTable tbody').appendChild(row);

                const quantityInput = row.querySelector('.quantity-input');
                if (quantityInput) {
                    quantityInput.setAttribute('name', 'quantities[' + id + ']');
                }

                if (!document.getElementById('input-' + id)) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'item_ids[]';
                    input.value = id;
                    input.id = 'input-' + id;
                    document.getElementById('itemAssignForm').appendChild(input);
                }
            }
        });
    }

    function unassignSelected() {
    let selectedItems = [];
    const rows = document.querySelectorAll('#assignedTable tbody tr');

    rows.forEach(row => {
        const checkbox = row.querySelector('input[type="checkbox"]');
        const id = row.getAttribute('data-id');

        if (checkbox && checkbox.checked) {
            // Get the assigned quantity input
            const qtyInput = row.querySelector('.assigned-qty');
            const qty = qtyInput ? parseFloat(qtyInput.value) : 0;

            selectedItems.push({ id, quantity: qty });
        }
    });

    if (selectedItems.length === 0) return;

    fetch(`{{ route('warehouse.unassignItems', $warehouse->id) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            items: selectedItems
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            selectedItems.forEach(item => {
                const row = document.querySelector(`#assignedTable tr[data-id="${item.id}"]`);
                if (row) {
                    row.remove();
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    checkbox.classList.remove('assigned-checkbox');
                    checkbox.classList.add('available-checkbox');
                    checkbox.checked = false;

                    // Update available qty in cell if needed (optional)

                    // Move back to available table
                    document.querySelector('#availableTable tbody').appendChild(row);

                    const input = document.getElementById('input-' + item.id);
                    if (input) input.remove();
                }
            });
        }
    });
}

</script>
@endsection
