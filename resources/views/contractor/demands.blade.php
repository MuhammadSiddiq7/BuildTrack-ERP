@extends('layout.master')
@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Contractor: {{ $contractor->name }}</h4>

    @forelse($itemDemands as $demand)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Demand #{{ $demand->id }} — Project: {{ $demand->project->project_name ?? 'N/A' }}
            </div>
            <div class="card-body">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>S.No</th>
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
                        @foreach ($demand->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->item }}</td>
                                <td>{{ $item->deno ?? '-' }}</td>
                                <td>{{ $item->pivot->allocated_qty ?? '-' }}</td>
                                <td>{{ $item->pivot->item_qty }}</td>
                                <td>{{ $item->calculated_previous_issued ?? '-' }}</td>
                                <td>{{ $item->pivot->current_issued ?? '-' }}</td>
                                <td>{{ $item->calculated_progressive_total ?? '-' }}</td>
                                <td>{{ $item->calculated_balance_qty ?? '-' }}</td>
                                <td>
                                    @if (!empty($item->pivot->over_qty))
                                        {{ $item->pivot->over_qty }} <br>
                                        {{ $item->pivot->over_description }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $item->pivot->comments ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-info">No demands found for this contractor.</div>
    @endforelse
</div>
@endsection
