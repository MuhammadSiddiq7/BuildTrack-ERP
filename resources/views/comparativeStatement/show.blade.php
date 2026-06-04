@extends('layout.master')
@section('title', 'View Comparative Statement')
@section('header-title', 'View Comparative Statement')

@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h4 class="mb-0">Comparative Statement for Demand #{{ $itemDemand->demand_no }}</h4>
                </div>
                <div class="card-body">

    @foreach($itemDemand->items as $item)
        <div class="card mb-4">
            <div class="card-header"><strong>{{ $item->item }} ({{ $item->size }})</strong></div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Remarks</th>
                            <th>Supplier</th>
                            <th>Description</th>
                            <th>Deno</th>
                            <th>Quantity</th>
                            <th>Rate</th>
                            <th>Amount</th>
                            <th>Lowest</th>
                        </tr>   
                    </thead>
                    <tbody>
                        @php
                            $itemQuotations = $quotations[$item->id] ?? collect();
                            $lowestRate = $itemQuotations->min('rate');
                        @endphp
                        @foreach($itemQuotations as $q)
                            <tr @if($q->rate == $lowestRate) style="background-color:#d4edda;" @endif>
                                <td>{{ $q->remarks ?? '-' }}</td>
                                <td>{{ $q->supplier_name ?? '-' }}</td>
                                <td>{{ $q->description ?? '-' }}</td>
                                <td>{{ $q->deno ?? '-' }}</td>
                                <td>{{ $q->quantity ?? '-' }}</td>
                                <td>{{ number_format($q->rate, 2) ?? '-' }}</td>
                                <td>{{ number_format($q->amount, 2) ?? '-' }}</td>
                                <td>
                                    @if($q->rate == $lowestRate)
                                        <i class="fas fa-check-circle text-success"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
