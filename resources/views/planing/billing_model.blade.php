<div class="modal fade" id="billingModal-{{ $contractorId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Billing Details — {{ $contractor->name ?? 'N/A' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>House No</th>
                            <th>Activity</th>
                            <th>Requested By</th>
                            <th>Percentage</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Approve Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($billings as $loopIndex => $plan)
                            <tr>
                                <td>{{ $loopIndex + 1 }}</td>
                                <td>{{ $plan->planActivity->plan->house->house_number ?? 'N/A' }}</td>
                                <td>{{ $plan->planActivity->activity->name ?? 'N/A' }}</td>
                                <td>{{ $plan->requester->name ?? 'N/A' }}</td>
                                <td>{{ $plan->percentage ?? 'N/A' }}%</td>
                                <td>{{ $plan->date ?? 'N/A' }}</td>
                                <td>{{ $plan->amount ?? 'N/A' }}</td>
                                <td>
                                    @if ($plan->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif ($plan->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>

                                <td>{{ $plan->approved_at ?? 'N/A' }}</td>
                                <td>
                                    @if ($plan->status == 'pending')
                                    @can('billing_approve')
                                        <form action="{{ route('billing.approve', $plan->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                        </form>
                                    @endcan
                                        @can('billing_reject')
                                        <form action="{{ route('billing.reject', $plan->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </button>
                                        </form>
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
