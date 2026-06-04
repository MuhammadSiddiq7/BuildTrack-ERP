@extends('layout.master')
@section('title', 'Create Brand')
@section('header-title', 'Create Quotation')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3>Create Quotations for Demand #{{ $itemDemand->demand_no }}</h3>
                <form action="{{ route('quotation.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="item_demand_id" value="{{ $itemDemand->id }}">

                    @foreach($itemDemand->items as $item)
                    <div class="card mb-4">
                        <div class="card-header">
                            <strong>{{ $item->item }} ({{ $item->size }})</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered text-center supplier-table" data-item-id="{{ $item->id }}">
                                <thead>
                                    <tr>
                                        <th>Supplier Name</th>
                                        <th>Description</th>
                                        <th>Deno</th>
                                        <th>Total Qty Required</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="supplier-rows">
                                    <tr>
                                        <td><input type="text" name="quotations[{{ $item->id }}][0][supplier_name]" class="form-control" ></td>
                                        <td><input type="text" name="quotations[{{ $item->id }}][0][description]" class="form-control"></td>
                                        <td><input type="text" name="quotations[{{ $item->id }}][0][deno]" class="form-control"></td>
                                        <td><input type="number" step="0.01" name="quotations[{{ $item->id }}][0][quantity]" class="form-control quantity" ></td>
                                        <td><input type="number" step="0.01" name="quotations[{{ $item->id }}][0][rate]" class="form-control rate" ></td>
                                        <td><input type="number" step="0.01" name="quotations[{{ $item->id }}][0][amount]" class="form-control amount"  readonly></td>
                                        <td><button type="button" class="btn btn-sm btn-success add-row">+</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save Quotations</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-row')) {
            let table = e.target.closest('table');
            let tbody = table.querySelector('.supplier-rows');
            let index = tbody.children.length;
            let itemId = table.getAttribute('data-item-id');

            let newRow = `
                <tr>
                <td><input type="text" name="quotations[${itemId}][${index}][supplier_name]" class="form-control"></td>
                <td><input type="text" name="quotations[${itemId}][${index}][description]" class="form-control"></td>
                <td><input type="text" name="quotations[${itemId}][${index}][deno]" class="form-control"></td>
                <td><input type="number" step="0.01" name="quotations[${itemId}][${index}][quantity]" class="form-control quantity" ></td>
                <td><input type="number" step="0.01" name="quotations[${itemId}][${index}][rate]" class="form-control rate" ></td>
                <td><input type="number" step="0.01" name="quotations[${itemId}][${index}][amount]" class="form-control amount" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row">x</button></td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', newRow);
        }

        // Remove row on clicking "x" button
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity') || e.target.classList.contains('rate')) {
            let quantityInput = e.target.closest('tr').querySelector('.quantity');
            let rateInput = e.target.closest('tr').querySelector('.rate');
            let amountInput = e.target.closest('tr').querySelector('.amount');

            if (quantityInput.value && rateInput.value) {
                let quantity = parseFloat(quantityInput.value);
                let rate = parseFloat(rateInput.value);
                let amount = quantity * rate;

                amountInput.value = amount.toFixed(2);
            }
        }
    });

</script>
@endsection
