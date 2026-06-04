@extends('layout.master')
@section('title', 'Planning')
@section('header-title', 'Planning')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        @can('planning_create')
                        <a href="{{ route('plan.create') }}" class="btn btn-primary">Create Plan</a>
                        @endcan
                        @can('planning_assign_activity_combine')
                        <a href="{{ route('plan.assignActivity.combine') }}" class="btn btn-primary ms-2">Assign Bulk Activity</a>
                        @endcan
                    </div>
                    {{-- @can('planning_trash_view')
                        <a href="{{ route('plan.trash') }}" class="btn btn-danger d-flex align-items-center gap-2 ms-auto"
                            title="Deleted Users">
                            <i class="bi bi-trash-fill"></i>
                            <span>Trash</span>
                            <span class="badge bg-light text-dark">{{ $trashuser ?? 0 }}</span>
                        </a>
                    @endcan --}}
                </div>

                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S:NO</th>
                                <th>Project </th>
                                <th>Type</th>
                                <th>House</th>
                                <th>Contractor</th>
                                {{-- <th>User</th> --}}
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($planing as $plan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $plan->project->project_name ?? 'N/A' }}</td>
                                    <td>{{ $plan->type->house_type_id ?? 'N/A' }}</td>
                                    <td>{{ $plan->house->house_number ?? 'N/A' }}</td>
                                    <td>{{ $plan->contractor->name ?? 'N/A' }}</td>
                                    {{-- <td>{{ $plan->user->name ?? 'N/A' }}</td> --}}
                                    <td>
                                        @if ($plan->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @can('planning_assign_activity_view')
                                        <a href="{{ route('plan.assignActivity', $plan->id) }}" class="btn btn-sm btn-success btn-icon"
                                            title="Assign Activity"><i class="bi bi-box-arrow-up-right"></i> Assign
                                        </a>
                                        @endcan
                                        {{-- @can('user_trash')
                                            <form action="{{ route('plan.delete', $plan->id) }}" method="POST"
                                                style="display:inline-block;"
                                                onsubmit="return confirm('Are you sure you want to delete this User?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endcan --}}
                                        @can('planning_show')
                                        <a href="{{ route('plan.show', $plan->id) }}" class="btn btn-sm btn-info"
                                            title="View Plan"><i class="bi bi-eye"></i>
                                        </a>
                                        @endcan
                                        @can('planning_graph_view')
                                        <a href="{{ route('plan.check', $plan->id) }}?house_id={{ $plan->house->id ?? '' }}"
                                            class="btn btn-sm btn-warning " title="View Graph"><i class="bi bi-bar-chart"></i> Graph
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
