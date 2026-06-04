@extends('layout.master')
@section('title', 'Create Department')
@section('header-title', 'Create Department')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('employee_departments.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label"><b>Name</b><span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ old('name') }}" placeholder="Enter Department Name">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label"><b>Code</b><span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="code" name="code" class="form-control"
                                        value="{{ old('code') }}" placeholder="Enter Department Code">
                                    @error('code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="company_id" class="form-label"><b>Company</b><span
                                            class="text-danger">*</span></label>
                                    <select id="company_id" name="company_id" class="form-control">
                                        <option value="" disabled selected>Select Company</option>
                                        @foreach ($companies as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('company_id') == $id ? 'selected' : '' }}>{{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label"><b>Status</b><span
                                            class="text-danger">*</span></label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                {{-- <!-- Department Check -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="hidden" name="department_check" value="0">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" name="department_check"
                                                value="1" {{ old('department_check') ? 'checked' : '' }}>
                                            <span class="form-check-label"><b>Need Leave Approval From Operation</b></span>
                                        </label>
                                        <label class="form-label text-danger d-block mt-1">(If yes, please check this
                                            box)</label>
                                        @error('department_check')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- SIRA -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="hidden" name="is_sira" value="0">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_sira" value="1"
                                                {{ old('is_sira') ? 'checked' : '' }}>
                                            <span class="form-check-label"><b>Requires SIRA Certification</b></span>
                                        </label>
                                        <label class="form-label text-danger d-block mt-1">(If yes, please check this
                                            box)</label>
                                        @error('is_sira')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Lifeguard -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="hidden" name="is_lifeguard_licenses" value="0">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_lifeguard_licenses"
                                                value="1" {{ old('is_lifeguard_licenses') ? 'checked' : '' }}>
                                            <span class="form-check-label"><b>Requires Lifeguard License</b></span>
                                        </label>
                                        <label class="form-label text-danger d-block mt-1">(If yes, please check this
                                            box)</label>
                                        @error('is_lifeguard_licenses')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label"><b>Description</b></label>
                                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter Description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary"><b>Save Department</b></button>
                            <a href="{{ route('employee_departments.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
