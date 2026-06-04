@extends('layout.master')
@section('title', 'application Details')
@section('header-title', 'application Details')
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
    <h2>Application Details</h2>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-space-between">
            <h3 class="card-title" style="font-size: 22px;">application Details</h3>
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-info">Back</a>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <!-- <p><strong>Employee ID:</strong> {{ $application->employee->employee_id ?? 'N/A' }}</p> -->
                    <p><strong>Name:</strong> {{ $application->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $application->email ?? 'N/A' }}</p>
                    <p><strong>Father Name:</strong> {{ $application->father_name ?? 'N/A' }}</p>
                    <p><strong>CNIC:</strong> {{ $application->cnic ?? 'N/A' }}</p>
                    <p><strong>Date Of Birth:</strong> {{ $application->dob ?? 'N/A' }}</p>

                    <p><strong>Age:</strong> {{ $application->age ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Gender:</strong> {{ $application->gender ?? 'N/A' }}</p>
                    <p><strong>Marital Status:</strong> {{ $application->marital_status ?? 'N/A' }}</p>
                    <p><strong>Previous Address:</strong> {{ $application->previous_address ?? 'N/A' }}</p>
                    <p><strong>Nationality:</strong> {{ $application->nationality ?? 'N/A' }}</p>
                    <p><strong>City:</strong> {{ $application->city ?? 'N/A' }}</p>
                </div>
                <hr>

                <h3 class="card-title" style="font-size: 18px !important;">Job Related Info:</h3>
                <div class="col-md-6">
                    <p><strong>Department:</strong> {{ $application->employee_department_id ?? 'N/A' }}</p>
                    <p><strong>Designation:</strong> {{ $application->designation_id ?? 'N/A' }}</p>
                    <p><strong>Currently Employed Company:</strong> {{ $application->currently_employed_company ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">


                    <p><strong>Currently Employed Designation:</strong> {{ $application->currently_employed_designation ?? 'N/A' }}</p>
                    <p><strong>Currently Salary:</strong> {{ $application->currently_salary ?? 'N/A' }}</p>
                    <p><strong>Allowances:</strong> {{ $application->allowances ?? 'N/A' }}</p>

                </div>

                <hr>
                <div class="col-md-12">
                    <p><strong>Resume:</strong>
                        @if ($application->resume)
                        <a href="{{ asset($application->resume) }}" target="_blank">View File</a>
                        @else
                        <span>No Resume</span>
                        @endif
                    </p>
                </div>





                <!-- <div class="col-md-12">
                    <p><strong>Remarks:</strong></p>
                    @php
                    $remarks = [];

                    if (!empty($application->remarks_by_hr)) {
                    $remarks[] = 'HR: ' . $application->remarks_by_hr;
                    }
                    if (!empty($application->remarks_by_operation)) {
                    $remarks[] = 'Operation: ' . $application->remarks_by_operation;
                    }
                    if (!empty($application->remarks_by_higher_management)) {
                    $remarks[] = 'Higher Management: ' . $application->remarks_by_higher_management;
                    }
                    @endphp

                    @if (count($remarks) > 0)
                    @foreach ($remarks as $line)
                    <p>{{ $line }}</p>
                    @endforeach
                    @else
                    <p>N/A</p>
                    @endif
                </div> -->

            </div>


        </div>
    </div>

</div>
@endsection
