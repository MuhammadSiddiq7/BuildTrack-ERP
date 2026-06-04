@extends('layout.master')
@section('title', 'Users')
@section('header-title', 'Users')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                @can('user_create')
                <a href="{{ route('user.create') }}" class="btn btn-primary">Create User</a>
                @endcan

                @can('user_trash_view')
                <a href="{{ route('user.trash') }}" class="btn btn-danger d-flex align-items-center gap-2 ms-auto"
                    title="Deleted Users">
                    <i class="bi bi-trash-fill"></i>
                    <span>Trash</span>
                    <span class="badge bg-light text-dark">{{ $trashuser ?? 0 }}</span>
                </a>
                @endcan
            </div>

            <div class="card-body">
                <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S:NO</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name ?? 'N/A' }}</td>
                            <td>{{ $user->email ?? 'N/A' }}</td>
                            <td>{{ $user->department ?? 'N/A' }}</td>
                            <td>
                                @if ($user->roles->isNotEmpty())
                                @foreach ($user->roles as $role)
                                <span class="badge bg-primary me-1">{{ ucfirst($role->name) }}</span>
                                @endforeach
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->status == 'active')
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @can('user_edit')
                                <a href="{{ route('user.edit', $user->id) }}"
                                    class="btn btn-sm btn-primary">Edit</a>
                                @endcan
                                @can('user_trash')
                                @if($user->id > 2)
                                <form action="{{ route('user.delete', $user->id) }}" method="POST"
                                    style="display:inline-block;"
                                    onsubmit="return confirm('Are you sure you want to delete this User?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                @endif
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
