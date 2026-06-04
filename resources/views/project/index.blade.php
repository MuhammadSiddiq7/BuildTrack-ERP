@extends('layout.master')
@section('title', 'Project')
@section('header-title', 'Projects')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-kanban me-2 text-primary"></i>Projects List
                    </h5>

                    <div class="d-flex align-items-center gap-2">
                        @can('project_create')
                            <a href="{{ route('project.create') }}" class="btn btn-sm btn-success">
                                <i class="bi bi-plus-circle me-1"></i> Create Project
                            </a>
                        @endcan

                        @can('project_trash_view')
                            <a href="{{ route('project.trash') }}"
                                class="btn btn-sm btn-outline-danger position-relative">
                               <i class="bi bi-trash-fill me-1"></i>
                                <span>Trash</span>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $trashproject ?? 0 }}</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatables-reponsive" class="table table-bordered table-hover align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-hash"></i> S:NO</th>
                                    <th><i class="bi bi-building"></i> Project Name</th>
                                    <th><i class="bi bi-card-list"></i> Project Number</th>
                                    <th><i class="bi bi-geo-alt"></i> Project Location</th>
                                    <th><i class="bi bi-gear"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $project)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $project->project_name ?? 'N/A' }}</td>
                                        <td>{{ $project->project_number ?? 'N/A' }}</td>
                                        <td>{{ $project->project_location ?? 'N/A' }}</td>
                                        <td class="d-flex justify-content-center gap-1 flex-wrap">

                                           {{-- @can('project_create') --}}
                                            <a href="{{ route('project.categories.create', $project->id) }}" class="btn btn-sm btn-success">
                                                <i class="bi bi-plus-circle me-1"></i> Add Category
                                            </a>
                                        {{-- @endcan --}}



                                            {{-- @can('project_assign_item')
                                                <a href="{{ route('project.assignItem', $project->id) }}"
                                                    class="btn btn-sm btn-success" title="Assign Item">
                                                    <i class="bi bi-box-arrow-in-right me-1"></i><span>Assign</span>
                                                </a>
                                            @endcan --}}

                                            @can('project_edit')
                                                <a href="{{ route('project.edit', $project->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                </a>
                                            @endcan

                                                {{-- @can('project_view') --}}
                                                    <a href="{{ route('project.view', $project->id) }}"
                                                        class="btn btn-sm btn-info" title="View">
                                                        <i class="bi bi-eye me-1"></i>
                                                    </a>
                                                {{-- @endcan --}}


                                            @can('project_trash')
                                                <form action="{{ route('project.delete', $project->id) }}" method="POST"
                                                    style="display:inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete this project?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="bi bi-trash me-1"></i>
                                                    </button>
                                                </form>
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
@endsection
