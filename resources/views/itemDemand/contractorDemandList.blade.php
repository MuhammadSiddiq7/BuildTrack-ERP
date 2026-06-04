<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contractor Demands</title>
    <link href="{{ asset('assets/css/modern.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/adcc.png') }}" />

</head>
<body class="p-4">
    <div class="container py-4">
        <h4 class="mb-4">Contractor: <strong>{{ $contractor->name }}</strong></h4>
        @forelse($itemDemands as $itemDemand)
        <div class="card shadow-sm p-4 mb-4">
            <div class="row mb-2">
                <div class="col-md-4">
                    <p><strong>Project:</strong> {{ $itemDemand->project->project_name }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Demand No:</strong> {{ $itemDemand->demand_no }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <p><strong>Dated:</strong> {{ \Carbon\Carbon::parse($itemDemand->date)->format('d-m-Y') }}</p>
                </div>
            </div>

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
                        <th>Actions</th>
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
                        <td>{{ $item->calculated_previous_issued ?? '-' }}</td>
                        <td><span class="badge bg-success fs-6">{{ $item->pivot->current_issued ?? '-' }}</span></td>
                        <td>{{ $item->calculated_progressive_total ?? '-' }}</td>
                        <td>{{ $item->calculated_balance_qty ?? '-' }}</td>
                        <td>
                            @if(!empty($item->pivot->over_qty))
                            {{ $item->pivot->over_qty }}<br>
                            {{ $item->pivot->over_description }}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if(empty($item->pivot->status) || $item->pivot->status != 'received')
                            @if(!empty($item->pivot->current_issued))
                            <form action="{{ route('demand.item.stockout', [$itemDemand->public_token, $item->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">Receive</button>
                            </form>
                            @else
                            <span class="badge bg-secondary">Not Issued</span>
                            @endif
                            @else
                            <span class="badge bg-success">Received</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @empty
        <div class="alert alert-warning text-center">
            No demands found for this contractor.
        </div>
        @endforelse
    </div>

</body>
</html>
