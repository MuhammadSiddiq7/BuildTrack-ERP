@extends('layout.master')
@section('title', 'Dashboard')
@section('header-title', 'Deleted house Type')

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
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($houseTypes as $houseType)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $houseType->name }}</td>
                                <td>{{ $houseType->description }}</td>
                                <td>{{ $houseType->status ?? 'N/A' }}</td>
                                <td>
                                    @can('houseType_restore')
                                        <a href="{{ route('houseType.restore', $houseType->id) }}"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Are you sure you want to restore this houseType?')">
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
