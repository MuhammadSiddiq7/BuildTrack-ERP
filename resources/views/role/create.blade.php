@extends('layout.master')
@section('title', 'Create Role')
@section('header-title', 'Create Role')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('role.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="name"><b>Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" placeholder="Enter Name"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-auto text-center">
                                <h4><b>Permissions</b></h4>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                    <label class="form-check-label" for="checkAll"><b>Select All</b></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @php
                                $groupedPermissions = $permissions->groupBy(function ($perm) {
                                    return $perm->group_name;
                                });
                            @endphp
                            @foreach ($groupedPermissions as $module => $perms)
                                <div class="col-md-12">
                                    <h5 class="text-primary text-uppercase">
                                        {{ ucfirst(str_replace('_', ' ', $module)) }}</h5>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="row">
                                        @foreach ($perms as $permission)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input type="checkbox" id="permission-{{ $permission->id }}"
                                                        class="form-check-input permission-checkbox" name="permissions[]"
                                                        value="{{ $permission->id }}">
                                                    <label class="form-check-label"
                                                        for="permission-{{ $permission->id }}">{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary"><b>Save Role</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = checkAll.checked);
            });
        });
    </script>

@endsection
