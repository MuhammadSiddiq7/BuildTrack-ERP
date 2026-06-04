@extends('layout.master')
@section('title', 'Comparative Statement')
@section('header-title', 'Comparative Statement')

@section('content')
<style>
    .modern-table thead th {
        background-color: #f0f2f5;
        font-weight: 600;
        color: #495057;
        vertical-align: middle;
    }

    .modern-table tbody tr {
        transition: background-color 0.2s ease-in-out;
    }

    .modern-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .card-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-active {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .status-inactive {
        background-color: #f8d7da;
        color: #842029;
    }

    .table-actions .btn {
        padding: 4px 10px;
        font-size: 0.8rem;
    }

    .card-header h5 {
        font-weight: 600;
        font-size: 1.1rem;
        color: #333;
    }

</style>

<div class="row">
    <div class="col-12">
        <div class="card card-modern">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                <h5 class="mb-0">📦 All Comparative Statement</h5>
                <!-- You can add filters/actions here later -->
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatables-reponsive" class="table table-bordered table-hover text-center modern-table" style="width:100%">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash"></i> S.No</th>
                                <th><i class="bi bi-calendar-event"></i> Date</th>
                                <th><i class="bi bi-currency-dollar"></i> Grand Total</th>
                                {{-- <th><i class="bi bi-toggle-on"></i> Status</th> --}}
                                {{-- <th><i class="bi bi-toggle-on"></i> Approval</th> --}}
                                <th><i class="bi bi-person-circle"></i> Created By</th>
                                <th><i class="bi bi-gear-fill"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comparativeStatements as $purchaseOrder)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $purchaseOrder->created_at ? $purchaseOrder->created_at->format('Y-m-d') : 'N/A' }}</td>
                                <td>{{ number_format($purchaseOrder->grand_total, 2) ?? 'N/A' }}</td>
                                {{-- <td>
                                    <span class="badge bg-success" {{ $purchaseOrder->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                        {{ ucfirst($purchaseOrder->status) }}
                                    </span>
                                </td> --}}
                                {{-- <td>
                                    @if ($purchaseOrder->approve_status == 'pending')
                                    <button class="btn btn-sm btn-primary approve-btn" data-id="{{ $purchaseOrder->id }}">
                                        Approved
                                    </button>
                                    @else
                                    <span class="badge bg-success">{{ ucfirst($purchaseOrder->approve_status) }}</span>
                                    @endif
                                </td> --}}

                                <td>{{ $purchaseOrder->creator->name ?? 'N/A' }}</td>
                                <td class="table-actions">
                                    @can('comparativeStatement_view')
                                    {{-- @if($purchaseOrder->approve_status == 'approved') --}}
                                    <a href="{{ route('comparativeStatement.view', $purchaseOrder->item_demand_id) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Comparative Statement">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    {{-- @else
                                    <span>Not Approve</span>
                                    @endif --}}
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Tooltip -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.getElementById('datatables-reponsive');

        table.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('approve-btn')) {
                const button = e.target;
                const id = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?'
                    , text: "Do you want to approve this Comparative Statement?"
                    , icon: 'question'
                    , showCancelButton: true
                    , confirmButtonColor: '#28a745'
                    , cancelButtonColor: '#d33'
                    , confirmButtonText: 'Yes, Approve'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/comparativeStatement/approve/${id}`, {
                                method: 'POST'
                                , headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    , 'Content-Type': 'application/json'
                                , }
                            , })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success'
                                        , title: 'Approved!'
                                        , text: 'Status has been changed to approved.'
                                        , timer: 1500
                                        , showConfirmButton: false
                                    });

                                    // Replace button with approved badge
                                    button.outerHTML = `<span class="badge bg-success">Approved</span>`;

                                    // Reload the page
                                    window.location.reload();
                                } else {
                                    Swal.fire({
                                        icon: 'error'
                                        , title: 'Oops!'
                                        , text: data.message || 'Failed to approve.'
                                    });
                                }
                            })
                            .catch(() => {
                                Swal.fire({
                                    icon: 'error'
                                    , title: 'Error!'
                                    , text: 'Something went wrong while approving.'
                                });
                            });
                    }
                });
            }
        });
    });

</script>
{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.getElementById('datatables-reponsive');

        table.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('approve-btn')) {
                const button = e.target;
                const id = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?'
                    , text: "Do you want to approve this Comparative Statement?"
                    , icon: 'question'
                    , showCancelButton: true
                    , confirmButtonColor: '#28a745'
                    , cancelButtonColor: '#d33'
                    , confirmButtonText: 'Yes, Approve'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/comparativeStatement/approve/${id}`, {
                                method: 'POST'
                                , headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    , 'Content-Type': 'application/json'
                                , }
                            , })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success'
                                        , title: 'Approved!'
                                        , text: 'Status has been changed to approved.'
                                        , timer: 1500
                                        , showConfirmButton: false
                                    });

                                    // Replace button with approved badge
                                    button.outerHTML = `<span class="badge bg-success">Approved</span>`;
                                } else {
                                    Swal.fire({
                                        icon: 'error'
                                        , title: 'Oops!'
                                        , text: data.message || 'Failed to approve.'
                                    });
                                }
                            })
                            .catch(() => {
                                Swal.fire({
                                    icon: 'error'
                                    , title: 'Error!'
                                    , text: 'Something went wrong while approving.'
                                });
                            });
                    }
                });
            }
        });
    });

</script> --}}

@endsection
