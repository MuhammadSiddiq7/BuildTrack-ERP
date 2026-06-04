@extends('layout.master')
@section('title', 'Deleted Employees')
@section('header-title', 'Deleted Employees')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.NO</th>
                                <th>Name</th>
                                {{-- <th>Company</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $employee->name ?? 'N/A' }}</td>
                                    {{-- <td>{{ $employee->company->name ?? 'N/A' }}</td> --}}
                                    <td>
                                        @can('employee_restore')
                                            <a href="{{ route('employee.restore', $employee->id) }}"
                                                onclick="return confirm('Are you sure you want to restore this employee?')"
                                                class="text-success">
                                                <i data-feather="rotate-ccw" class="align-middle me-1"></i> Restore
                                            </a>
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
@endsection
