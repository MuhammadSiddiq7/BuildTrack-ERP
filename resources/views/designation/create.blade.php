@extends('layout.master')
@section('title', 'Create Designation')
@section('header-title', 'Create Designation')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('designations.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="employee_department_id"><b>Employee Department</b><span
                                            class="text-danger">*</span></label>
                                    <select name="employee_department_id" id="employee_department_id" class="form-control">
                                        <option value="" selected disabled>Select Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('employee_department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_department_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Designation Name</b><span class="text-danger"></span></label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Enter Designation Name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary"><b>Create Designation</b></button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
