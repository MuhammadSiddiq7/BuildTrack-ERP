@extends('layout.master')

@section('title', 'Employee Details')
@section('header-title', 'Employee Details')

@section('content')


<style>
    .label {
        font-weight: 600;
        color: #495057;
    }

    .value {
        font-weight: 500;
    }

    .bg-light-box {
        background-color: #f8f9fa;
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 1rem;
    }
</style>

<div class="container">
    <!-- <h2>Employee Details</h2> -->

    <div class="card mb-4">

        <div class="card-body">
            <h4 class="mb-3">Employee Details:</h4>
            @foreach ($employee as $show)
            <div class="row bg-light-box">
                <div class="col-md-4">
                    <div class="label">Employee Code:</div>
                    <div class="value">{{ $show->employee_id ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="label">Employee Name:</div>
                    <div class="value">{{ $show->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="label">Fathers Name:</div>
                    <div class="value">{{ $show->father_name ?? '-' }}</div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">Designation:</div>
                    <div class="value">{{ $show->designation->name ?? '-' }}</div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">Joining Date:</div>
                    <div class="value">{{ $show->joining_date ?? '-' }}</div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">Tenure at ADCC :</div>
                    <span class=" fs-6">{{ $show->tenure ?? '-' }}</span>
                </div>

                <div class="col-md-4 mt-3">
                    <div class="label">CNIC Number:</div>
                    <span class=" fs-6">{{ $show->cnic ?? '-' }}</span>
                </div>

                <div class="col-md-4 mt-3">
                    <div class="label">Mobile Number:</div>
                    <span class=" fs-6">{{ $show->mobile_number ?? '-' }}</span>
                </div>

                <div class="col-md-4 mt-3">
                    <div class="label">Bank Account No:</div>
                    <span class=" fs-6">{{ $show->account_number ?? '-' }}</span>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">Date Of Birth:</div>
                    <span class=" fs-6">{{ $show->dob ?? '-' }}</span>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">Age:</div>
                    <span class=" fs-6">{{ $show->age ?? '-' }}</span>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">Education:</div>
                    <span class=" fs-6">{{ $show->education ?? '-' }}</span>
                </div>

                <div class="col-md-4 mt-3">
                    <div class="label">Deployment (Location/area):</div>
                    <span class=" fs-6">{{ $show->deployment_area ?? '-' }}</span>
                </div>

                <div class="col-md-4 mt-3">
                    <div class="label">Project:</div>
                    <span class="fs-6">{{ $show->project->project_name ?? '-' }}</span>
                </div>


                <div class="col-md-4 mt-3">
                    <div class="label">Employee Department:</div>
                    <span class=" fs-6">{{ $show->department->name ?? '-' }}</span>
                </div>

                <div class="col-md-4 mt-3">
                    <div class="label">Employee Status:</div>
                    <span class="badge fs-6 {{ $show->employee_status == 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($show->employee_status) ?? '-' }}
                    </span>
                </div>


                <div class="col-md-4 mt-3">
                    <div class="label">Employee Comment:</div>
                    <span class=" fs-6">{{ $show->comment ?? '-' }}</span>
                </div>


                <div class="col-md-4 mt-3">
                    <div class="label">Employee Image:</div>
                    @if($show->employee_picture)
                    <img style="height: 150px; width: 150px" src="{{ asset($show->employee_picture) }}"
                        alt="Employee Image"
                        class="img-thumbnail mt-2"
                        width="120">
                    @else
                    <span class="fs-6">-</span>
                    @endif
                </div>



            </div>
            @endforeach

            @php
            $currentUser = auth()->user();
            @endphp

            @if($currentUser && $currentUser->email == 'hr@gmail.com')

            <h4 class="mt-4 mb-2">Employee Allowances:</h4>
            @foreach ($employee_allowance as $show)
            <div class="row bg-light-box">
                <div class="col-md-4 mt-3">
                    <div class="label">Company Vehicle:</div>
                    <div class="value">{{ $show->company_vehicle ? 'Yes' : 'No' }}</div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">Fuel Allowance (Vehicle):</div>
                    <div class="value">{{ $show->fuel_allowance_vehicle ?? '-' }}</div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">Calling Card:</div>
                    <div class="value">{{ $show->calling_card ? 'Yes' : 'No' }}</div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">Calling Card Amount:</div>
                    <div class="value">{{ $show->card_account ?? '-' }}</div>
                </div>
            @foreach ($employee_salary as $show)

                <div class="col-md-4 mt-3">
                    <div class="label">Fuel Allowance (Monthly):</div>
                    <div class="value">{{ $show->fuel_allowance_monthly ? number_format($show->fuel_allowance_monthly) : '-' }}</div>
                </div>
            @endforeach



            </div>
            @endforeach

            <h4 class="mt-4 mb-2">Employee Salary:</h4>
            @foreach ($employee_salary as $show)
            <div class="row bg-light-box">
                <div class="col-md-4 mt-3">
                    <div class="label">Gross Salary:</div>
                    <div class="value">{{ $show->gross_salary ? number_format($show->gross_salary) : '-' }}</div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">Basic Salary:</div>
                    <div class="value">{{ $show->basic_salary ? number_format($show->basic_salary) : '-' }}</div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">House Rent:</div>
                    <div class="value">{{ $show->house_rent ? number_format($show->house_rent) : '-' }}</div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">Medical:</div>
                    <div class="value">{{ $show->medical ? number_format($show->medical) : '-' }}</div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="label">Utilities:</div>
                    <div class="value">{{ $show->utilities ? number_format($show->utilities) : '-' }}</div>
                </div>


                @foreach ($employee_allowance as $show)
                <div class="col-md-4 mt-3">
                    <div class="label">Scale/Level:</div>
                    <div class="value">{{ $show->scale_level ?? '-' }}</div>
                </div>
                @endforeach

            </div>
            @endforeach


            @endif




            <div class="mt-4">
                <a href="{{ route('employee.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>

        </div>
    </div>
</div>

{{-- Include Bootstrap 5 JavaScript if not already --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
