@extends('layout.master')
@section('title', 'View Project')
@section('header-title', 'Project Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i> Project Details</h5>
            </div>

            <div class="card-body p-4">
                {{-- Project Info --}}
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted fw-semibold mb-1">Project Name</h6>
                        <p class="mb-0 fs-6">{{ $project->project_name ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted fw-semibold mb-1">Project Number</h6>
                        <p class="mb-0 fs-6">{{ $project->project_number ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted fw-semibold mb-1">Number of Houses</h6>
                        <p class="mb-0 fs-6">{{ $project->number_of_houses ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted fw-semibold mb-1">Project Location</h6>
                        <p class="mb-0 fs-6">{{ $project->project_location ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted fw-semibold mb-1">Bank</h6>
                        <p class="mb-0 fs-6">{{ $project->bank->name ?? 'N/A' }}</p>
                    </div>
                </div>

                <hr class="my-4">

                {{-- House Categories --}}
                <h5 class="text-primary mb-3">
                    <i class="bi bi-list-task me-2"></i> Categories & Houses
                </h5>

                @forelse ($houseProjects as $index => $houseProject)
                @if ($houseProject->house_type_id || $houseProject->site_square_yard)    
                 <div class="border rounded-4 shadow-sm p-4 mb-4 bg-light-subtle">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <h6 class="text-muted fw-semibold mb-1">House Type</h6>
                                <p class="mb-0">{{ $houseProject->house_type_id ?? 'N/A' }}</p>
                            </div>

                            <div class="col-md-4">
                                <h6 class="text-muted fw-semibold mb-1">Site Square Yard</h6>
                                <p class="mb-0">{{ $houseProject->site_square_yard ?? 'N/A' }}</p>
                            </div>

                            <div class="col-md-4">
                                <h6 class="text-muted fw-semibold mb-1">Warehouse</h6>
                                <p class="mb-0">
                                    @php
                                        $warehouseName = $warehouses->firstWhere('id', $houseProject->warehouse_id)->name ?? 'N/A';
                                    @endphp
                                    {{ $warehouseName }}
                                </p>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-muted fw-semibold mb-1">Contractors</h6>
                                <p class="mb-0">
                                    {{ $houseProject->contractors->pluck('name')->join(', ') ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-muted fw-semibold mb-1">Total Houses</h6>
                                <p class="mb-0">{{ $houseProject->total_houses ?? 'N/A' }}</p>
                            </div>

                            <div class="col-md-12">
                                <h6 class="text-muted fw-semibold mb-1">Houses</h6>
                                <p>
                                    @forelse ($houseProject->houses as $house)
                                        <span class="badge bg-secondary me-1 mb-1">House {{ $house->house_number }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                            </div>

                            <div class="col-md-12">
                                <h6 class="text-muted fw-semibold mb-1">Description</h6>
                                <p class="mb-0">{{ $houseProject->description ?? 'N/A' }}</p>
                            </div>

                            {{-- <div class="col-md-12">
                                <h6 class="text-muted fw-semibold mb-1">Available Items</h6>
                                <p class="mb-0">
                                    @if(!empty($houseProject->available_items) && count($houseProject->available_items) > 0)
                                        {{ collect($houseProject->available_items)->map(function ($item) {
                                            return $item['item']['name'] ?? $item['item_name'] ?? 'Unnamed Item';
                                        })->join(', ') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </p>
                            </div> --}}
                        </div>
                    </div>
                @endif
                @empty
                    <p class="text-muted">No house project details available.</p>
                @endforelse

                <div class="text-end mt-4">
                    <a href="{{ route('project.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Projects
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
