@extends('layout.master')
@section('title', 'Designations')
@section('header-title', 'Designations')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @can('designation_create')
                        <a href="{{ route('designations.create') }}" class="btn btn-primary">Create Designation</a>
                    @endcan
                    @can('designation_trash_view')
                        <a href="{{ route('designations.trash') }}" class="btn btn-danger d-flex align-items-center gap-2 ms-auto"
                            title="Trashed designations">
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
                                {{-- <th>Company Name</th> --}}
                                <th>Department Name</th>
                                <th>Designation Name</th>
                                {{-- <th>Designation ID</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($designations as $designation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    {{-- <td>{{ $designation->company->name ?? 'N/A' }}</td> --}}
                                    <td>{{ $designation->employee_department->name ?? 'N/A' }}</td>
                                    <td>{{ $designation->name ?? 'N/A' }}</td>
                                    {{-- <td>{{ $designation->id ?? 'N/A' }}</td> --}}
                                    <td>
    <div class="card-actions float-center">
        <div class="d-inline-block dropdown show">
            <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                <i class="align-middle" data-feather="more-vertical"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-center">
                @can('designation_edit')
                    <a class="dropdown-item" href="{{ route('designations.edit', $designation->id) }}">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                @endcan
                @can('designation_trash')
                    <form action="{{ route('designations.destroy', $designation->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure?')">
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
