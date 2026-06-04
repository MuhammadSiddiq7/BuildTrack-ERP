@extends('layout.master')
@section('title', 'Deleted Departments')
@section('header-title', 'Deleted Departments')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Code</th>
                                {{-- <th>Company</th> --}}
                                <th>Status</th>
                                <th>Deleted Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($departments as $department)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $department->name ?? 'N/A' }}</td>
                                    <td>{{ $department->code ?? 'N/A' }}</td>
                                    {{-- <td>{{ $department->company->name ?? 'N/A' }}</td> --}}
                                    <td>
                                        <span class="badge bg-{{ $department->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($department->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($department->deleted_at)
                                            {{ formatDateToCustom($department->deleted_at) }}
                                            {{ \Carbon\Carbon::parse($department->deleted_at)->format('h:i:s A') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @can('employee_department_restore')
                                            <form action="{{ route('employee_departments.restore', $department->id) }}"
                                                method="POST" class="d-inline-block">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Are you sure you want to restore this department?')">
                                                    Restore
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
