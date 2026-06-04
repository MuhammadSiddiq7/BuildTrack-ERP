@extends('layout.master')
@section('title', 'Project List')
@section('header-title', 'Project List')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">All Projects</h3>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <table class="table table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Project Name</th>
                        <th>Number</th>
                        <th>Houses</th>
                        <th>Location</th>
                        <th>Bank</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $index => $project)
                        <tr class="align-middle">
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $project->project_name ?? 'N/A' }}</td>
                            <td>{{ $project->project_number ?? 'N/A' }}</td>
                            <td>{{ $project->number_of_houses ?? 'N/A' }}</td>
                            <td>{{ $project->project_location ?? 'N/A' }}</td>
                            <td>{{ $project->bank->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('project.dashboard', $project->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted">No projects found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Optional: DataTables for sorting/search --}}
@push('scripts')
<script>
    $(document).ready(function () {
        $('.table').DataTable({
            pageLength: 10,
            lengthChange: false,
            searching: true,
            ordering: true,
        });
    });
</script>
@endpush
@endsection
