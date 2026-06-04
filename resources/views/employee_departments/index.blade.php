@extends('layout.master')
@section('title', 'Departments')
@section('header-title', 'Departments')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @can('employee_department_create')
                        <a href="{{ route('employee_departments.create') }}" class="btn btn-primary">Create Department</a>
                    @endcan
                    @can('employee_department_trash_view')
                        <a href="{{ route('employee_departments.trash') }}"
                            class="btn btn-danger d-flex align-items-center gap-2 ms-auto" title="Trashed Departments">
                            <i class="bi bi-trash-fill"></i>
                            <span>Trash</span>
                            <span class="badge bg-light text-dark">{{ $trashed->count() ?? 0 }}</span>
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Department Code</th>
                                {{-- <th>Company</th> --}}
                                <th>Department ID</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $department->name ?? 'N/A' }}</td>
                                    <td>{{ $department->code ?? 'N/A' }}</td>
                                    {{-- <td>{{ $department->company->name ?? 'N/A' }}</td> --}}
                                    <td>{{ $department->id ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $department->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($department->status) }}
                                        </span>
                                    </td>
                                    <td>
    <div class="card-actions float-center">
        <div class="d-inline-block dropdown show">
            <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                <i class="align-middle" data-feather="more-vertical"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-center">
                @can('employee_department_edit')
                    <a class="dropdown-item" href="{{ route('employee_departments.edit', $department->id) }}">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                @endcan
                @can('employee_department_trash')
                    <form action="{{ route('employee_departments.delete', $department->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure?')">
                        @csrf
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
