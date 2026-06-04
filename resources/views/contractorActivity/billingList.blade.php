{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contractor Billing Portal</title>
    <link href="{{ asset('assets/css/modern.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="p-4">

<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-info text-white d-flex justify-content-between">
            <h5 class="mb-0">Billing Activities - {{ $contractor->name }}</h5>
            <a href="{{ route('contractor.billing.form') }}" class="btn btn-light btn-sm">← Back</a>
        </div>

        <div class="card-body">
            @foreach ($plans as $plan)
                <h4 class="fw-bold mt-4">
                    House: {{ $plan->house->house_number ?? 'N/A' }} ({{ $plan->type->house_type_id ?? 'N/A' }})
                </h4>
                <table class="table table-bordered table-striped">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Activity</th>
                            <th>Yardstick</th>
                            <th>Amount</th>
                            <th>CEO Progress</th>
                            <th>Billed (%)</th>
                            <th>Remaining</th>
                            <th>Bill Approved</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plan->planActivities as $index => $pa)
                            @php
                                $billed = $pa->billingRequests->sum('percentage');
                                $remaining = $pa->ceo_progress - $billed;
                            @endphp
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pa->activity->name ?? 'N/A' }}</td>
                                <td>{{ $pa->activity->yardstick ?? 'N/A' }}</td>

                                <td>
                                    @if ($pa->billingRequests->count() > 0)
                                        <ul class="list-unstyled mb-0 text-start">
                                            @foreach ($pa->billingRequests as $br)
                                                <li>Rs. {{ number_format($br->amount) }}</li>
                                            @endforeach
                                        </ul>
                                        @else
                                        0
                                        @endif
                                    </td>

                                    <td>{{ $pa->ceo_progress }}%</td>
                                    <td>
                                        @if ($pa->billingRequests->count() > 0)
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($pa->billingRequests as $br)
                                            <li>{{ $br->percentage }}%</li>
                                            @endforeach
                                        </ul>
                                        @else
                                        0%
                                        @endif
                                    </td>

                                    <td>{{ $remaining > 0 ? $remaining : 0 }}%</td>

                                     <td>
                                        @if ($pa->billingRequests->count() > 0)
                                            @foreach ($pa->billingRequests as $br)
                                                <ul class="list-unstyled mb-0 text-center"><span class="badge
                                                    bg-{{ $br->status == 'approved' ? 'success' : ($br->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($br->status) }}
                                                </span></ul>
                                            @endforeach
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                    @php
                                        $ir = $pa->inspectionReports->first();
                                        $irStatus = 'not_requested';
                                        if ($ir) {
                                            $isApproved =
                                                $ir->contractor_status === 'approved' &&
                                                $ir->consultant_status === 'approved' &&
                                                $ir->adcc_status === 'approved' &&
                                                $ir->pm_status === 'approved';

                                            $isRejected =
                                                in_array('rejected', [
                                                    $ir->contractor_status,
                                                    $ir->consultant_status,
                                                    $ir->adcc_status,
                                                    $ir->pm_status
                                                ]);

                                            $isPending =
                                                in_array('pending', [
                                                    $ir->contractor_status,
                                                    $ir->consultant_status,
                                                    $ir->adcc_status,
                                                    $ir->pm_status
                                                ]);

                                            if ($isApproved) {
                                                $irStatus = 'approved';
                                            } elseif ($isRejected) {
                                                $irStatus = 'rejected';
                                            } elseif ($isPending) {
                                                $irStatus = 'pending';
                                            } else {
                                                $irStatus = 'unknown';
                                            }
                                        }
                                    @endphp

                                    @if ($remaining > 0)
                                        @if ($irStatus === 'approved')
                                            <form action="{{ route('contractor.billing.request', $pa->id) }}" method="POST" class="mb-2">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success ">
                                                    Request Billing
                                                </button>
                                            </form>
                                        @elseif ($irStatus === 'pending')
                                            <span class="badge bg-warning">IR Pending Approval</span>
                                        @elseif ($irStatus === 'rejected')
                                            <span class="badge bg-danger">IR Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">IR Not Requested</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Done</span>
                                    @endif
                                    @if (!$ir)
                                        <form action="{{ route('contractor.ir.request', $pa->id) }}" method="POST" class="mt-2">
                                            @csrf
                                            <input type="hidden" name="amount" value="{{ $pa->amount }}">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                Request IR
                                            </button>
                                        </form>
                                    @endif
                                </td>

                                        <td>
                                            @php
                                                $ir = $pa->inspectionReports->first();
                                                $irApproved = $ir && $ir->contractor_status === 'approved'
                                                && $ir->consultant_status === 'approved' && $ir->adcc_status === 'approved'
                                                 && $ir->pm_status === 'approved';
                                            @endphp
                                            @if ($remaining > 0)
                                                @if ($irApproved)
                                                    <form action="{{ route('contractor.billing.request', $pa->id) }}" method="POST" class="mb-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success w-100">
                                                            Request Billing
                                                        </button>
                                                    </form>
                                                @elseif($ir && $ir->approval_status === 'noted')
                                                    <span class="badge bg-warning">IR Pending Approval</span>
                                                @elseif($ir && $ir->approval_status === 'rejected')
                                                    <span class="badge bg-danger">IR Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">IR Not Requested</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Completed</span>
                                            @endif
                                            @if (!$ir)
                                                <form action="{{ route('contractor.ir.request', $pa->id) }}" method="POST" class="mt-2">
                                                    @csrf
                                                    <input type="hidden" name="amount" value="{{ $pa->amount }}">
                                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                                        Request IR
                                                    </button>
                                                </form>
                                            @endif
                                        </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
</div>

</body>
</html> --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contractor Billing Portal</title>
    <link href="{{ asset('assets/css/modern.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.min.js"></script>

    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.3em 0.8em;
            border-radius: 8px;
            border: none;
            margin: 2px;
        }
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px;
            border: 1px solid #ccc;
            padding: 4px 8px;
        }
        .dataTables_wrapper .dataTables_length select {
            border-radius: 6px;
            padding: 4px 6px;
        }
    </style>
