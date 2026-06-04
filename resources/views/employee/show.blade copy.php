@extends('layout.master')

@section('title', 'Employee Details')
@section('header-title', 'Employee Details')

@section('content')
    <style>
        .images-row {
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .images-row>div {
            flex: 0 0 19%;
            text-align: center;
        }

        .images-row img {
            max-height: 100px;
            width: auto;
            display: inline-block;
        }
    </style>

    <div class="container">
        <h2>Employee Details</h2>

        <div class="card mb-4">
            <div class="card-header">
                <a href="{{ url()->previous() }}" class="btn btn btn-info mb-2">
                    <i class="bi bi-arrow-left"></i> <b>Back</b>
                </a>
                <ul class="nav nav-pills card-header-pills pull-right" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-basic">Basic Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-labour">Labour Card</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-passport">Passport</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-visa">Visa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-emirates">Emirates ID</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-other">Other Info</a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">

                    {{-- Basic Info --}}
                    <div class="tab-pane fade show active" id="tab-basic">
                        <p><strong>Name:</strong> {{ $employee->name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $employee->email ?? 'N/A' }}</p>
                        <p><strong>Mobile Number:</strong> {{ $employee->mobile_number ?? 'N/A' }}</p>
                        <p><strong>Company:</strong> {{ $employee->company->name ?? 'N/A' }}</p>
                        <p><strong>Number of Working days:</strong> {{ $employee->number_of_working_days ?? 'N/A' }}</p>
                        <p><strong>Per day Salary:</strong> {{ $employee->per_day_salary ?? 'N/A' }}</p>

                        <p><strong>Joining Date:</strong> {{ $employee->joining_date ?? 'N/A' }}</p>
                        <p><strong>Person Code:</strong> {{ $employee->person_code ?? 'N/A' }}</p>
                        <p><strong>ID Number:</strong> {{ $employee->id_number ?? 'N/A' }}</p>
                        <p><strong>Nationality:</strong> {{ $employee->nationality ?? 'N/A' }}</p>
                        <p><strong>Department:</strong> {{ $employee->department->name ?? 'N/A' }}</p>
                        <p><strong>Designation:</strong> {{ $employee->designation->name ?? 'N/A' }}</p>
                    </div>

                    {{-- Labour Card --}}
                    <div class="tab-pane fade" id="tab-labour">
                        <p><strong>Labour Card Number:</strong> {{ $employee->labour_card_number ?? 'N/A' }}</p>
                        <p><strong>Labour Card Issue Date:</strong> {{ $employee->labour_card_number_issue_date ?? 'N/A' }}
                        </p>
                        <p><strong>Labour Card Expiry Date:</strong>
                            {{ $employee->labour_card_number_expiry_date ?? 'N/A' }}</p>
                        <p><strong>Labour Card Validity Days:</strong> {{ $employee->labour_card_validity_days ?? 'N/A' }}
                        </p>
                        <p><strong>Labour Card Status:</strong> {{ ucfirst($employee->labour_card_status) ?? 'N/A' }}</p>
                    </div>

                    {{-- Passport --}}
                    <div class="tab-pane fade" id="tab-passport">
                        <p><strong>Passport Number:</strong> {{ $employee->passport_number ?? 'N/A' }}</p>
                        <p><strong>Passport Issue Date:</strong> {{ $employee->passport_issue_date ?? 'N/A' }}</p>
                        <p><strong>Passport Expiry Date:</strong> {{ $employee->passport_expiry_date ?? 'N/A' }}</p>
                        <p><strong>Passport Validity Days:</strong> {{ $employee->passport_validity_days ?? 'N/A' }}</p>
                        <p><strong>Passport Status:</strong> {{ ucfirst($employee->passport_status) ?? 'N/A' }}</p>
                    </div>

                    {{-- Visa --}}
                    <div class="tab-pane fade" id="tab-visa">
                        <p><strong>Visa Expiry Date:</strong> {{ $employee->visa_expiry_date ?? 'N/A' }}</p>
                        <p><strong>Visa Validity Days:</strong> {{ $employee->visa_validity_days ?? 'N/A' }}</p>
                        <p><strong>Visa Status:</strong> {{ ucfirst($employee->visa_status) ?? 'N/A' }}</p>
                    </div>

                    {{-- Emirates ID --}}
                    <div class="tab-pane fade" id="tab-emirates">
                        <p><strong>Emirates ID Number:</strong> {{ $employee->emirates_id_number ?? 'N/A' }}</p>
                        <p><strong>Emirates ID Expiry Date:</strong> {{ $employee->emirates_id_expiry_date ?? 'N/A' }}</p>
                        <p><strong>Emirates ID Validity Days:</strong> {{ $employee->emirates_id_validity_days ?? 'N/A' }}
                        </p>
                        <p><strong>Emirates ID Status:</strong> {{ ucfirst($employee->emirates_id_status) ?? 'N/A' }}</p>
                    </div>

                    {{-- Other Info --}}
                    <div class="tab-pane fade" id="tab-other">
                        <p><strong>Date of Birth:</strong> {{ $employee->date_of_birth ?? 'N/A' }}</p>
                        <p><strong>ILOE Insurance Date:</strong> {{ $employee->iloe_insurance_date ?? 'N/A' }}</p>
                        <p><strong>Medical Insurance Issue Date:</strong>
                            {{ $employee->medical_insurance_issue_date ?? 'N/A' }}</p>
                        <p><strong>Medical Insurance Expiry Date:</strong>
                            {{ $employee->medical_insurance_expiry_date ?? 'N/A' }}</p>
                        {{-- Sira Card --}}
                        <p><strong>SIRA Card Number:</strong> {{ $employee->sira_card_number ?? 'N/A' }}</p>
                        <p><strong>SIRA Card Issue Date:</strong> {{ $employee->valid_sira_cartificate_date ?? 'N/A' }}</p>
                        <p><strong>SIRA Card Expiry Date:</strong> {{ $employee->sira_card_expiry_date ?? 'N/A' }}</p>
                        {{-- Life Guard License --}}
                        <p><strong>Life Guard License Number:</strong> {{ $employee->life_guard_license_number ?? 'N/A' }}
                        </p>
                        <p><strong>Life Guard License Expiry:</strong>
                            {{ $employee->life_guard_license_expiry_date ?? 'N/A' }}</p>
                        <p><strong>ACT Training Date:</strong> {{ $employee->act_training_date ?? 'N/A' }}</p>

                        {{-- JAFZA Pass --}}
                        <p><strong>JAFZA Pass Valid Date:</strong> {{ $employee->jafza_pass_valid_date ?? 'N/A' }}</p>
                        <p><strong>JAFZA Pass End Date:</strong> {{ $employee->jafza_pass_end_date ?? 'N/A' }}</p>
                        <p><strong>Employee Status:</strong> {{ $employee->employee_status ?? 'N/A' }}</p>

                        <p><strong>Comment:</strong> {{ $employee->comment ?? 'N/A' }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Include Bootstrap 5 JavaScript if not already --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
