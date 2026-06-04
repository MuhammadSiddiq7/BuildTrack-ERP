@extends('layout.master')
@section('title', 'Dashboard')
@section('header-title', 'Deleted contractor')

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
                            <th>Description</th>
                            <th>No of Houses</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contractors as $contractor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $contractor->name }}</td>
                                <td>{{ $contractor->description }}</td>
                                <td>{{ $contractor->no_of_houses }}</td>
                                <td>{{ $contractor->status ?? 'N/A' }}</td>
                                <td>
                                    @can('contractor_restore')
                                        <a href="{{ route('contractor.restore', $contractor->id) }}"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Are you sure you want to restore this contractor?')">
                                        Restore
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
