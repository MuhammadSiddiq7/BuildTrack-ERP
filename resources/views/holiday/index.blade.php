@extends('layout.master')
@section('title', 'Holiday')
@section('header-title', 'Holiday')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                @can('holiday_create')
                <a href="{{ route('holiday.create') }}" class="btn btn-primary">Create Holiday</a>
                @endcan
            </div>

            <div class="card-body">
                <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S:NO</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($holidays as $holiday)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $holiday->title ?? 'N/A' }}</td>
                            <td>{{ $holiday->date ?? 'N/A' }}</td>
                            <td>
                                @if ($holiday->type == 'weekend')
                                <span class="badge bg-success">Weekend</span>
                                @else
                                <span class="badge bg-danger">Holiday</span>
                                @endif
                            </td>
                            <td>
                                @if ($holiday->status == 'active')
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @can('holiday_edit')
                                <a href="{{ route('holiday.edit', $holiday->id) }}"
                                    class="btn btn-sm btn-primary">Edit</a>
                                @endcan
                                @can('holiday_trash')
                                <form action="{{ route('holiday.delete', $holiday->id) }}" method="POST"
                                    style="display:inline-block;"
                                    onsubmit="return confirm('Are you sure you want to delete this holiday?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
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
