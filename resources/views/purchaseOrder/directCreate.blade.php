@extends('layout.master')
@section('title', 'Purchase Order')
@section('header-title', 'Purchase Order')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    
                    {{--old form <form action="{{ route('purchaseOrder.generate', $itemDemand->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Item</label>
                        <select class="form-select" name="item_id" id="item-select">
                            <option value="">Select an Item</option>
                            @foreach ($itemDemand->items as $item)
                                <option value="{{ $item->id }}">{{ $item->item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deno</label>
                        <input type="text" class="form-control" name="unit" value="{{ $item->deno }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" id="supplier" class="form-control" required>
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}" data-rate="{{ $s->purchase_price }}">
                                {{ $s->name }} (Rate: {{ number_format($s->purchase_price, 2) }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rate</label>
                        <input type="number" step="0.01" class="form-control" name="rate" id="rate" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" min="0" step="0.01" class="form-control" name="qty" step="any" id="qty" value="{{ $item->pivot->item_qty }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total</label>
                        <input type="number" step="0.01" class="form-control" name="total" id="total" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Delivery Date</label>
                        <input type="date" class="form-control" name="delivery_date" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Create PO</button>
                    </form> --}}

                    <form action="{{ route('purchaseOrder.generate', $itemDemand->id) }}" method="POST"
                        class="p-4 border rounded-3 shadow-sm bg-light">
                        @csrf

                        <h5 class="mb-4 text-primary fw-bold"><i class="bi bi-file-earmark-text"></i> Create Purchase Order
                        </h5>

                        <div class="row g-4">
                            <!-- Item -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Item</label>
                                <select class="form-select" name="item_id" id="item-select">
                                    <option value="">Select an Item</option>
                                    @foreach ($itemDemand->items as $item)
                                        <option value="{{ $item->id }}">{{ $item->item }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Deno -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Deno</label>
                                <input type="text" class="form-control" name="unit" value="{{ $item->deno }}"
                                    readonly>
                            </div>

                            <!-- Supplier -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Supplier</label>
                                <select name="supplier_id" id="supplier" class="form-control" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $s)
                                        <option value="{{ $s->id }}" data-rate="{{ $s->purchase_price }}">
                                            {{ $s->name }} (Rate: {{ number_format($s->purchase_price, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Rate -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Rate</label>
                                <input type="number" step="0.01" class="form-control" name="rate" id="rate"
                                    readonly>
                            </div>

                            <!-- Quantity -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Quantity</label>
                                <input type="number" min="0" step="0.01" class="form-control" name="qty"
                                    step="any" id="qty" value="{{ $item->pivot->item_qty }}">
                            </div>

                            <!-- Total -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Total</label>
                                <input type="number" step="0.01" class="form-control" name="total" id="total"
                                    readonly>
                            </div>

                            <!-- Delivery Date -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Delivery Date</label>
                                <input type="date" class="form-control" name="delivery_date" required>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle-fill me-1"></i> Create PO
                            </button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('item-select').addEventListener('change', function() {
            let itemId = this.value;
            if (!itemId) return;

            fetch("{{ url('/purchaseOrder/get-item-suppliers') }}/" + itemId + "/{{ $itemDemand->id }}")
                .then(res => res.json())
                .then(data => {
                    // Unit aur Qty
                    document.querySelector('input[name="unit"]').value = data.unit ?? '';
                    document.querySelector('input[name="qty"]').value = data.qty ?? 0;

                    // Supplier dropdown reset karo
                    let supplierSelect = document.getElementById('supplier');
                    supplierSelect.innerHTML = '<option value="">Select Supplier</option>';

                    // Naye suppliers add karo
                    data.suppliers.forEach(s => {
                        let option = document.createElement('option');
                        option.value = s.id;
                        option.setAttribute('data-rate', s.purchase_price); // 🔑 ye zaroori hai
                        option.textContent =
                            `${s.name} (Rate: ${parseFloat(s.purchase_price).toFixed(2)})`;
                        supplierSelect.appendChild(option);
                    });

                    // Reset rate & total
                    document.getElementById('rate').value = '';
                    document.getElementById('total').value = '';
                });
        });

        // Supplier change → rate set karo
        document.getElementById('supplier').addEventListener('change', function() {
            let selected = this.options[this.selectedIndex];
            let rate = selected.getAttribute('data-rate') || 0;
            document.getElementById('rate').value = rate;
            calculateTotal();
        });

        // Qty change → total update
        document.getElementById('qty').addEventListener('input', calculateTotal);

        function calculateTotal() {
            let rate = parseFloat(document.getElementById('rate').value) || 0;
            let qty = parseFloat(document.getElementById('qty').value) || 0;
            document.getElementById('total').value = (rate * qty).toFixed(2);
        }
    </script>
@endsection
