@extends('layout.master')
@section('title', 'Inspection Report')
@section('header-title', 'Inspection Report')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0">

        <div class="card-body">
            <div class="card-header d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Inspection Report - {{ $ir->ir_no ?? 'N/A' }}</h5>
                <span>{{ $ir->inspection_date ?? 'N/A' }}</span>
            </div>

            <div class="row mb-3">
                <div class="col-md-6"><strong>Banglow No:</strong> {{ $ir->banglow_no ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>House Type:</strong> {{ $ir->house_type ?? 'N/A' }}</div>
            </div>
            <div class="row mb-3">
                @if ($activity->activity->parent)
                <div class="col-md-12">
                    <strong>Activity:</strong>
                    <span class="text-primary"><strong>{{ $activity->activity->parent->name }}</strong></span>
                    <br>
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ↳ <strong>{{ $activity->activity->name }}</strong></span>
                </div>
                @else
                <div class="col-md-12">
                    <strong>Activity:</strong>
                    {{ $activity->activity->name }}
                </div>
                @endif
            </div>

            <div class="mb-3">
                <strong>Description of Work:</strong>
                <p class="border rounded p-2">{{ $ir->description_of_work ?? 'N/A' }}</p>
            </div>

            {{-- <div class="mb-3">
                <strong>Remarks (General):</strong>
                <p class="border rounded p-2">{{ $ir->remarks ?? 'No remarks yet.' }}</p>
                        </div> --}}
        {{-- <div class="mb-3">
            <strong>Remarks (General):</strong>
            <form action="{{ route('inspection_reports.updateGeneralRemarks', $ir->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <textarea name="remarks" class="form-control mt-2" rows="3">{{ $ir->remarks ?? '' }}</textarea>
                <button type="submit" class="btn btn-primary btn-sm mt-2">Save Remarks</button>
            </form>
        </div> --}}

        <hr>
        <h5 class="text-center text-primary fw-bold mb-3">Approval Status</h5>

        <table class="table table-bordered align-middle text-center">
            <thead>
                <tr>
                    <!-- <th>Contractor</th> -->
                    <th>Consultant</th>
                    <th>PM</th>
                    <th>ADCC</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    {{-- <td>
                        @if ($ir->contractor_status == 'pending' && (auth()->user()->hasRole('Contractor') || auth()->user()->hasRole('Admin')))
                        <form action="{{ route('inspection_reports.contractor.approve', $ir->id) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="bi bi-check-lg"></i>
                    </button>
                    </form>
                    <form action="{{ route('inspection_reports.contractor.reject', $ir->id) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </form>
                    @else
                    <span class="badge bg-{{ $ir->contractor_status == 'approved' ? 'success' : ($ir->contractor_status == 'rejected' ? 'danger' : 'warning') }}">
                        {{ ucfirst($ir->contractor_status) }}
                    </span>
                    @endif
                    @if ($ir->contractor_remarks)
                    <p class="text-muted small mt-2">{{ $ir->contractor_remarks }}</p>
                    @endif
                    </td> --}}
                    <!-- <td>
                        @if ($ir->contractor_status == 'pending' && (auth()->user()->hasRole('Contractor') || auth()->user()->hasRole('Admin')))
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#remarksModal" data-action="{{ route('inspection_reports.contractor.approve', $ir->id) }}">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#remarksModal" data-action="{{ route('inspection_reports.contractor.reject', $ir->id) }}">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        @else
                        <span class="badge bg-{{ $ir->contractor_status == 'approved' ? 'success' : ($ir->contractor_status == 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($ir->contractor_status) }}
                        </span>
                        @endif
                        @if ($ir->contractor_remarks)
                        <p class="text-muted small mt-2">{{ $ir->contractor_remarks }}</p>
                        @endif
                    </td> -->
                    <td>
                        @if ($ir->consultant_status == 'pending' && (auth()->user()->hasRole('Consultant') || auth()->user()->hasRole('Admin')))
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#remarksModal" data-action="{{ route('inspection_reports.consultant.approve', $ir->id) }}">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#remarksModal" data-action="{{ route('inspection_reports.consultant.reject', $ir->id) }}">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        @else
                        <span class="badge bg-{{ $ir->consultant_status == 'approved' ? 'success' : ($ir->consultant_status == 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($ir->consultant_status) }}
                        </span>
                        @endif
                        @if ($ir->consultant_remarks)
                        <p class="text-muted small mt-2">{{ $ir->consultant_remarks }}</p>
                        @endif
                    </td>
                    <td>
                        @if ($ir->pm_status == 'pending' && (auth()->user()->hasRole('Project Manager') || auth()->user()->hasRole('Admin')))
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#remarksModal" data-action="{{ route('inspection_reports.pm.approve', $ir->id) }}">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#remarksModal" data-action="{{ route('inspection_reports.pm.reject', $ir->id) }}">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        @else
                        <span class="badge bg-{{ $ir->pm_status == 'approved' ? 'success' : ($ir->pm_status == 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($ir->pm_status) }}
                        </span>
                        @endif
                        @if ($ir->pm_remarks)
                        <p class="text-muted small mt-2">{{ $ir->pm_remarks }}</p>
                        @endif
                    </td>
                    <td>
                        @if ($ir->adcc_status == 'pending' && (auth()->user()->hasRole('ADCC') || auth()->user()->hasRole('Admin')))
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#remarksModal" data-action="{{ route('inspection_reports.adcc.approve', $ir->id) }}">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#remarksModal" data-action="{{ route('inspection_reports.adcc.reject', $ir->id) }}">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        @else
                        <span class="badge bg-{{ $ir->adcc_status == 'approved' ? 'success' : ($ir->adcc_status == 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($ir->adcc_status) }}
                        </span>
                        @endif
                        @if ($ir->adcc_remarks)
                        <p class="text-muted small mt-2">{{ $ir->adcc_remarks }}</p>
                        @endif
                    </td>
                </tr>
                </tr>
            </tbody>
        </table>
        <div class="mt-4 text-end">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>
</div>
<!-- Remarks Modal -->
<div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="remarksForm">
            @csrf
            @method('POST')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="remarksModalLabel">Add Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea name="remarks" class="form-control" rows="3" placeholder="Enter your remarks..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const remarksModal = document.getElementById('remarksModal');
    remarksModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const action = button.getAttribute('data-action');
        const form = document.getElementById('remarksForm');
        form.action = action;
    });

</script>


@endsection
