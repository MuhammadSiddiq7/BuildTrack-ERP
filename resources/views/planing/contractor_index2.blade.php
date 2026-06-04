@extends('layout.master')
@section('title', 'Contractor Progress List')
@section('header-title', 'Contractor Progress List')
@section('content')

<div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
        <div class="card-header">
            <h5 class="card-title mb-0">Contractor List</h5>
        </div>

        <table id="datatables-dashboard-projects" class="table table-striped my-0">
            <thead>
                <tr>
                    <th>S.NO</th>
                    <th>Contractor</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contractors as $contractor)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $contractor->name ?? 'N/A' }}</td>
                        <td>{{ $contractor->description ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('contractor.progress.show', $contractor->id) }}" 
                               class="btn btn-sm btn-primary">
                                View Progress
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
