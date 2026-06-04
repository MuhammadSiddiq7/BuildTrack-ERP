@extends('layout.master')
@section('title', 'House Type')
@section('header-title', 'House Type')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-house-door-fill me-2"></i>House Type List
                    </h5>

                    <div class="d-flex align-items-center gap-2">
                        @can('houseType_create')
                            <a href="{{ route('houseType.create') }}" class="btn btn-sm btn-success">
                                <i class="bi bi-plus-circle me-1"></i> Create
                            </a>
                        @endcan

                        @can('houseType_trash_view')
                            <a href="{{ route('houseType.trash') }}"class="btn btn-sm btn-outline-danger position-relative"
                                title="Deleted Items">
                                <i class="bi bi-trash-fill me-1"></i> Trash
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $trashuser ?? 0 }}</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash"></i> S.NO</th>
                                <th><i class="bi bi-card-heading"></i> Name</th>
                                <th><i class="bi bi-info-circle"></i> Description</th>
                                <th><i class="bi bi-check-circle"></i> Status</th>
                                <th><i class="bi bi-gear-fill"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($houseTypes as $houseType)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $houseType->name ?? 'N/A' }}</td>
                                    <td>{{ $houseType->description ?? 'N/A' }}</td>
                                    <td>
                                        @if ($houseType->status == 'active')
                                            <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>
                                                Active</span>
                                        @else
                                            <span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i>
                                                Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @can('houseType_edit')
                                                <a href="{{ route('houseType.edit', $houseType->id) }}"
                                                    class="btn btn-sm btn-primary me-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endcan
                                            @can('houseType_trash')
                                                <form action="{{ route('houseType.delete', $houseType->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this house type?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
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
