@extends('layout.master')
@section('title', 'Roles')
@section('header-title', 'Roles')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @can('role_create')
                        <a href="{{ route('role.create') }}" class="btn btn-primary">Create Role</a>
                    @endcan
                    @can('role_trash_view')
                        <a href="{{ route('role.trash') }}" class="btn btn-danger d-flex align-items-center gap-2"
                            title="Deleted Companies">
                            <i class="bi bi-trash-fill"></i>
                            <span>Trash</span>
                            <span class="badge bg-light text-dark">{{ $trashrole ?? 0 }}</span>
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S:NO</th>
                                <th>Name</th>
                                {{-- <th>Permissions</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    {{-- <td>
                                        @foreach ($role->permissions as $permission)
                                            <span class="badge bg-info text-dark">{{ $permission->name }}</span>
                                        @endforeach
                                    </td> --}}
                                    <td>
                                        @can('role_edit')
                                            <a href="{{ route('role.edit', $role->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                        @endcan
                                        @can('role_trash')
                                            <form action="{{ route('role.delete', $role->id) }}" method="POST"
                                                style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this Role?');">
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
