@extends('layout.master')
@section('title', 'Deleted Designations')
@section('header-title', 'Deleted Designations')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                {{-- <th>Company Name</th> --}}
                                <th>Department Name</th>
                                <th>Designation Name</th>
                                <th>Deleted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($designations as $designation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    {{-- <td>{{ $designation->company->name ?? 'N/A' }}</td> --}}
                                    <td>{{ $designation->employee_department->name ?? 'N/A' }}</td>
                                    <td>{{ $designation->name ?? 'N/A' }}</td>
                                    <td>{{ $designation->deleted_at ? $designation->deleted_at->format('d M, Y h:i A') : 'N/A' }}</td>
                                    <td>
                                        @can('designation_restore')
                                            <form action="{{ route('designations.restore', $designation->id) }}" method="GET"
                                                class="d-inline-block">
                                                <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Are you sure you want to restore this Designation?')">
                                                    Restore
                                                </button>
                                            </form>
                                        @endcan

                                        @can('designation_force_delete')
                                            <form action="{{ route('designations.forceDelete', $designation->id) }}" method="POST"
                                                class="d-inline-block"
                                                onsubmit="return confirm('Are you sure you want to permanently delete this Designation?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                {{-- <tr>
                                    <td colspan="5" class="text-center text-muted">No trashed Designations found.</td>
                                </tr> --}}
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
