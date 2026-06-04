<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ADCC - Payroll PDF</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/adcc.png') }}" />
   <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        margin: 10px;
        padding: 10px;
        background: #fff;
        font-size: 12px;
    }

    .download-btn {
        text-align: right;
        margin: 10px auto;
        max-width: 800px;
    }

    .btn-custom {
        background-color: #153d77;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        font-size: 12px;
    }

    .payslip {
        border: 2px solid #000;
        max-width: 800px;
        width: 100%;
        margin: auto;
        padding: 0;
    }

    .header {
        display: flex;
        align-items: center;
        border-bottom: 2px solid #000;
        padding: 8px;
    }

    .header img {
        height: 60px; /* thoda chhota */
        margin-right: 15px;
    }

    .header-text {
        text-align: center;
        flex: 1;
    }

    .header-text h2 {
        margin: 0;
        font-size: 16px; /* kam kiya */
        font-weight: bold;
    }

    .header-text p {
        margin: 0;
        font-size: 12px;
        font-weight: bold;
    }

    .title {
        text-align: center;
        border-bottom: 2px solid #000;
        padding: 5px;
        font-size: 12px;
        font-weight: bold;
    }

    .employee-info {
        width: 100%;
        border-bottom: 2px solid #000;
        border-collapse: collapse;
        font-size: 12px;
    }

    .employee-info td {
        padding: 4px 8px;
        vertical-align: top;
    }

    .employee-info strong {
        display: inline-block;
        width: 110px;
    }

    .salary-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .salary-table th,
    .salary-table td {
        padding: 5px;
        text-align: left;
    }

    .salary-table th {
        background: #f9f9f9;
    }

    .totals {
        width: 100%;
        border-top: 2px solid #000;
        border-collapse: collapse;
        font-size: 12px;
    }

    .totals td {
        padding: 5px;
    }

    .netpay {
        width: 99%;
        border-top: 2px solid #000;
        border-bottom: 2px solid #000;
        padding: 5px;
        font-weight: bold;
        font-size: 13px; /* thoda highlight rahe */
    }

    .netpay small {
        display: block;
        font-weight: normal;
        font-style: italic;
        margin-top: 4px;
        font-size: 11px;
    }

    .remarks {
        margin-top: 12px;
        font-size: 11px;
        padding-left: 8px;
        color: #333;
    }

    .notes {
        margin-top: 8px;
        font-size: 10px;
        color: #666;
        text-align: center;
    }
</style>

</head>

<body>

    <!-- ✅ Download Button -->
  @if (empty($pdf))
    <div class="download-btn">
        <a href="{{ route('payrolls.pdf.download', $payroll->id) }}" class="btn-custom">
            Download PDF
        </a>
    </div>
@endif


    <div class="payslip">
        <!-- Header -->
        <div class="header">
            @if (!empty($pdf))
                <img src="{{ public_path('assets/img/logo/adcc.png') }}" alt="Logo">
            @else
                <img src="{{ asset('assets/img/logo/adcc.png') }}" alt="Logo">
            @endif
            <div class="header-text">
                <h2>ANCHOR DEVELOPMENT & CONSTRUCTION COMPANY PVT LTD</h2>
                <p>(SOUTH)</p>
            </div>
        </div>

        <!-- Title -->
        <div class="title">
            Payslip For the Month of {{ \Carbon\Carbon::parse($payroll->payment_date)->format('F Y') }}
        </div>

        <!-- Employee Info -->
        <table class="employee-info">
            <tr>
                <td><strong>Employee ID:</strong> {{ $payroll->employee->id ?? '' }}</td>
                <td><strong>Bank Name:</strong> {{ $payroll->companyBank->name ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>Employee Name:</strong> {{ $payroll->employee->name }}</td>
                <td><strong>A/C #:</strong> {{ $payroll->employee->account_no ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>Department:</strong> {{ $payroll->employee->department->name ?? '' }}</td>
                <td><strong>D.O.J:</strong> {{ \Carbon\Carbon::parse($payroll->employee->joining_date)->format('d-M-Y') }}</td>
            </tr>
            <tr>
                <td><strong>Designation:</strong> {{ $payroll->employee->designation->name ?? '' }}</td>
            </tr>
        </table>

        <!-- Salary Table -->
        <table class="salary-table">
            <tr>
                <th>Earnings</th>
                <th>Amount</th>
                <th style="text-align: end !important;">Deductions</th>
                <th style="text-align: end !important;">Amount</th>
            </tr>
            <tr>
                <td>Basic Salary</td>
                <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                <td style="text-align: end !important;">Income Tax</td>
                <td style="text-align: end !important;">{{ number_format($payroll->income_tax, 2) }}</td>
            </tr>
            <tr>
                <td>House Rent</td>
                <td>{{ number_format($payroll->house_rent, 2) }}</td>
                <td style="text-align: end !important;">Absenteeism</td>
                <td style="text-align: end !important;">{{ number_format($payroll->absenteeism, 2) }}</td>
            </tr>
            <tr>
                <td>Medical Allowance</td>
                <td>{{ number_format($payroll->medical_allowance, 2) }}</td>
                <td style="text-align: end !important;">Recovery</td>
                <td style="text-align: end !important;">{{ number_format($payroll->recovery, 2) }}</td>
            </tr>
            <tr>
                <td>Utilities</td>
                <td>{{ number_format($payroll->utilities, 2) }}</td>
                <td style="text-align: end !important;">Security Deposit</td>
                <td style="text-align: end !important;">{{ number_format($payroll->security_deposit, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Gross Salary</strong></td>
                <td><strong>{{ number_format($payroll->gross_salary, 2) }}</strong></td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <!-- Totals -->
        <table class="totals">
            <tr>
                <td><strong>Total Earnings:</strong> {{ number_format($payroll->gross_salary, 2) }}</td>
                <td style="text-align: end !important;">
                    <strong>Total Deductions:</strong>
                    {{ number_format($payroll->income_tax + $payroll->absenteeism + $payroll->recovery + $payroll->security_deposit, 2) }}
                </td>
            </tr>
        </table>

        <!-- Net Pay -->
        <div class="netpay">
            Net Pay: {{ number_format($payroll->net_pay, 2) }}

            <!-- <small>{{ $payroll->net_pay_words ?? 'One Hundred Forty-Four Thousand Six Hundred Ninety-Six Only' }}</small> -->
        </div>

        <!-- Remarks -->
        <div class="remarks">
            <strong>Remarks:</strong>
            <p>This payslip is system generated and does not require any signature or stamp.</p>
        </div>

        <!-- Notes -->
        <div class="notes">
            <p>Thank you for being a valuable part of the team!</p>
        </div>
    </div>

</body>
</html>
