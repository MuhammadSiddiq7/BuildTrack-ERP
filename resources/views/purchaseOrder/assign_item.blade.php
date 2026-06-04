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
                        <form action="{{ route('project.storeAssignedItems', $project->id) }}" method="POST" id="itemAssignForm">
                            @csrf
                            <div class="row">
                                <!-- Available Items -->
                                <div class="col-6">
                                    <div class="d-flex justify-content-between">
                                    <h5>Available Items</h5>
                                    <h5>{{$project->site_name}}</h5>
                                    </div>
                                    <button type="button" class="btn btn-sm mb-2" onclick="selectAll('available')" style="background-color: #153d77; color: white;">Select All</button>
                                    <table  class="table table-striped" style="width:100%" id="datatables-reponsive">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Item</th>
                                                <th>Size</th>
                                                <th>Allocated Qty</th>
                                                {{-- <th>Available Qty</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>QTY</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allItems as $item)
                                            @if ($item->available_qty <= 0)
                                            <tr data-id="{{ $item->id }}">
                                                <td><input type="checkbox" class="available-checkbox"></td>
                                                <td>{{ $item->item }}</td>
                                                <td>{{ $item->size }}</td>
                                                <td>
                                                    <input type="number" min="0" step="0.01" name="allowed_qty[{{ $item->id }}]" class="form-control quantity-input" data-item-id="{{ $item->id }}">
                                                </td>
                                                {{-- <td>{{ $item->available_qty }}</td>
                                                <td>{{ $item->qty }}</td>
                                                <td>{{ $item->rate }}</td>
                                                <td>
                                                    <input type="number" min="0" step="1" name="quantities[{{ $item->id }}]" class="form-control quantity-input" data-item-id="{{ $item->id }}" data-available="{{ $item->available_qty }}">
                                                </td> --}}
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
                                    <table id="datatables-reponsive-assigned" class="table table-striped assignedTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Item</th>
                                                <th>Size</th>
                                                <th>Allocated Qty</th>
                                                {{-- <th>Rate</th>
                                                <th>QTY</th>
                                                <th>Assigned QTY</th> --}}
                                                <th>Assign Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($project->items as $item)
                                            <tr data-id="{{ $item->id }}">
                                                <td><input type="checkbox" class="assigned-checkbox" checked></td>
                                                <td>{{ $item->item }}</td>
                                                <td>{{ $item->size }}</td>
                                                {{-- <td>{{ $item->pivot->quantity }}</td> --}}
                                                <td>
                                                    <input disabled class="form-control assigned-qty" value="{{ $item->pivot->quantity }}"
                                                    data-id="{{ $item->id }}">
                                                </td>
                                                {{-- <td>{{ $item->rate }}</td>
                                                <td>{{ $item->pivot->quantity }}</td>
                                                <td>
                                                    <input type="number"
                                                        min="0"
                                                        step="1"
                                                        class="form-control assigned-qty"
                                                        value="{{ $item->pivot->quantity }}"
                                                        data-id="{{ $item->id }}"
                                                        data-available="{{ $item->available_qty }}">
                                                    </td> --}}
                                                <td>{{ $item->pivot->created_at }}</td>
                                            </tr>
                                            {{-- <input type="hidden" name="item_ids[]" value="{{ $item->id }}" id="input-{{ $item->id }}"> --}}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive-assigned").DataTable({
                pageLength: 20,
                lengthMenu: [
                    [20, 40, 60, 80, 100],
                    [20, 40, 60, 80, 100]
                ],
                responsive: true
            });
        });
    </script>
<script>
    // document.querySelectorAll('.quantity-input').forEach(input => {
    //     input.addEventListener('input', function() {
    //         const maxQty = parseFloat(this.dataset.available);
    //         const enteredQty = parseFloat(this.value);

    //         if (enteredQty > maxQty) {
    //             alert(`You cannot assign more than ${maxQty}`);
    //             this.value = maxQty;
    //         }
    //     });
    // });
    // document.querySelectorAll('.assigned-qty').forEach(input => {
    //     input.addEventListener('input', function() {
    //         const maxQty = parseFloat(this.dataset.available);
    //         const enteredQty = parseFloat(this.value);

    //         if (!isNaN(maxQty) && enteredQty > maxQty) {
    //             alert(`You cannot assign more than ${maxQty}`);
    //             this.value = maxQty;
    //         }
    //     });
    // });


    function selectAll(type) {
        const checkboxes = document.querySelectorAll(`.${type}-checkbox`);
        checkboxes.forEach(cb => cb.checked = true);
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

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'item_ids[]';
                input.value = id;
                input.id = 'input-' + id;
                document.getElementById('itemAssignForm').appendChild(input);
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

                selectedItems.push({ id, quantity: qty });
            }
        });

        if (selectedItems.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Items Selected',
                text: 'Please select items to unassign.'
            });
            return;
        }

        fetch(`{{ route('project.unassignItems', $project->id) }}`, {
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
                Swal.fire({
                    icon: 'success',
                    title: 'Unassigned!',
                    text: 'Selected items have been unassigned successfully.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to unassign items. Please try again.'
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong while unassigning.'
            });
        });
    }

</script>
@endsection
