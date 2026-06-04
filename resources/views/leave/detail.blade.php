@extends('layout.master')
@section('title', 'leave Details')
@section('header-title', 'leave Details')
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
    <h2>Leave Details</h2>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-space-between">
                <h3 class="card-title" style="font-size: 22px;">Leave Details</h3>
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-info">Back</a>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Employee ID:</strong> {{ $leave->employee->employee_id ?? 'N/A' }}</p>
                    <p><strong>Employee Name:</strong> {{ $leave->name ?? 'N/A' }}</p>
                    <p><strong>Employee Email:</strong> {{ $leave->email ?? 'N/A' }}</p>
                    <p><strong>Father Name:</strong> {{ $leave->father_name ?? 'N/A' }}</p>
                    <p><strong>Employee Department:</strong> {{ $leave->employee_department ?? 'N/A' }}</p>
                    <p><strong>Employee Designation:</strong> {{ $leave->employee_designation ?? 'N/A' }}</p>
                    <p><strong>Employee CNIC:</strong> {{ $leave->cnic ?? 'N/A' }}</p>
                    <p><strong>Mobile Number:</strong> {{  $leave->mobile_number ?? 'N/A' }}</p>
                    <p><strong>Deployment:</strong> {{ $leave->deployment ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Application Type:</strong> {{ $leave->application_type ?? 'N/A' }}</p>
                    <p><strong>Leave Type:</strong> {{ $leave->leave_type ?? 'N/A' }}</p>

                    <p><strong>Date Of Request:</strong> {{  $leave->date_of_request ?? 'N/A' }}</p>
                    <p><strong>Leave Reason:</strong> {{  $leave->leave_reason ?? 'N/A' }}</p>
                    <p><strong>Start Date:</strong> {{  $leave->start_date ?? 'N/A' }}</p>
                    <p><strong>End Date:</strong> {{  $leave->end_date ?? 'N/A' }}</p>
                    <p><strong>Days Request:</strong> {{  $leave->days_request ?? 'N/A' }}</p>
                    <p><strong>Address During Leave:</strong> {{  $leave->address_during_leave ?? 'N/A' }}</p>



                    <p><strong>Signature:</strong>
                        @if ($leave->employee_signature)
                        <img src="{{ asset($leave->employee_signature) }}" alt="Signature"
                            style="max-width:250px;">
                        @else
                        <span>No Signature</span>
                        @endif
                    </p>
                </div>
                <!-- <div class="col-md-12">
                    <p><strong>Remarks:</strong></p>
                    @php
                    $remarks = [];

                    if (!empty($leave->remarks_by_hr)) {
                    $remarks[] = 'HR: ' . $leave->remarks_by_hr;
                    }
                    if (!empty($leave->remarks_by_operation)) {
                    $remarks[] = 'Operation: ' . $leave->remarks_by_operation;
                    }
                    if (!empty($leave->remarks_by_higher_management)) {
                    $remarks[] = 'Higher Management: ' . $leave->remarks_by_higher_management;
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
