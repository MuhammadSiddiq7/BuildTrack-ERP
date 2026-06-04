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
                            Leave Application Form
                        </h1>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div>
                                            @if (session('success'))
                                            <div class="alert alert-success alert-top" id="alertMessage">
                                                {{ session('success') }}
                                            </div>
                                            @endif
                                            @if (session('error'))
                                            <div class="alert alert-danger alert-top" id="alertMessage">
                                                {{ session('error') }}
                                            </div>
                                            @endif
                                            <form action="{{ route('leave.store') }}" method="POST"
                                                id="default-form-section" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">

                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                                        <div class="mb-3">
                                                            <label for="employee_id"><b>ID Number</b></label>
                                                            <input type="text" name="employee_id" id="employee_id"
                                                                value="{{ old('employee_id') }}"
                                                                class="form-control @error('employee_id') is-invalid @enderror"
                                                                placeholder="Enter ID Number">
                                                            @error('employee_id')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                                        <div class="mb-3">
                                                            <label for="name"><b>Employee Name</b></label>
                                                            <input type="text" id="name" name="name" readonly
                                                                value="{{ old('name') }}"
                                                                class="form-control @error('name') is-invalid @enderror"
                                                                placeholder="Enter Name">
                                                            @error('name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                                        <div class="mb-3">
                                                            <label for="father_name"><b>Father Name</b></label>
                                                            <input type="text" id="father_name" name="Father Name"
                                                                value="{{ old('father_name') }}" readonly
                                                                class="form-control @error('father_name') is-invalid @enderror"
                                                                placeholder="Enter father_name">
                                                            @error('father_name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="cnic"><b>CNIC</b></label>
                                                            <input type="text" id="cnic" name="cnic"
                                                                value="{{ old('cnic') }}" readonly
                                                                class="form-control @error('cnic') is-invalid @enderror"
                                                                placeholder="Enter cnic">
                                                            @error('cnic')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <!-- Department -->
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="department_name"><b>Department</b></label>
                                                            <input type="text" id="department_name" name="employee_department"
                                                                class="form-control" value="{{ old('employee_department') }}" readonly>
                                                            <input type="hidden" id="employee_department_id"
                                                                value="{{ old('employee_department_id') }}">
                                                        </div>
                                                    </div>

                                                    <!-- Designation -->
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="designation_name"><b>Designation</b></label>
                                                            <input type="text" id="designation_name" name="employee_designation"
                                                                class="form-control" value="{{ old('employee_designation') }}" readonly>
                                                            <input type="hidden" id="employee_designation_id"
                                                                value="{{ old('employee_designation_id') }}">
                                                        </div>
                                                    </div>




                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="cnic"><b>Mobile Number</b></label>
                                                            <input type="text" id="mobile_number" name="mobile_number"
                                                                value="{{ old('mobile_number') }}" readonly
                                                                class="form-control @error('mobile_number') is-invalid @enderror"
                                                                placeholder="Enter Mobile Number">
                                                            @error('mobile_number')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="deployment"><b>Deployment</b></label>
                                                            <input type="text" id="deployment" name="deployment"
                                                                value="{{ old('deployment') }}" readonly
                                                                class="form-control @error('deployment') is-invalid @enderror"
                                                                placeholder="Enter Deployment">
                                                            @error('deployment')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>


                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="email"><b>Email</b></label>
                                                            <input type="text" id="email" name="email"
                                                                value="{{ old('email') }}" readonly
                                                                class="form-control @error('email') is-invalid @enderror"
                                                                placeholder="Enter Email">
                                                            @error('email')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>


                                                    <div class="col-md-12 mb-3 mt-3">
                                                        <hr>
                                                    </div>

                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="application_type"><b>Application
                                                                    Type</b></label>
                                                            <select class="form-control mb-3" name="application_type"
                                                                id="application_type">
                                                                <option disabled selected>Select Application Type
                                                                </option>
                                                                <option value="earned"
                                                                    {{ old('application_type') == 'earned' ? 'selected' : '' }}>
                                                                    Earned Leave
                                                                </option>
                                                                <option value="casual"
                                                                    {{ old('application_type') == 'casual' ? 'selected' : '' }}>
                                                                    Casual Leave
                                                                </option>
                                                            </select>
                                                            @error('application_type')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="leave_type"><b>Leave Type</b></label>
                                                            <select class="form-control mb-3" name="leave_type"
                                                                id="leave_type">
                                                                <option disabled selected>Select Leave Type</option>
                                                                <option value="paid"
                                                                    {{ old('leave_type') == 'paid' ? 'selected' : '' }}>
                                                                    Paid</option>
                                                                <option value="unpaid"
                                                                    {{ old('leave_type') == 'unpaid' ? 'selected' : '' }}>
                                                                    Unpaid</option>
                                                            </select>
                                                            @error('leave_type')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>




                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="date_of_request"><b>Date Of Request</b></label>
                                                            <input type="date" id="date_of_request"
                                                                name="date_of_request"
                                                                value="{{ old('date_of_request') }}"
                                                                class="form-control @error('date_of_request') is-invalid @enderror">
                                                            @error('date_of_request')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="leave_reason"><b>Reason For Leave</b></label>
                                                            <input type="text" id="leave_reason"
                                                                name="leave_reason"
                                                                value="{{ old('leave_reason') }}"
                                                                class="form-control @error('leave_reason') is-invalid @enderror">
                                                            @error('leave_reason')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                                        <div class="mb-3">
                                                            <label for="holiday_start_date"><b>Start Date</b></label>
                                                            <input type="date" name="start_date"
                                                                id="holiday_start_date"
                                                                value="{{ old('start_date') }}"
                                                                class="form-control @error('start_date') is-invalid @enderror">
                                                            @error('start_date')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                                        <div class="mb-3">
                                                            <label for="holiday_end_date"><b>End Date</b></label>
                                                            <input type="date" name="end_date"
                                                                id="holiday_end_date" value="{{ old('end_date') }}"
                                                                class="form-control @error('end_date') is-invalid @enderror">
                                                            @error('end_date')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                                        <div class="mb-3">
                                                            <label for="holiday_days_request"><b>Days
                                                                    Request</b></label>
                                                            <input type="number" name="days_request"
                                                                id="holiday_days_request" id="days_request"
                                                                value="{{ old('days_request') }}"
                                                                class="form-control @error('days_request') is-invalid @enderror"
                                                                placeholder="Enter Days Request" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="address_during_leave"><b>Address during Leave</b></label>
                                                            <input type="text" id="address_during_leave"
                                                                name="address_during_leave"
                                                                value="{{ old('address_during_leave') }}"
                                                                class="form-control @error('address_during_leave') is-invalid @enderror">
                                                            @error('address_during_leave')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <!-- Signature Box 1 -->
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                        <!-- Form 1 Signature -->
                                                        <div class="mb-3">
                                                            <label><b>Employee Signature</b></label>
                                                            <div
                                                                style="border: 2px solid #ccc; border-radius: 10px; padding: 10px;">
                                                                <canvas id="canvas1" width="585" height="200"
                                                                    style="background:#f9f9f9;"></canvas>
                                                            </div>
                                                            <input type="hidden" name="employee_signature"
                                                                id="signature1">
                                                            <button type="button" onclick="clearCanvas1()"
                                                                class="btn btn-sm btn-danger mt-2">Clear</button>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <button type="submit" class="btn btn-primary"><b>Submit
                                                                Application</b></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
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
    <style>
        .alert-top {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 250px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.3s ease-in-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
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
        // ------------ Canvas 1 ----------------
        const canvas1 = document.getElementById("canvas1");
        const ctx1 = canvas1.getContext("2d");
        let isDrawing1 = false;

        canvas1.addEventListener("mousedown", e => {
            isDrawing1 = true;
            ctx1.beginPath();
            ctx1.moveTo(e.offsetX, e.offsetY);
        });

        canvas1.addEventListener("mousemove", e => {
            if (!isDrawing1) return;
            ctx1.lineTo(e.offsetX, e.offsetY);
            ctx1.strokeStyle = "#000";
            ctx1.lineWidth = 2;
            ctx1.stroke();
        });

        canvas1.addEventListener("mouseup", () => {
            isDrawing1 = false;
            document.getElementById("signature1").value = canvas1.toDataURL("image/png");
        });

        canvas1.addEventListener("mouseleave", () => {
            isDrawing1 = false;
        });

        function clearCanvas1() {
            ctx1.clearRect(0, 0, canvas1.width, canvas1.height);
            document.getElementById("signature1").value = "";
        }


        // ------------ Canvas 2 ----------------
        const canvas2 = document.getElementById("canvas2");
        const ctx2 = canvas2.getContext("2d");
        let isDrawing2 = false;

        canvas2.addEventListener("mousedown", e => {
            isDrawing2 = true;
            ctx2.beginPath();
            ctx2.moveTo(e.offsetX, e.offsetY);
        });

        canvas2.addEventListener("mousemove", e => {
            if (!isDrawing2) return;
            ctx2.lineTo(e.offsetX, e.offsetY);
            ctx2.strokeStyle = "#000";
            ctx2.lineWidth = 2;
            ctx2.stroke();
        });

        canvas2.addEventListener("mouseup", () => {
            isDrawing2 = false;
            document.getElementById("signature2").value = canvas2.toDataURL("image/png");
        });

        canvas2.addEventListener("mouseleave", () => {
            isDrawing2 = false;
        });

        function clearCanvas2() {
            ctx2.clearRect(0, 0, canvas2.width, canvas2.height);
            document.getElementById("signature2").value = "";
        }
    </script>



    <script>
        function setupDateAutoFill(startId, endId, daysId) {
            const startDateInput = document.getElementById(startId);
            const endDateInput = document.getElementById(endId);
            const daysInput = document.getElementById(daysId);
            if (startDateInput && endDateInput && daysInput) {
                const calculateDays = () => {
                    const start = new Date(startDateInput.value);
                    const end = new Date(endDateInput.value);
                    if (!isNaN(start) && !isNaN(end) && end >= start) {
                        const timeDiff = end.getTime() - start.getTime();
                        const totalDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                        daysInput.value = totalDays;
                    } else {
                        daysInput.value = '';
                    }
                };
                startDateInput.addEventListener('change', calculateDays);
                endDateInput.addEventListener('change', calculateDays);
            }
        }
        setupDateAutoFill('holiday_start_date', 'holiday_end_date', 'holiday_days_request');
        setupDateAutoFill('sick_start_date', 'sick_end_date', 'sick_days_request');
        setupDateAutoFill('emergency_start_date', 'emergency_end_date', 'emergency_days_request');
    </script>




    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('days_request_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            html: `{!! session('days_request_error') !!}`,
            confirmButtonText: 'Okay',
            confirmButtonColor: '#153d77'
        });
    </script>
    @endif


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const defaultSection = document.getElementById('default-form-section');
            const guardforceSection = document.getElementById('guardforce-fields');

            const hiddenCompanyInput = document.getElementById('final_company_id');
            const hiddenDeptInput = document.getElementById('final_department_id');

            function setSelectedOption(selectElement, valueToSelect) {
                for (let i = 0; i < selectElement.options.length; i++) {
                    if (selectElement.options[i].value == valueToSelect) {
                        selectElement.selectedIndex = i;
                        break;
                    }
                }
            }

            // function fillFormFields(data) {
            //     const name = document.getElementById('name');
            //     const father_name = document.getElementById('father_name');
            //     const cnic = document.getElementById('cnic');
            //     const company = document.getElementById('company_id');
            //     const dept = document.getElementById('employee_department_id');
            //     const employee_id = document.getElementById('employee_id');

            //     if (name) name.value = data.name || '';
            //     if (father_name) father_name.value = data.father_name || '';
            //     if (cnic) cnic.value = data.cnic || '';
            //     if (company) company.value = data.company_id || '';
            //     if (dept) dept.value = data.employee_department_id || '';
            //     if (employee_id && data.employee_id) employee_id.value = data.employee_id;
            //     if (hiddenDeptInput) hiddenDeptInput.value = data.employee_department_id || '';
            // }

            function fillFormFields(data) {
                document.getElementById('name').value = data.name || '';
                document.getElementById('father_name').value = data.father_name || '';
                document.getElementById('cnic').value = data.cnic || '';
                document.getElementById('mobile_number').value = data.mobile_number || '';
                document.getElementById('deployment').value = data.deployment_area || '';
                document.getElementById('email').value = data.email || '';

                // ✅ Department
                document.getElementById('department_name').value = data.department_name || '';
                document.getElementById('employee_department_id').value = data.employee_department_id || '';

                // ✅ Designation
                document.getElementById('designation_name').value = data.designation_name || '';
                document.getElementById('employee_designation_id').value = data.designation_id || '';

                // baki optional fields
                if (document.getElementById('account_number')) {
                    document.getElementById('account_number').value = data.account_number || '';
                }
                if (document.getElementById('dob')) {
                    document.getElementById('dob').value = data.dob || '';
                }
                if (document.getElementById('education')) {
                    document.getElementById('education').value = data.education || '';
                }
            }



            function fetchAndFill(idNumber) {
                if (idNumber !== '') {
                    fetch(`{{ url('get-employee') }}/${idNumber}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Employee not found');
                            }
                            return response.json();
                        })
                        .then(data => fillFormFields(data))
                        .catch(error => {
                            alert('Employee not found');
                            console.error('Error:', error);
                        });
                }
            }

            function debounce(fn, delay) {
                let timer;
                return function(...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn.apply(this, args), delay);
                };
            }

            const idInput = document.getElementById('employee_id');
            const guardIdInput = document.getElementById('guard_employee_id');

            if (idInput) {
                idInput.addEventListener('input', debounce(function() {
                    fetchAndFill(this.value.trim());
                }, 700));
            }

            if (guardIdInput) {
                guardIdInput.addEventListener('input', debounce(function() {
                    fetchAndFill(this.value.trim());
                }, 700));
            }

            // Initial state
            if (guardforceSection && defaultSection) {
                guardforceSection.style.display = 'none';
                defaultSection.style.display = 'flex';
            }

            if (mainSelect) mainSelect.selectedIndex = 0;
            if (guardforceSelect) guardforceSelect.selectedIndex = 0;
        });
    </script>



</body>

</html>
