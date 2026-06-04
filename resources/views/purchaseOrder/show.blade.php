@extends('layout.master')
@section('title', 'Show Project')
@section('header-title', 'Find Project')
@section('content')

<style>
    .readonly {
        background-color: #e9ecef !important;
        cursor: not-allowed;
    }

</style>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="page-title text-primary fw-bold">
                            <a href="{{ route('find.project') }}">Search Project</a>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('find.project') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="searchName" class="form-label">Search By Project Name</label>
                                        <input type="text" class="form-control" id="searchName" name="search_name" value="{{ request('search_name') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="searchNumber" class="form-label">Search By Project Number</label>
                                        <input type="number" class="form-control" id="searchNumber" name="search_number" value="{{ request('search_number') }}">
                                    </div>
                                    <div class="col-12 d-flex justify-content-center mt-4">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
                            <!-- Search Result -->
                            @if ($projects->count() > 0)
                            <div class="row">
                                <div class="col-md-12 mt-5">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Project Number</th>
                                                    <th>Site Name</th>
                                                    <th>Address</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($projects as $project)
                                                <tr>
                                                    <td>{{ $project->project_number ?? 'N/A'}}</td>
                                                    <td>{{ $project->site_name ?? 'N/A' }}</td>
                                                    <td>{{ $project->address }}</td>
                                                    <td>
                                                        <a href="{{ route('project.index', ['project' => $project->id]) }}" class="btn btn-success btn-sm">View</a>
                                                        @can('project_edit')
                                                        <a href="{{ route('project.edit', ['id' => $project->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @elseif(request()->has('search_name'))
                            <div class="alert alert-dark mt-4 p-2">
                                No project found with the provided details. <br>
                                @can('project_create')
                                <a href="{{ route('project.create') }}" class="btn btn-secondary p-0 m-0 align-baseline">Create new Project?</a>
                                @endcan
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
