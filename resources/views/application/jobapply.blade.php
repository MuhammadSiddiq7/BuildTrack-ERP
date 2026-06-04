<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A name you can trust in an uncertain world">
    <meta name="author" content="Bootlab">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/adcc.png') }}" />
    <title>ADCC - Application Leave</title>
    <link href="{{ asset('assets/css/modern.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/classic.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dark.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/light.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            opacity: 0;
        }
    </style>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-120946860-7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-120946860-7');
    </script>
</head>

<body>
    <div class="splash active">
        <div class="splash-icon"></div>
    </div>
    <div class="wrapper">
        <div class="main">
            <main class="content">
                <div class="container-fluid">
                    <div class="header text-center" style="margin-bottom: 10px !important">
                        <img src="{{ asset('assets/img/logo/adcc.png') }}" alt="ADCC" style="width: 150px">
                        <h1 class="header-title pt-3">
                            Job Application Form
                        </h1>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">

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
                                                            <input type="number" step="0.01"  id="age" name="age"
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
                    </div>
                </div>
            </main>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-8 text-start">
                            <ul class="list-inline">
                                <li class="list-inline-item">Developed by
                                    <a class="text-muted" style="color: #2fa09c !important" target="_blank"
                                        href="https://synergyintegratedsolutions.pk/">Synergy Integrated Solutions</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-4 text-end">
                            <p class="mb-0">
                                &copy; 2025 - <a class='text-muted' target="_blank"
                                    href='https://guardforce.ae/'>Guardforce</a>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <svg width="0" height="0" style="position:absolute">
        <defs>
            <symbol viewBox="0 0 512 512" id="ion-ios-pulse-strong">
                <path
                    d="M448 273.001c-21.27 0-39.296 13.999-45.596 32.999h-38.857l-28.361-85.417a15.999 15.999 0 0 0-15.183-10.956c-.112 0-.224 0-.335.004a15.997 15.997 0 0 0-15.049 11.588l-44.484 155.262-52.353-314.108C206.535 54.893 200.333 48 192 48s-13.693 5.776-15.525 13.135L115.496 306H16v31.999h112c7.348 0 13.75-5.003 15.525-12.134l45.368-182.177 51.324 307.94c1.229 7.377 7.397 11.92 14.864 12.344.308.018.614.028.919.028 7.097 0 13.406-3.701 15.381-10.594l49.744-173.617 15.689 47.252A16.001 16.001 0 0 0 352 337.999h51.108C409.973 355.999 427.477 369 448 369c26.511 0 48-22.492 48-49 0-26.509-21.489-46.999-48-46.999z">
                </path>
            </symbol>
        </defs>
    </svg>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        setTimeout(function() {
            let alertBox = document.getElementById('alertMessage');
            if (alertBox) {
                alertBox.style.transition = 'opacity 0.5s ease';
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 4000);
    </script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const dobInput = document.getElementById("dob");
    const ageInput = document.getElementById("age");

    dobInput.addEventListener("change", function () {
        if (!this.value) {
            ageInput.value = "";
            return;
        }

        let dob = new Date(this.value);
        let today = new Date();

        let age = today.getFullYear() - dob.getFullYear();
        let monthDiff = today.getMonth() - dob.getMonth();
        let dayDiff = today.getDate() - dob.getDate();

        // agar month ya day abhi complete nahi hua to age ek kam kar do
        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
            age--;
        }

        ageInput.value = age;
    });
});
</script>



</body>

</html>
