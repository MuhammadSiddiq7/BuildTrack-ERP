@extends('layout.master')
@section('title', 'Dashboard')
@section('header-title', 'Deleted brand')

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
                        @foreach ($brands as $brand)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $brand->description }}</td>
                                <td>{{ $brand->status ?? 'N/A' }}</td>
                                <td>
                                    @can('brand_restore')
                                        <a href="{{ route('brand.restore', $brand->id) }}"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Are you sure you want to restore this brand?')">
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
