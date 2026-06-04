@extends('layout.master')
@section('title', 'Comparative Statement')
@section('header-title', 'Comparative Statement')
@section('content')
<style>
    .supplier-section {
        margin-top: 30px;
        padding: 20px;
        background-color: #f9f9fb;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }

    .table thead th {
        background-color: #f1f1f1;
        color: #333;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 14px;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .item-row:hover {
        background-color: #f2f8ff !important;
        cursor: pointer;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.06);
    }

    .form-control,
    input[type="radio"] {
        border-radius: 8px;
    }

    .supplier-section h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #153d77;
        border-left: 5px solid #153d77;
        padding-left: 10px;
    }

    .btn-primary {
        padding: 8px 20px;
        font-weight: bold;
        border-radius: 8px;
    }

</style>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('comparativeStatement.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="item_demand_id" value="{{ $itemDemand->id }}">

                    @foreach($itemDemand->items as $item)
                    @php
                    $itemQuotations = $item->quotations;
                    $lowestRate = $itemQuotations->min('rate');
                    @endphp

                    <div class="card mb-4">
                        <div class="card-header">
                            {{ $item->item }} ({{ $item->size }}) - Qty: {{ $item->pivot->item_qty }}
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Description</th>
                                        <th>Deno</th>
                                        <th>Total Qty Required</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>Remarks</th>
                                        <th>Attachment</th>
                                        <th>Lowest</th>
                                        <th>Select</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($itemQuotations as $q)
                                    <tr @if($q->rate == $lowestRate) style="background-color:#d4edda;" @endif>
                                        <td>{{ $q->supplier_name ?? '-' }}</td>
                                        <td>{{ $q->description ?? '-' }}</td>
                                        <td>{{ $q->deno ?? '-' }}</td>
                                        <td>{{ number_format($q->quantity, 2) ?? '-' }}</td>
                                        <td>{{ number_format($q->rate, 2) ?? '-' }}</td>
                                        <td>{{ number_format($q->amount, 2) ?? '-' }}</td>
                                        <td>
                                            <input type="text" name="remarks[{{ $item->id }}][{{ $q->id }}]" class="form-control">
                                        </td>
                                        <td>
                                            <input type="file" name="attachments[{{ $item->id }}][{{ $q->id }}][]" multiple class="form-control">
                                        </td>
                                        <td>
                                            @if($q->rate == $lowestRate)
                                            <i class="fas fa-check-circle text-success"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="radio" name="selected_supplier[{{ $item->id }}]" value="{{ $q->id }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach


                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Comparative Statement
                        </button>
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


<style>
    .highlighted-row {
        background-color: #153d7766 !important;
    }

</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.item-row').on('click', function() {
            var itemId = $(this).data('item-id');
            var contractorId = $(this).data('contractor-id');
            $('.item-row').removeClass('highlighted-row');
            $(this).addClass('highlighted-row');
            $('.supplier-section').hide();
            $('.supplier-section-' + itemId + '-' + contractorId).slideDown();
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
