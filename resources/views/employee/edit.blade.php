@extends('layout.master')
@section('title', 'Edit Employee')
@section('header-title', 'Edit Employee')
@section('content')



<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('employee.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div id="uae_form">
                        <div class="row">
                            <!-- Employee Code -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee_id"><b>Employee Code</b></label>
                                    <input type="text" id="employee_id" name="employee_id"
                                        value="{{ old('employee_id', $employee->employee_id ?? '') }}"
                                        class="form-control" placeholder="Enter Employee Code">
                                    @error('employee_id') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Employee Name -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name"><b>Employee Name</b></label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $employee->name ?? '') }}"
                                        class="form-control" placeholder="Enter Employee Name">
                                    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Father Name -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="father_name"><b>Father's Name</b></label>
                                    <input type="text" id="father_name" name="father_name"
                                        value="{{ old('father_name', $employee->father_name ?? '') }}"
                                        class="form-control" placeholder="Enter Father's Name">
                                    @error('father_name') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email"><b>Employee Email</b></label>
                                    <input type="text" id="email" name="email"
                                        value="{{ old('email', $employee->email ?? '') }}"
                                        class="form-control" placeholder="Enter email">
                                    @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>


                            <!-- Designation -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="designation_id"><b>Designation</b></label>
                                    <select name="designation_id" id="designation_id" class="form-control select2">
                                        <option value="" disabled>Select Designation</option>
                                        @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}"
                                            {{ old('designation_id', $employee->designation_id ?? '') == $designation->id ? 'selected' : '' }}>
                                            {{ $designation->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('designation_id') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Joining Date -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="joining_date"><b>Joining Date</b></label>
                                    <input type="date" id="joining_date" name="joining_date"
                                        value="{{ old('joining_date', $employee->joining_date ?? '') }}"
                                        class="form-control" onchange="calculateTenure()">
                                    @error('joining_date') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Tenure -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tenure"><b>Tenure at ADCC</b></label>
                                    <input type="text" id="tenure" name="tenure"
                                        value="{{ old('tenure', $employee->tenure ?? '') }}"
                                        class="form-control" readonly>
                                    @error('tenure') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- CNIC -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cnic"><b>CNIC Number</b></label>
                                    <input type="text" id="cnic" name="cnic"
                                        value="{{ old('cnic', $employee->cnic ?? '') }}"
                                        class="form-control" placeholder="Enter CNIC">
                                    @error('cnic') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Mobile Number -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mobile_number"><b>Mobile Number</b></label>
                                    <input type="text" id="mobile_number" name="mobile_number"
                                        value="{{ old('mobile_number', $employee->mobile_number ?? '') }}"
                                        class="form-control" placeholder="Enter Mobile Number">
                                    @error('mobile_number') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Bank Account -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="account_number"><b>Bank Account No</b></label>
                                    <input type="text" id="account_number" name="account_number"
                                        value="{{ old('account_number', $employee->account_number ?? '') }}"
                                        class="form-control" placeholder="Enter Bank Account Number">
                                    @error('account_number') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Date of Birth -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dob"><b>Date of Birth</b></label>
                                    <input type="date" id="dob" name="dob"
                                        value="{{ old('dob', $employee->dob ?? '') }}"
                                        class="form-control" onchange="calculateAge()">
                                    @error('dob') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Age -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="age"><b>Age</b></label>
                                    <input type="number" step="0.01" id="age" name="age"
                                        value="{{ old('age', $employee->age ?? '') }}"
                                        class="form-control" readonly>
                                    @error('age') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Education -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="education"><b>Education</b></label>
                                    <input type="text" id="education" name="education"
                                        value="{{ old('education', $employee->education ?? '') }}"
                                        class="form-control" placeholder="Enter Education">
                                    @error('education') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Deployment Area -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deployment_area"><b>Deployment Area</b></label>
                                    <input type="text" id="deployment_area" name="deployment_area"
                                        value="{{ old('deployment_area', $employee->deployment_area ?? '') }}"
                                        class="form-control" placeholder="Enter Deployment Area">
                                    @error('deployment_area') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Project -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="project_id"><b>Project</b></label>
                                    <select name="project_id" id="project_id" class="form-control select2">
                                        <option value="" disabled>Select Project</option>
                                        @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ old('project_id', $employee->project_id ?? '') == $project->id ? 'selected' : '' }}>
                                            {{ $project->project_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('project_id') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Employee Department -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee_department_id"><b>Employee Department</b></label>
                                    <select name="employee_department_id" id="employee_department_id" class="form-control select2">
                                        <option value="" disabled>Select Department</option>
                                        @foreach ($employee_departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('employee_department_id', $employee->employee_department_id ?? '') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('employee_department_id') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Gross Salary -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Gross Salary</b></label>
                                    <input type="text" id="gross_salary" name="gross_salary"
                                        value="{{ old('gross_salary', $employee->salary->gross_salary ?? '') }}"
                                        class="form-control" placeholder="Enter Gross Salary">
                                </div>
                            </div>

                            <!-- Basic Salary -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Basic Salary</b></label>
                                    <input type="text" id="basic_salary" name="basic_salary"
                                        value="{{ old('basic_salary', $employee->salary->basic_salary ?? '') }}"
                                        class="form-control" readonly>
                                </div>
                            </div>

                            <!-- House Rent -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>House Rent</b></label>
                                    <input type="text" id="house_rent" name="house_rent"
                                        value="{{ old('house_rent', $employee->salary->house_rent ?? '') }}"
                                        class="form-control" readonly>
                                </div>
                            </div>

                            <!-- Medical -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Medical</b></label>
                                    <input type="text" id="medical" name="medical"
                                        value="{{ old('medical', $employee->salary->medical ?? '') }}"
                                        class="form-control" readonly>
                                </div>
                            </div>

                            <!-- Utilities -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Utilities</b></label>
                                    <input type="text" id="utilities" name="utilities"
                                        value="{{ old('utilities', $employee->salary->utilities ?? '') }}"
                                        class="form-control" readonly>
                                </div>
                            </div>

                            <!-- Fuel Allowance (Monthly) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Fuel Allowance (Monthly)</b></label>
                                    <input type="number" step="0.01" name="fuel_allowance_monthly"
                                        value="{{ old('fuel_allowance_monthly', $employee->salary->fuel_allowance_monthly ?? '') }}"
                                        class="form-control" placeholder="Enter Fuel Allowance (Monthly)">
                                </div>
                            </div>

                            <!-- Company Vehicle -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Company Vehicle</b></label>
                                    <select name="company_vehicle" class="form-control">
                                        <option value="1" {{ old('company_vehicle', $employee->allowance->company_vehicle ?? '') == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('company_vehicle', $employee->allowance->company_vehicle ?? '') == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Fuel Allowance (Vehicle) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Fuel Allowance (Vehicle)</b></label>
                                    <input type="number" step="0.01" name="fuel_allowance_vehicle"
                                        value="{{ old('fuel_allowance_vehicle', $employee->allowance->fuel_allowance_vehicle ?? '') }}"
                                        class="form-control" placeholder="Enter Fuel Allowance (Vehicle)">
                                </div>
                            </div>

                            <!-- Calling Card -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Calling Card</b></label>
                                    <select name="calling_card" id="calling_card" class="form-control">
                                        <option value="0" {{ old('calling_card', $employee->allowance->calling_card ?? '') == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('calling_card', $employee->allowance->calling_card ?? '') == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Card Account -->
                            <div class="col-md-6" id="card_account_div" style="{{ old('calling_card', $employee->allowance->calling_card ?? '') == 1 ? '' : 'display:none;' }}">
                                <div class="mb-3">
                                    <label><b>Card Amount</b></label>
                                    <input type="text" name="card_account"
                                        value="{{ old('card_account', $employee->allowance->card_account ?? '') }}"
                                        class="form-control" placeholder="Enter Card Account">
                                </div>
                            </div>

                            <!-- Scale/Level -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Scale/Level</b></label>
                                    <input type="text" name="scale_level"
                                        value="{{ old('scale_level', $employee->allowance->scale_level ?? '') }}"
                                        class="form-control" placeholder="Enter Scale/Level">
                                </div>
                            </div>

                            <!-- Employee Status -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee_status"><b>Employee Status</b></label>
                                    <select name="employee_status" id="employee_status" class="form-control select2">
                                        <option value="" disabled>Select Status</option>
                                        <option value="active" {{ old('employee_status', $employee->employee_status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('employee_status', $employee->employee_status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('employee_status') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Employee Picture -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee_picture"><b>Employee Picture</b></label>
                                    <input type="file" id="employee_picture" name="employee_picture" class="form-control">
                                    @if($employee->employee_picture)
                                    <small class="text-muted">Current: <a href="{{ asset($employee->employee_picture) }}" target="_blank">View</a></small>
                                    @endif
                                </div>
                            </div>

                            <!-- Comment -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="comment"><b>Comment</b></label>
                                    <textarea id="comment" name="comment" class="form-control" placeholder="Enter any comments">{{ old('comment', $employee->comment ?? '') }}</textarea>
                                    @error('comment') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success"><b>Update Employee</b></button>
                        <a href="{{ route('employee.index') }}" class="btn btn-secondary"><b>Back</b></a>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>







<script>
    function calculateTenure() {
        var joiningDate = document.getElementById("joining_date").value;
        if (joiningDate) {
            var joinDate = new Date(joiningDate);
            var today = new Date();
            var years = today.getFullYear() - joinDate.getFullYear();
            var months = today.getMonth() - joinDate.getMonth();
            var days = today.getDate() - joinDate.getDate();

            if (months < 0 || (months === 0 && days < 0)) {
                years--;
                months = 12 + months;
            }
            if (days < 0) {
                days = new Date(today.getFullYear(), today.getMonth(), 0).getDate() + days;
            }

            // Showing the tenure as "X years, Y months, Z days"
            document.getElementById("tenure").value = years + " years, " + months + " months, " + days + " days";
        }
    }
</script>
<script>
    function calculateAge() {
        var dob = document.getElementById("dob").value;
        if (dob) {
            var birthDate = new Date(dob);
            var today = new Date();
            var age = today.getFullYear() - birthDate.getFullYear();
            var month = today.getMonth() - birthDate.getMonth();
            if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            document.getElementById("age").value = age;
        }
    }
</script>




<script>
    document.addEventListener("DOMContentLoaded", function() {
        let callingCard = document.getElementById("calling_card");
        let cardAccountDiv = document.getElementById("card_account_div");

        function toggleCardAccount() {
            if (callingCard.value === "1") {
                cardAccountDiv.style.display = "block";
            } else {
                cardAccountDiv.style.display = "none";
            }
        }

        // Run on page load
        toggleCardAccount();

        // Run on change
        callingCard.addEventListener("change", toggleCardAccount);
    });
</script>



<script>
const grossInput = document.getElementById("gross_salary");

function updateSalaryFields(gross) {
    let basic = gross * 0.625;
    let medical = basic * 0.10;
    let houseRent = basic * 0.40;
    let utilities = basic * 0.10;

    document.getElementById("basic_salary").value = Math.round(basic).toLocaleString();
    document.getElementById("medical").value = Math.round(medical).toLocaleString();
    document.getElementById("house_rent").value = Math.round(houseRent).toLocaleString();
    document.getElementById("utilities").value = Math.round(utilities).toLocaleString();
}

// Input event: calculate & format with commas while typing
grossInput.addEventListener("input", function() {
    let cursorPos = this.selectionStart; // save cursor
    let rawValue = this.value.replace(/,/g, '');
    let gross = parseFloat(rawValue) || 0;

    updateSalaryFields(gross);

    // Format gross with commas
    this.value = gross.toLocaleString();

    // Adjust cursor position
    let diff = this.value.length - rawValue.length;
    this.setSelectionRange(cursorPos + diff, cursorPos + diff);
});
</script>

@endsection
