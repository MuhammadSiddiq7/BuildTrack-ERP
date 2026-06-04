@extends('layout.master')
@section('title', 'Payroll Details')
@section('header-title', 'Payroll Details')

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

    <div class="container mt-4">
        <!-- Back Button -->
        <a href="{{ url('payroll/') }}" class="btn btn-sm btn-secondary mb-3">
            <i class="bi bi-arrow-left-circle"></i> Back to Payrolls
        </a>
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Payroll Summary</h4>
                    <h5 class="mt-4 mb-2">Employee Details</h5>
                    <div class="row bg-light-box">
                        <div class="col-md-4">
                            <div class="label">Employee ID:</div>
                            <div class="value">{{ $payroll->employee->employee_id ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="label">Employee Name:</div>
                            <div class="value">{{ $payroll->employee->name ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="label">Designation:</div>
                            <div class="value">{{ $payroll->employee->designation->name ?? '—' }}</div>
                        </div>
                    </div>
                    <h5 class="mt-4 mb-2">Salary Details</h5>
                    <div class="row bg-light-box">
                        <div class="col-md-4">
                            <div class="label">Pay Date:</div>
                            <div class="value">{{ \Carbon\Carbon::parse($payroll->payment_date)->format('d M Y') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="label">Month:</div>
                            <div class="value">{{ $payroll->pay_date ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="label">Basic Salary:</div>
                            <div class="value">Rs. {{ number_format($payroll->basic_salary, 2) }}</div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="label">Medical Allowance:</div>
                            <div class="value">Rs. {{ number_format($payroll->medical_allowance, 2) }}</div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="label">House Rent:</div>
                            <div class="value">Rs. {{ number_format($payroll->house_rent, 2) }}</div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="label">Utilities:</div>
                            <div class="value">Rs. {{ number_format($payroll->utilities, 2) }}</div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="label">Gross Salary:</div>
                            <div class="value">Rs. {{ number_format($payroll->gross_salary, 2) }}</div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="label">Arrears:</div>
                            <div class="value">Rs. {{ number_format($payroll->arrears, 2) }}</div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="label">Recovery:</div>
                            <div class="value">Rs. {{ number_format($payroll->recovery, 2) }}</div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="label">Security Deposit:</div>
                            <div class="value">Rs. {{ number_format($payroll->security_deposit, 2) }}</div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="label">Income Tax:</div>
                            <div class="value">Rs. {{ number_format($payroll->income_tax, 2) }}</div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="label">Absenteeism:</div>
                            <div class="value">Rs. {{ number_format($payroll->absenteeism, 2) }}</div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="label">Net Salary:</div>
                            <span class="badge bg-success fs-6">Rs. {{ number_format($payroll->net_pay, 2) }}</span>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-2">Leave</h5>
                    <div class="row bg-light-box">
                        <div class="col-md-6">
                            <div class="label">Paid Leaves:</div>
                            <div style="background-color:#28a745; color:#fff; display:inline-block; padding:1px 10px; border-radius:5px;">
                                Total: {{ $payroll->paid_leave }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="label">Unpaid Leaves:</div>
                            <div style="background-color:#dc3545; color:#fff; display:inline-block; padding:1px 10px; border-radius:5px;">
                                Total: {{ $payroll->unpaid_leave }}
                            </div>
                        </div>
                    </div>

                {{-- <h4 class="mb-3">Payroll Summary</h4>
                <div class="row bg-light-box">
                    <div class="col-md-4">
                        <div class="label">Employee:</div>
                        <div class="value">{{ $payroll->employee->name ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="label">Company Bank:</div>
                        <div class="value">{{ $payroll->companyBank->name ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="label">Pay Date:</div>
                        <div class="value">{{ \Carbon\Carbon::parse($payroll->payment_date)->format('d M Y') }}</div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="label">Net Salary:</div>
                        <span class="badge bg-success fs-6">Rs. {{ number_format($payroll->net_salary, 2) }}</span>
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="label">Deduction:</div>
                        <span class="badge bg-danger fs-6">Rs. {{ number_format($payroll->deductions, 2) }}</span>
                    </div>
                    <h5 class="mt-4 mb-2">Leave</h5>
                    <div class="row bg-light-box">
                        <div class="col-md-6">
                            <div class="label">Paid Leaves:</div>
                            <div
                                style="background-color:#28a745; color:#fff; display:inline-block; padding:1px 10px; border-radius:5px;">
                                Total: {{ $payroll->paid_leave }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="label">Unpaid Leaves:</div>
                            <div
                                style="background-color:#dc3545; color:#fff; display:inline-block; padding:1px 10px; border-radius:5px;">
                                Total: {{ $payroll->unpaid_leave }}
                            </div>
                        </div>
                    </div>
                </div> --}}
                @if ($payroll->remarks)
                    <h5 class="mt-4 mb-2">Remarks</h5>
                    <div class="bg-light-box">
                        <p class="mb-0">{{ $payroll->remarks }}</p>
                    </div>
                @endif

                <!-- Footer Back Button -->
                <div class="mt-4">
                    <a href="{{ url('payroll/') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Payrolls
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
