@extends('layout.master')
@section('title', 'Create Payslip')
@section('header-title', 'Create Payslip')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                    {{-- <div class="row"> --}}
                        <!-- EMPLOYEE -->
                         <form action="{{ route('payrolls.store') }}" method="POST">
        @csrf

        <!-- Employee Select -->
        <div class="form-group mb-3">
            <label for="employee_id">Employee</label>
            <select name="employee_id" id="employee_id" class="form-control" required>
                <option value="">-- Select Employee --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->designation->title ?? '' }})</option>
                @endforeach
            </select>
        </div>

        <!-- Basic Salary -->
        <div class="form-group mb-3">
            <label for="basic_salary">Basic Salary</label>
            <input type="number" step="0.01" name="basic_salary" id="basic_salary" class="form-control" placeholder="Enter basic salary" required>
        </div>

        <!-- Allowances -->
        <div class="form-group mb-3">
            <label for="allowances">Allowances</label>
            <input type="number" step="0.01" name="allowances" id="allowances" class="form-control" placeholder="Enter allowances">
        </div>

        <!-- Deductions -->
        <div class="form-group mb-3">
            <label for="deductions">Deductions</label>
            <input type="number" step="0.01" name="deductions" id="deductions" class="form-control" placeholder="Enter deductions">
        </div>

        <!-- Tax (Auto Calculate JS) -->
        <div class="form-group mb-3">
            <label for="tax">Tax</label>
            <input type="number" step="0.01" name="tax" id="tax" class="form-control" readonly>
        </div>

        <!-- Net Salary -->
        <div class="form-group mb-3">
            <label for="net_salary">Net Salary</label>
            <input type="number"  step="0.01" name="net_salary" id="net_salary" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Save Payroll</button>
    </form>
            </div>
        </div>
    </div>
</div>
<script>
    function calculatePayroll() {
        let basic = parseFloat(document.getElementById('basic_salary').value) || 0;
        let allowances = parseFloat(document.getElementById('allowances').value) || 0;
        let deductions = parseFloat(document.getElementById('deductions').value) || 0;

        let gross = basic + allowances;
        let tax = 0;

        if (gross > 600000 && gross <= 1200000) {
            tax = (gross - 600000) * 0.01;
        } else if (gross > 1200000) {
            tax = (600000 * 0.01) + ((gross - 1200000) * 0.02); // Example extended logic
        }

        let net = gross - deductions - tax;

        document.getElementById('tax').value = tax.toFixed(2);
        document.getElementById('net_salary').value = net.toFixed(2);
    }

    document.getElementById('basic_salary').addEventListener('input', calculatePayroll);
    document.getElementById('allowances').addEventListener('input', calculatePayroll);
    document.getElementById('deductions').addEventListener('input', calculatePayroll);
</script>

@endsection