</head>
<body class="p-4">

<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-info text-white d-flex justify-content-between">
            <h5 class="mb-0">Billing Activities - {{ $contractor->name }}</h5>
            <a href="{{ route('contractor.billing.form') }}" class="btn btn-light btn-sm">← Back</a>
        </div>

        <div class="card-body">
            @foreach ($plans as $plan)
                <h4 class="fw-bold mt-4">
                    House: {{ $plan->house->house_number ?? 'N/A' }} ({{ $plan->type->house_type_id ?? 'N/A' }})
                </h4>

                <div class="table-responsive">
                    <table id="billingTable_{{ $plan->id }}" class="table table-bordered table-striped align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Activity</th>
                                <th>Yardstick</th>
                                <th>Amount</th>
                                <th>Billed (%)</th>
                                <th>Remaining</th>
                                <th>Bill Approved</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plan->planActivities as $index => $pa)
                                @php
                                    $billed = $pa->billingRequests->sum('percentage');
                                    $remaining_list = 100 - $pa->ceo_progress;
                                    $remaining = $pa->ceo_progress - $billed;
                                    $ir = $pa->inspectionReports->first();
                                    $irStatus = 'not_requested';
                                    if ($ir) {
                                        $isApproved =
                                             $ir->consultant_status === 'approved'
                                            && $ir->adcc_status === 'approved'
                                            && $ir->pm_status === 'approved';
                                        $isRejected = in_array('rejected', [
                                             $ir->consultant_status,
                                            $ir->adcc_status, $ir->pm_status
                                        ]);
                                        $isPending = in_array('pending', [
                                            $ir->consultant_status,
                                            $ir->adcc_status, $ir->pm_status
                                        ]);
                                        if ($isApproved) $irStatus = 'approved';
                                        elseif ($isRejected) $irStatus = 'rejected';
                                        elseif ($isPending) $irStatus = 'pending';
                                    }
                                @endphp

                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pa->activity->name ?? 'N/A' }}</td>
                                    <td>{{ $pa->activity->yardstick ?? 'N/A' }}</td>

                                    <td>
                                        @if ($pa->billingRequests->count() > 0)
                                            <ul class="list-unstyled mb-0 text-start">
                                                @foreach ($pa->billingRequests as $br)
                                                    <li>Rs. {{ number_format($br->amount) }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            0
                                        @endif
                                    </td>

                                    <td>
                                        @if ($pa->billingRequests->count() > 0)
                                            <ul class="list-unstyled mb-0">
                                                @foreach ($pa->billingRequests as $br)
                                                    <li>{{ $br->percentage }}%</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            0%
                                        @endif
                                    </td>
                                    <td>{{ $remaining_list }}</td>

                                    <td>
                                        @if ($pa->billingRequests->count() > 0)
                                            @foreach ($pa->billingRequests as $br)
                                                <ul class="list-unstyled mb-0 text-center">
                                                    <span class="badge bg-{{ $br->status == 'approved' ? 'success' : ($br->status == 'rejected' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($br->status) }}
                                                    </span>
                                                </ul>
                                            @endforeach
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($remaining > 0)
                                            @if ($irStatus === 'approved')
                                                <form action="{{ route('contractor.billing.request', $pa->id) }}" method="POST" class="mb-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Request Billing
                                                    </button>
                                                </form>
                                            @elseif ($irStatus === 'pending')
                                                <span class="badge bg-warning">IR Pending Approval</span>
                                            @elseif ($irStatus === 'rejected')
                                                <span class="badge bg-danger">IR Rejected</span>
                                            @else
                                                <span class="badge bg-secondary">IR Not Requested</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Done</span>
                                        @endif

                                        @if (!$ir)
                                            <form action="{{ route('contractor.ir.request', $pa->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                <input type="hidden" name="amount" value="{{ $pa->amount }}">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Request IR
                                                </button>
                                            </form>
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
</div>
<script>
    $(document).ready(function() {
        $('table[id^="billingTable_"]').each(function() {
            $(this).DataTable({
                pageLength: 10,
                order: [],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: { previous: "Prev", next: "Next" },
                    zeroRecords: "No matching records found"
                }
            });
        });
    });
</script>

</body>
</html>
