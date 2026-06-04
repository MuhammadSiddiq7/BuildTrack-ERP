<!DOCTYPE html>
<html lang="en">
<link rel="icon" type="image/png" href="{{ asset('assets/img/logo/adcc.png') }}" />
<title>ADCC - Payroll PDF</title>

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        margin: 0;
        padding: 0;
    }

    .invoice-box {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        border: 1px solid #153d77;
        max-width: 900px;
        margin: 20px auto;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid #153d77;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }

    .invoice-header img {
        height: 60px;
    }

    .invoice-title {
        font-size: 26px;
        font-weight: bold;
        color: #153d77;
    }

    .top-info {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .section-title {
        font-size: 16px;
        font-weight: bold;
        margin-top: 20px;
        color: #153d77;
        border-bottom: 1px solid #d04636;
        padding-bottom: 4px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-top: 10px;
    }

    table th,
    table td {
        padding: 8px;
        border: 1px solid #ddd;
    }

    table th {
        background: #e8edf7;
        color: #153d77;
    }

    .table-summary th,
    .table-summary td {
        background: #fcebea;
        color: #d04636;
        font-weight: bold;
    }

    .remarks {
        margin-top: 20px;
        font-size: 13px;
        color: #555;
    }

    .notes {
        margin-top: 20px;
        font-size: 13px;
        color: #555;
        text-align: center;
    }

    .download-btn {
        text-align: right;
        margin: 10px auto 10px auto;
        max-width: 900px;
    }

    .btn-custom {
        background-color: #153d77;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
    }
</style>

<body>

    @if (empty($pdf))
        <div class="download-btn">
            <a href="{{ route('payrolls.pdf.download', $payroll->id) }}" class="btn-custom">
                Download PDF
            </a>
        </div>
    @endif

    <div class="invoice-box">
        <div class="invoice-header clearfix">
            <div style="display: inline-block; width: 48%;">
                @if (!empty($pdf))
                    <img src="{{ public_path('assets/img/logo/adcc.png') }}" alt="Logo" style="height: 100px;">
                @else
                    <img src="{{ asset('assets/img/logo/adcc.png') }}" alt="Logo" style="height: 60px;">
                @endif
            </div>
            <div style="display: inline-block; width: 48%; text-align: center; font-size: 15px; font-weight: bold; color: #153d77;">
                Anchor Development & Construction Company (Pvt.) Ltd
            </div>
            <div style="display: inline-block; width: 48%; text-align: right; font-size: 26px; font-weight: bold; color: #153d77;">
                Payslip
            </div>
        </div>

        <div class="top-info clearfix" style="margin-bottom: 15px; font-size: 14px;">
            <div style="display: inline-block; width: 48%;"><strong>Employee Name:</strong>
                {{ $payroll->employee->name }}</div>
            <div style="display: inline-block; width: 48%; text-align: right;"><strong>Pay Date:</strong>
                {{ \Carbon\Carbon::parse($payroll->payment_date)->format('d-m-Y') }}</div>
        </div>
        <div class="top-info clearfix" style="margin-bottom: 15px; font-size: 14px;">
            <div style="display: inline-block; width: 48%;"><strong>Project Salary:</strong>
                {{ $payroll->project->project_name }}</div>
            <div style="display: inline-block; width: 48%; text-align: right;"><strong>Bank:</strong>
                {{ $payroll->companyBank->name }}</div>
        </div>

        <div class="section-title">Salary Details</div>
        <table>
            <tr>
                <td>Basic Salary</td>
                <td>{{ number_format($payroll->basic_salary, 2) }}</td>
            </tr>
            <tr>
                <td>Medical Allowance</td>
                <td>{{ number_format($payroll->medical_allowance, 2) }}</td>
            </tr>
            <tr>
                <td>House Rent</td>
                <td>{{ number_format($payroll->house_rent, 2) }}</td>
            </tr>
            <tr>
                <td>Utilities</td>
                <td>{{ number_format($payroll->utilities, 2) }}</td>
            </tr>
            <tr>
                <td>Gross Salary</td>
                <td>{{ number_format($payroll->gross_salary, 2) }}</td>
            </tr>
            <tr>
                <td>Arrears</td>
                <td>{{ number_format($payroll->arrears, 2) }}</td>
            </tr>
            <tr>
                <td>Recovery</td>
                <td>{{ number_format($payroll->recovery, 2) }}</td>
            </tr>
            <tr>
                <td>Security Deposit</td>
                <td>{{ number_format($payroll->security_deposit, 2) }}</td>
            </tr>
            <tr>
                <td>Income Tax</td>
                <td>{{ number_format($payroll->income_tax, 2) }}</td>
            </tr>
            <tr>
                <td>Absenteeism</td>
                <td>{{ number_format($payroll->absenteeism, 2) }}</td>
            </tr>
        </table>

        <div class="section-title">Summary</div>
        <table class="table-summary">
            <tr>
                <td>Net Pay</td>
                <td>{{ number_format($payroll->net_pay, 2) }}</td>
            </tr>
        </table>

        <div class="remarks">
            <strong>Remarks:</strong>
            <p>If you have any queries regarding this payslip, please contact the HR department.</p>
        </div>

        <div class="notes">
            <p>Thank you for being a valuable part of the team!</p>
        </div>
    </div>
</body>

</html>
