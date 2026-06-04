@extends('layout.master')
@section('title', 'Edit Application')
@section('header-title', 'Edit Application')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('application.update', $application->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="row" id="use-form">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name"><b>Name</b><span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control"
                                            value="{{ old('name', $application->name ?? '') }}"
                                             placeholder="Enter Name" oninput="updateCertification()">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="surname"><b>Surname</b></label>
                                        <input type="text" id="surname" name="surname" class="form-control"
                                            value="{{ old('surname', $application->surname ?? '') }}" placeholder="Enter Surname"
                                            oninput="updateCertification()">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email"><b>Email</b></label>
                                        <input type="email" id="email" name="email"
                                           value="{{ old('email', $application->email ?? '') }}" class="form-control"
                                            placeholder="Enter Email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mobile"><b>Mobile</b></label>
                                        <input type="number" id="mobile" name="mobile"
                                            value="{{ old('mobile', $application->mobile ?? '') }}" class="form-control"
                                            placeholder="Enter Mobile">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender"><b>Gender</b></label>
                                        <select id="gender" name="gender" class="form-control">
                                            <option value="" disabled
                                                {{ old('gender', $application->gender ?? '') == '' ? 'selected' : '' }}>
                                                Select Gender</option>
                                            <option value="male"
                                                {{ old('gender', $application->gender ?? '') == 'male' ? 'selected' : '' }}>
                                                Male</option>
                                            <option value="female"
                                                {{ old('gender', $application->gender ?? '') == 'female' ? 'selected' : '' }}>
                                                Female</option>
                                            <option value="other"
                                                {{ old('gender', $application->gender ?? '') == 'other' ? 'selected' : '' }}>
                                                Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="dob"><b>Date of Birth</b></label>
                                        <input type="date" id="dob" name="dob"
                                            value="{{ old('dob', $application->dob ?? '') }}" class="form-control">
                                        @error('dob')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nationality"><b>Country</b></label>
                                        <input type="text" id="nationality" name="nationality"
                                            value="{{ old('nationality', $application->nationality ?? '') }}" class="form-control"
                                            placeholder="Enter Country">
                                        @error('nationality')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="city"><b>City</b></label>
                                        <input type="text" id="city" name="city"
                                            value="{{ old('city', $application->city ?? '') }}" class="form-control"
                                            placeholder="Enter City">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="marital_status"><b>Marital Status</b></label>
                                        <select id="marital_status" name="marital_status"
                                            class="form-control">
                                            <option value="" disabled
                                                {{ old('marital_status', $applicant->marital_status ?? '') == '' ? 'selected' : '' }}>
                                                Select Marital Status</option>
                                            @foreach (['single', 'married', 'divorced', 'widowed', 'engaged', 'other'] as $status)
                                                <option value="{{ $status }}"
                                                    {{ old('marital_status', $applicant->marital_status ?? '') == $status ? 'selected' : '' }}>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="resume"><b>Update Resume</b></label>
                                        <input type="file" id="resume" name="resume"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="job_applied_for"><b>Job Applied For</b></label>
                                        <textarea id="job_applied_for" name="job_applied_for" rows="2" class="form-control"
                                            placeholder="Enter Job Applied For Reason">{{ old('job_applied_for', $application->job_applied_for ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="current_address"><b>Current Address</b></label>
                                        <textarea id="current_address" name="current_address" class="form-control" rows="2">{{ old('current_address', $application->current_address ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="previous_address"><b>Previous Address</b></label>
                                        <textarea id="previous_address" name="previous_address" class="form-control" rows="2">{{ old('previous_address', $application->previous_address ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary"><b>Update Application</b></button>
                            <a href="{{ route('application.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
