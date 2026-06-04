@extends('layout.master')
@section('title', 'Demand Issue')
@section('header-title', 'Demand Issue')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold">
                            @if ($contractors->count() == 1)
                                {{ $contractors->first()->name }}
                            @else
                                {{ $contractors->pluck('name')->join(', ') }}
                            @endif
                        </h5>
                        <span class="fw-semibold">STORE DEMAND FORM</span>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <p><strong>Demand No:</strong> {{ $itemDemand->demand_no }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p><strong>Dated:</strong> {{ \Carbon\Carbon::parse($itemDemand->date)->format('d-m-Y') }}</p>
                        </div>
                    </div>
                    <form action="{{ route('itemDemand.issued', $itemDemand->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">S.No</th>
                                <th>Description</th>
                                <th>Deno/Unit</th>
                                <th>Qty Allowed</th>
                                <th>Qty Demand</th>
                                <th>Previous Issued</th>
                                <th>Current Issued</th>
                                <th>Progressive Total</th>
                                <th>Balance Qty</th>
                                <th>Over & Above</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itemDemand->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->item }}</td>
                                    <td>{{ $item->deno ?? '-' }}</td>
                                    <td>{{ $item->pivot->allocated_qty ?? '-' }}</td>
                                    <td><span class="badge bg-danger fs-6">{{ $item->pivot->item_qty }}</span></td>
                                    <td>{{ $item->calculated_previous_issued  ?? '-' }}</td>
                                    <td>
                                        <input type="number" name="current_issued[{{ $item->id }}]"
                                            class="form-control form-control-sm"
                                            value="{{ $item->pivot->current_issued ?? 0 }}"  step="any"
                                           @if(($item->pivot->current_issued ?? 0) > 0) disabled @endif>
                                    </td>
                                    <td>{{ $item->calculated_progressive_total  ?? '-' }}</td>
                                    <td>{{ $item->calculated_balance_qty  ?? '-' }}</td>
                                    <td>
                                        @if (!empty($item->pivot->over_qty))
                                            {{ $item->pivot->over_qty }} <br>
                                            {{ $item->pivot->over_description }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" name="comments[{{ $item->id }}]" class="form-control "
                                            value="{{ $item->pivot->over_description ?? '' }}" placeholder="Add Comment">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('itemDemand.index') }}" class="btn btn-secondary btn-sm">⬅ Back</a>
                    {{-- <button onclick="window.print()" class="btn btn-primary btn-sm">🖨 Print</button> --}}
                    {{-- @if ($itemDemand->status == 'issued' || $itemDemand->status == 'received') --}}
                    {{-- <button class="btn btn-sm btn-primary" disabled>Issued</button> --}}
                    {{-- @else --}}
                    <button type="submit" class="btn btn-sm btn-primary"> Mark as Issued</button>
                    {{-- @endif --}}
                </div>
             </form>
            </div>
        </div>
    </div>

@endsection
