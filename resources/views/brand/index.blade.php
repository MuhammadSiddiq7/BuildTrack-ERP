@extends('layout.master')
@section('title', 'Brand')
@section('header-title', 'Brand')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 ">
                        <i class="bi bi-truck me-2 text-primary"></i>Brand List
                    </h5>

                    <div class="d-flex align-items-center gap-2">
                        @can('brand_create')
                            <a href="{{ route('brand.create') }}" class="btn btn-sm btn-success">
                                <i class="bi bi-plus-circle me-1"></i> Create Brand
                            </a>
                        @endcan
                        @can('brand_trash_view')
                            <a href="{{ route('brand.trash') }}" class="btn btn-sm btn-outline-danger position-relative"
                                title="Deleted Users">
                                <i class="bi bi-trash-fill me-1"></i> Trash
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $trashuser ?? 0 }}
                                </span>
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatables-reponsive" class="table table-hover align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i> S:NO</th>
                                    <th><i class="bi bi-person-fill me-1"></i> Name</th>
                                    <th><i class="bi bi-telephone-fill me-1"></i> Contact</th>
                                    <th><i class="bi bi-card-text me-1"></i> Description</th>
                                    <th><i class="bi bi-toggle-on me-1"></i> Status</th>
                                    <th><i class="bi bi-gear-fill me-1"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brands as $brand)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $brand->name ?? 'N/A' }}</td>
                                        <td>{{ $brand->contact_number ?? 'N/A' }}</td>
                                        <td>{{ $brand->description ?? 'N/A' }}</td>
                                        <td>
                                            @if ($brand->status == 'active')
                                                <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>
                                                    Active</span>
                                            @else
                                                <span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i>
                                                    Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('brand_edit')
                                                <a href="{{ route('brand.edit', $brand->id) }}"
                                                    class="btn btn-sm btn-primary me-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endcan

                                            @can('brand_trash')
                                                <form action="{{ route('brand.delete', $brand->id) }}" method="POST"
                                                    style="display:inline-block;"
                                                    onsubmit="return confirm('Are you sure you want to delete this brand?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
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
    </div>
@endsection
