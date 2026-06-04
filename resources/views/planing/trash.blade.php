@extends('layout.master')
@section('title', 'Dashboard')
@section('header-title', 'Deleted Activity')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.NO</th>
                           <th>Activity</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activities as $activity)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $activity->activity_code ?? 'N/A' }}</td>
                                <td>{{ $activity->name ?? 'N/A' }}</td>
                                <td>{{ $activity->parent ?? 'N/A' }}</td>
                                <td>
                                    @if ($activity->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @can('user_restore')
                                        <a href="{{ route('activity.restore', $activity->id) }}"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Are you sure you want to restore this User?')">
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
