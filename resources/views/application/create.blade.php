@extends('layout.master')
@section('title', 'Create Application')
@section('header-title', 'Create Application')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('application.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="row" id="use-form">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name"><b>Name</b><span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ old('name') }}" placeholder="Enter Name"
                                        oninput="updateCertification()">
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="father_name"><b>Father's Name</b></label>
                                    <input type="text" id="father_name" name="father_name" class="form-control"
                                        value="{{ old('father_name') }}" placeholder="Enter Father Name"
                                        oninput="updateCertification()">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email"><b>Email</b></label>
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email') }}" class="form-control"
                                        placeholder="Enter Email">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cnic"><b>CNIC</b></label>
                                    <input type="text" id="cnic" name="cnic"
                                        value="{{ old('cnic') }}" class="form-control"
                                        placeholder="Enter cnic">
                                </div>
                            </div>




                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dob"><b>Date of Birth</b></label>
                                    <input type="date" id="dob" name="dob"
                                        value="{{ old('dob') }}" class="form-control">
                                    @error('dob')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="age"><b>Age</b></label>
                                    <input type="number" step="0.01" id="age" name="age"
                                        value="{{ old('age') }}" class="form-control" readonly>
                                    @error('age')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gender"><b>Gender</b></label>
                                    <select id="gender" name="gender" class="form-control select2">
                                        <option disabled {{ old('gender') ? '' : 'selected' }}>Select Gender
                                        </option>
                                        <option value="male"
                                            {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female"
                                            {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="other"
                                            {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="marital_status"><b>Marital Status</b></label>
                                    <select id="marital_status" name="marital_status"
                                        class="form-control select2">
                                        <option disabled {{ old('marital_status') ? '' : 'selected' }}>
                                            Select Marital Status</option>
                                        <option value="single"
                                            {{ old('marital_status') == 'single' ? 'selected' : '' }}>
                                            Single</option>
                                        <option value="married"
                                            {{ old('marital_status') == 'married' ? 'selected' : '' }}>
                                            Married</option>
                                        <option value="divorced"
                                            {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>
                                            Divorced</option>
                                        <option value="widowed"
                                            {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>
                                            Widowed</option>
                                        <option value="engaged"
                                            {{ old('marital_status') == 'engaged' ? 'selected' : '' }}>
                                            Engaged</option>
                                        <option value="other"
                                            {{ old('marital_status') == 'other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="previous_address"><b>Previous Address</b></label>
                                    <input type="text" id="previous_address" name="previous_address" class="form-control" value="{{ old('previous_address') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="nationality"><b>Country</b></label>
                                    <input type="text" id="nationality" name="nationality"
                                        value="{{ old('nationality') }}" class="form-control"
                                        placeholder="Enter Country">
                                    @error('nationality')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city"><b>City</b></label>
                                    <input type="text" id="city" name="city"
                                        value="{{ old('city') }}" class="form-control"
                                        placeholder="Enter City">
                                </div>
                            </div>


                            <hr>
                            <div class="row">
                                <h4 style="color: #153d77;">
                                    Job Related Info:
                                </h4>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employee_department_id"><b>Employee Department</b></label>
                                        <select name="employee_department_id" id="employee_department_id" class="form-control select2">
                                            <option value="" selected disabled>Select Department</option>
                                            @foreach ($employee_departments as $department)
                                            <option value="{{ $department->id }}" {{ old('employee_department_id', $applicant->employee_department_id ?? '') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('employee_department_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="designation_id"><b>Designation</b></label>
                                        <select name="designation_id" id="designation_id" class="form-control select2">
                                            <option value="" selected disabled>Select Designation</option>
                                            @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}" {{ old('designation_id', $applicant->designation_id ?? '') == $designation->id ? 'selected' : '' }}>
                                                {{ $designation->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('designation')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="currently_employed_company"><b>Currently Employed Company Name</b></label>
                                        <input type="text" id="currently_employed_company" name="currently_employed_company"
                                            class="form-control">
                                    </div>
                                    @error('currently_employed_company')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="currently_employed_designation"><b>Designation in Current Company</b></label>
                                        <input type="text" id="currently_employed_designation" name="currently_employed_designation"
                                            class="form-control">
                                    </div>
                                    @error('currently_employed_designation')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="currently_salary"><b>Current Salary</b></label>
                                        <input type="text" id="currently_salary" name="currently_salary"
                                            class="form-control">
                                    </div>
                                    @error('currently_salary')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="allowances"><b>Allowances</b></label>
                                        <input type="text" id="allowances" name="allowances"
                                            class="form-control">
                                    </div>
                                    @error('allowances')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="resume"><b>Upload CV (PDF/DOC allowed)</b></label>
                                        <input type="file" id="resume" name="resume"
                                            class="form-control">
                                    </div>
                                    @error('resume')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>



                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><b>Create Application</b></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
