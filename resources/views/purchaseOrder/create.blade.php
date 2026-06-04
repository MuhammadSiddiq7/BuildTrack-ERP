@extends('layout.master')
@section('title', 'Purchase Order')
@section('header-title', 'Purchase Order')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('purchaseOrder.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="item_demand_id" value="{{ $id }}">
                    <div class="row">
                        <div class="card-body">
                            <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Size</th>
                                        <th>Deno</th>
                                        <th>Allocated QTY</th>
                                        <th>Request QTY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($itemDemand->items as $item)
                                        @php
                                            $allocatedQty = $item->projects->first()?->pivot?->quantity ?? 0;
                                        @endphp
                                        <tr class="item-row" data-item-id="{{ $item->id }}" style="cursor: pointer;">
                                            <td>{{ $item->item ?? 'N/A' }}</td>
                                            <td>{{ $item->size ?? 'N/A' }}</td>
                                            <td>{{ $item->deno ?? 'N/A' }}</td>
                                            <td>{{ $allocatedQty }}</td>
                                            <td>{{ $item->pivot->item_qty ?? 0 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <hr>
                            @foreach($itemsWithSuppliers as $entry)
                                <div class="supplier-section" id="supplier-section-{{ $entry['item']->id }}" style="display: none;">
                                    <h3>{{ $entry['item']->item }}</h3>
                                    <table class="table table-striped" id="datatables-reponsive-suppliers">
                                        <thead>
                                            <tr>
                                                <th>Supplier</th>
                                                <th>Rate</th>
                                                <th>Last Purchase</th>
                                                <th>Remarks</th>
                                                <th>Select</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allSuppliers as $supplier)
                                                @php
                                                    $linkedSupplier = $entry['suppliers']->firstWhere('id', $supplier->id);
                                                    $showPrice = $linkedSupplier?->pivot->purchase_price ?? $supplier->purchase_price ?? 'N/A';
                                                    $isRecommended = $linkedSupplier && $linkedSupplier->id == $entry['recommended_supplier']?->id;
                                                    $isPOGenerated = isset($entry['is_po_generated']) && $entry['is_po_generated'] === true;
                                                @endphp
                                                <tr>
                                                    <td>{{ $supplier->name }}</td>
                                                    <td>{{ $showPrice }}</td>
                                                    {{-- <td>{{ $linkedSupplier?->pivot->purchase_price ?? 'N/A' }}</td> --}}
                                                    <td>
                                                        @if($isRecommended && $isPOGenerated)
                                                            <i class="fas fa-check-circle" style="color: #153d77;"></i>
                                                        @endif
                                                    </td>
                                                    <td><input type="text" name="remarks[{{ $supplier->id }}]" class="form-control"></td>
                                                    <td>
                                                        <input type="radio"
                                                            name="selected_supplier[{{ $entry['item']->id }}]"
                                                            value="{{ $supplier->id }}"
                                                            {{ $isRecommended ? 'checked' : '' }}>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="mt-3 d-flex">
                        <button type="submit" class="btn btn-primary"><b>Save</b></button>
                        {{-- <button type="submit" name="action" value="save_generate" class="btn btn-primary" style="margin-left:20px;"><b>Save & Generate Invoice</b></button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .highlighted-row {
        background-color: #153d7766 !important;
    }

</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.item-row').on('click', function() {
            var itemId = $(this).data('item-id');
            $('.item-row').removeClass('highlighted-row');
            $(this).addClass('highlighted-row');
            $('.supplier-section').hide();
            $('#supplier-section-' + itemId).slideDown();
        });
    });
</script>
 <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive-suppliers").DataTable({
                pageLength: 20,
                lengthMenu: [
                    [20, 40, 60, 80, 100],
                    [20, 40, 60, 80, 100]
                ],
                // responsive: true
            });
        });
    </script>
@endsection
