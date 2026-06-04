@extends('layout.master')
@section('title', 'Application')
@section('header-title', 'Application')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                @can('application_create')
                <a href="{{ route('application.create') }}" class="btn btn-primary">Create Application</a>
                @endcan
                @can('application_trash_view')
                <a href="{{ route('application.trash') }}" class="btn btn-danger d-flex align-items-center gap-2 ms-auto" title="Trashed Application">
                    <i class="bi bi-trash-fill"></i>
                    <span>Trash</span>
                    <span class="badge bg-light text-dark">{{ $trashCount ?? 0 }}</span>
                </a>
                @endcan
            </div>
            <div class="card-body">
                <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Father Name</th>
                            <th>Email</th>
                            <th>Nationality</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $application)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $application->name ?? 'N/A' }}</td>
                            <td>{{ $application->father_name ?? 'N/A' }}</td>
                            <td>{{ $application->email ?? 'N/A' }}</td>
                            <td>{{ $application->nationality ?? 'N/A' }}</td>
                            <td>
                                <div class="card-actions float-center">
                                    <div class="d-inline-block dropdown show">
                                        <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                                            <i class="align-middle" data-feather="more-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-center">
                                            <!-- @can('application_edit')
                                            <a class="dropdown-item" href="{{ route('application.edit', $application->id) }}">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            @endcan -->

                                             @can('application_details')
                                            <a class="dropdown-item" href="{{ route('application.details', $application->id) }}">
                                                <i class="bi bi-eye-fill"></i>Details
                                            </a>
                                            @endcan

                                            @can('application_trash')
                                            <form action="{{ route('application.destroy', $application->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
