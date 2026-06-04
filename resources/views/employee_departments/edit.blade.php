@extends('layout.master')
@section('title', 'Edit Department')
@section('header-title', 'Edit Department')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('employee_departments.update', $department->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Name</b><span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name', $department->name) }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="code"><b>Code</b><span class="text-danger">*</span></label>
                                    <input type="text" name="code" id="code" class="form-control"
                                        value="{{ old('code', $department->code) }}">
                                    @error('code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="status"><b>Status</b><span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="active"
                                            {{ old('status', $department->status) == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $department->status) == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="hidden" name="department_check" value="0">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" name="department_check"
                                                value="1"
                                                {{ old('department_check', $department->department_check ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label"><b>Need Leave Approval From Operation</b></span>
                                        </label>
                                        <label class="form-label text-danger d-block mt-1">(If yes, please check this
                                            box)</label>
                                        @error('department_check')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="hidden" name="is_sira" value="0">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_sira" value="1"
                                                {{ old('is_sira', $department->is_sira ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label"><b>Requires SIRA Certification</b></span>
                                        </label>
                                        <label class="form-label text-danger d-block mt-1">(If yes, please check this
                                            box)</label>
                                        @error('is_sira')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="hidden" name="is_lifeguard_licenses" value="0">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_lifeguard_licenses"
                                                value="1"
                                                {{ old('is_lifeguard_licenses', $department->is_lifeguard_licenses ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label"><b>Requires Lifeguard License</b></span>
                                        </label>
                                        <label class="form-label text-danger d-block mt-1">(If yes, please check this
                                            box)</label>
                                        @error('is_lifeguard_licenses')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description"><b>Description</b></label>
                                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $department->description) }}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary"><b>Update Department</b></button>
                            <a href="{{ url('employee-departments') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
