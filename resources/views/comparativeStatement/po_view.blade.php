@extends('layout.master')
@section('title', 'Comparative Statement')
@section('header-title', 'Comparative Statement')
@section('content')
<style>
    .supplier-section {
        margin-top: 20px;
        border-top: 1px solid #ccc;
        padding-top: 10px;
    }

</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('comparativeStatement.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="card-body">
                            <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Contractor</th>
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
                                    $contractorId = $item->pivot->contractor_id;
                                    $itemId = $item->id;
                                    $allocatedQty = $item->projects->first()?->pivot?->quantity ?? 0;
                                    $requestedQty = \App\Models\ComparativeStatementItem::where('item_id', $itemId)
                                    ->where('contractor_id', $contractorId);
                                    $houseProject = \App\Models\HouseProject::find($contractorId);
                                    $contractor = App\Models\Contractor::find($item->pivot->contractor_id);
                                    @endphp
                                    <tr class="" data-item-id="{{ $item->id }}" data-house-project-id="{{ $item->pivot->contractor_id }}" style="cursor: pointer;">
                                        <td>{{ $contractor->name ?? 'N/A' }}</td>
                                        <td>{{ $item->item ?? 'N/A' }}</td>
                                        <td>{{ $item->size ?? 'N/A' }}</td>
                                        <td>{{ $item->deno ?? 'N/A' }}</td>
                                        <td>{{ $allocatedQty ?? 0 }}</td>
                                        <td>{{ $item->pivot->item_qty ?? 0 }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <hr>
                            @foreach($itemsWithSuppliers as $entry)
                            <div class="supplier-section supplier-section-{{ $entry['item']->id }}">
                                <h3>{{ $entry['item']->item }} ({{ $entry['item']->size }})</h3>
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>Copy</th>
                                            <th>Supplier</th>
                                            <th>Rate</th>
                                            <th>Qty</th>
                                            <th>Amount</th>
                                            <th>Recommended</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($entry['suppliers'] as $supplier)
                                        @php
                                        $isRecommended = $supplier->supplier_name === $entry['recommended_supplier'];
                                        @endphp
                                        <tr @if($isRecommended) style="background-color:#d4edda;" @endif>
                                            <td>
                                                @if($supplier->attachments->count())
                                                @foreach($supplier->attachments as $file)
                                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $file->file_path) }}" width="50" height="50">
                                                </a>
                                                @endforeach
                                                @else
                                                N/A
                                                @endif

                                            </td>
                                            <td>{{ $supplier->supplier_name }}</td>
                                            <td>{{ number_format($supplier->rate, 2) }}</td>
                                            <td>{{ number_format($supplier->quantity, 2) }}</td>
                                            <td>{{ number_format($supplier->total, 2) }}</td>
                                            <td>
                                                @if($isRecommended)
                                                <i class="fas fa-check-circle text-success"></i>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>
                @php
                $poKey = $item->id . '_' . $item->pivot->item_demand_id;
                @endphp
                <form action="{{ route('purchaseOrder.invoice') }}" method="GET">
                    @csrf
                    @if($comparativeStatementId)
                    <input type="hidden" name="comparative_statement_id" value="{{ $comparativeStatementId }}">
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <input type="hidden" name="item_demand_id" value="{{ $item->pivot->item_demand_id }}">
                    <input type="hidden" name="item_demand_name" value="{{ $item->item }}">
                    @endif
                    <button type="submit" class="btn btn-primary mt-3"><b>Comparative Statement</b></button>
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
            var houseProjectId = $(this).data('house-project-id');

            $('.item-row').removeClass('highlighted-row');
            $(this).addClass('highlighted-row');
            $('.supplier-section').hide();
            $('.supplier-section-' + itemId + '-' + houseProjectId).slideDown();
        });
    });

</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Datatables Responsive
        $("#datatables-reponsive-suppliers").DataTable({
            pageLength: 20
            , lengthMenu: [
                [20, 40, 60, 80, 100]
                , [20, 40, 60, 80, 100]
            ],
            // responsive: true
        });
    });

</script>
@endsection
