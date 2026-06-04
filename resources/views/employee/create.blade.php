@extends('layout.master')
@section('title', 'Create Employee')
@section('header-title', 'Create Employee')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- UAE Fields -->
                    <div id="uae_form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee_id"><b>Employee Code</b></label>
                                    <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id') }}" class="form-control" placeholder="Enter Employee Code">
                                    @error('employee_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name"><b>Employee Name</b></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $applicant->name ?? '') }}" class="form-control" placeholder="Enter Employee Name">
                                    @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="father_name"><b>Fathers Name</b></label>
                                    <input type="text" id="father_name" name="father_name" value="{{ old('father_name', $applicant->father_name ?? '') }}" class="form-control" placeholder="Enter Father name">
                                    @error('father_name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                             <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email"><b>Employee Email</b></label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $applicant->email ?? '') }}" class="form-control" placeholder="Enter Email">
                                    @error('email')
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
                                    <label for="joining_date"><b>Joining Date</b></label>
                                    <input type="date" id="joining_date" name="joining_date" value="{{ old('joining_date') }}" class="form-control" onchange="calculateTenure()">
                                    @error('joining_date')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tenure"><b>Tenure at ADCC</b></label>
                                    <input type="text" id="tenure" name="tenure" value="{{ old('tenure') }}" class="form-control" readonly>
                                    @error('tenure')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cnic"><b>CNIC Number</b></label>
                                    <input type="text" id="cnic" name="cnic" value="{{ old('cnic') }}" class="form-control">
                                    @error('cnic')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mobile_number"><b>Mobile Number</b></label>
                                    <input type="number" id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}" class="form-control" placeholder="Enter Mobile Number">
                                    @error('mobile_number')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="account_number"><b>Bank Account No</b></label>
                                    <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}" class="form-control" placeholder="Enter Bank Account Number">
                                    @error('account_number')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dob"><b>Date Of Birth</b></label>
                                    <input type="date" id="dob" name="dob" value="{{ old('dob') }}" class="form-control" onchange="calculateAge()">
                                    @error('dob')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="age"><b>Age</b></label>
                                    <input type="number" step="0.01" id="age" name="age" value="{{ old('age') }}" class="form-control" readonly>
                                    @error('age')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="education"><b>Education</b></label>
                                    <input type="text" id="education" name="education" value="{{ old('education') }}" class="form-control" placeholder="Enter Education">
                                    @error('education')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deployment_area"><b>Deployment (Location/area)</b></label>
                                    <input type="text" id="deployment_area" name="deployment_area" value="{{ old('deployment_area') }}" class="form-control" placeholder="Enter Deployment area">
                                    @error('deployment_area')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="project_id"><b>Project</b></label>
                                    <select name="project_id" id="project_id" class="form-control select2">
                                        <option value="" selected disabled>Select Project</option>
                                        @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id', $applicant->project_id ?? '') == $project->id ? 'selected' : '' }}>
                                            {{ $project->project_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('project')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
                                    <label><b>Gross Salary</b></label>
                                    <input type="text" id="gross_salary" name="gross_salary" class="form-control" placeholder="Enter Gross Salary">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Basic Salary</b></label>
                                    <input type="text" id="basic_salary" name="basic_salary" class="form-control" placeholder="Enter Basic Salary" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>House Rent</b></label>
                                    <input type="text" id="house_rent" name="house_rent" class="form-control" placeholder="Enter House Rent" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Medical</b></label>
                                    <input type="text" id="medical" name="medical" class="form-control" placeholder="Enter Medical" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Utilities</b></label>
                                    <input type="text" id="utilities" name="utilities" class="form-control" placeholder="Enter Utilities" readonly>
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Fuel Allowance (Monthly Salary) (Ltrs)</b></label>
                                    <input type="number" step="0.01" name="fuel_allowance_monthly" class="form-control" placeholder="Enter Fuel Allowance (Monthly)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Company Vehicle</b></label>
                                    <select name="company_vehicle" class="form-control">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Fuel Allowance - Vehicle (Ltrs)</b></label>
                                    <input type="number" step="0.01" name="fuel_allowance_vehicle" class="form-control" placeholder="Enter Fuel Allowance (Vehicle)">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Calling Card</b></label>
                                    <select name="calling_card" id="calling_card" class="form-control">
                                        <option value="0" selected>No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6" id="card_account_div" style="display: none;">
                                <div class="mb-3">
                                    <label><b>Calling Card Amount</b></label>
                                    <input type="text" name="card_account" class="form-control" placeholder="Enter Calling Card Amount">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Scale/Level</b></label>
                                    <input type="text" name="scale_level" class="form-control" placeholder="Enter Scale/Level">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee_status"><b>Employee Status</b></label>
                                    <select name="employee_status" id="employee_status" class="form-control select2">
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="active" {{ old('employee_status') == 'active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="inactive" {{ old('employee_status') == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('employee_status')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label><b>Employee Picture</b></label>
                                    <input type="file" name="employee_picture" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="comment"><b>Comment</b></label>
                                    <textarea id="comment" name="comment" class="form-control" placeholder="Enter any comments">{{ old('comment') }}</textarea>
                                    @error('comment')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><b>Save Employee</b></button>
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
