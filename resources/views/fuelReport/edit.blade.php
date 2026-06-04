@extends('layout.master')
@section('title', 'Edit Payroll')
@section('header-title', 'Edit Payroll')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('payrolls.update', $payroll->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                         <div class="row">
                            <!-- EMPLOYEE -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="employee_id"><b>Employee</b><span class="text-danger">*</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control" required>
                                        <option value="" disabled>Select Employee</option>
                                        @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ $payroll->employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- BANKS -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="employee_bank_id" class="form-label">Employee Bank Account</label>
                                    <select name="employee_bank_id" id="employee_bank_id" class="form-control">
                                        <option value="">-- Select --</option>
                                        @foreach($employeeBankAccounts as $account)
                                        <option value="{{ $account->id }}" {{ $payroll->employee_bank_id == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_title }} - {{ $account->account_number }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="company_bank_id" class="form-label">Company Bank Account</label>
                                    <select name="company_bank_id" id="company_bank_id" class="form-control" required>
                                        <option value="">-- Select --</option>
                                        @foreach($companyBanks as $bank)
                                        <option value="{{ $bank->id }}" {{ $payroll->company_bank_id == $bank->id ? 'selected' : '' }}>
                                            {{ $bank->name }} - {{ $bank->account_number }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- PAY DATES -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="month" class="form-label">Month</label>
                                    <input type="month" name="month" id="month" class="form-control" value="{{ $payroll->month }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Payment Date</label>
                                    <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ $payroll->payment_date }}">
                                </div>
                            </div>

                            <!-- Salary Fields -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="number_of_working_days" class="form-label">Working Days</label>
                                    <input type="number" step="0.01" name="number_of_working_days" id="number_of_working_days" class="form-control" value="{{ $payroll->number_of_working_days }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="per_day_salary" class="form-label">Per Day Salary</label>
                                    <input type="number" step="0.01" name="per_day_salary" id="per_day_salary" class="form-control" value="{{ $payroll->per_day_salary }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="gross_salary" class="form-label">Gross Salary</label>
                                    <input type="number" step="0.01" name="gross_salary" id="gross_salary" class="form-control" value="{{ $payroll->gross_salary }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="deductions" class="form-label">Deductions</label>
                                    <input type="number" step="0.01" name="deductions" id="deductions" class="form-control" value="{{ $payroll->deductions }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="net_salary_display" class="form-label">Net Salary</label>
                                    <input type="number" step="0.01" id="net_salary_display" class="form-control" value="{{ $payroll->net_salary }}" readonly>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="col-md-12 mt-3">
                                <label for="remarks"><b>Remarks</b></label>
                                <textarea name="remarks" class="form-control" rows="3">{{ $payroll->remarks }}</textarea>
                            </div>

                            <!-- Hidden Net Salary Field -->
                            <input type="hidden" name="net_salary" id="net_salary" value="{{ $payroll->net_salary }}">

                            <!-- Submit -->
                            <div class="col-md-12 text-end mt-4">
                                <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">Back</a>
                                <button type="submit" class="btn btn-primary">Update Payroll</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

 <script>
    document.addEventListener('DOMContentLoaded', function () {
        const workingDaysInput = document.getElementById('number_of_working_days');
        const perDaySalaryInput = document.getElementById('per_day_salary');
        const grossSalaryInput = document.getElementById('gross_salary');
        const deductionsInput = document.getElementById('deductions');
        const netSalaryDisplay = document.getElementById('net_salary_display');
        const netSalaryHidden = document.getElementById('net_salary');

        function updateGrossAndNet() {
            const workingDays = parseFloat(workingDaysInput.value) || 0;
            const perDaySalary = parseFloat(perDaySalaryInput.value) || 0;
            const deductions = parseFloat(deductionsInput.value) || 0;

            const gross = workingDays * perDaySalary;
            const net = gross - deductions;

            grossSalaryInput.value = gross.toFixed(2);
            netSalaryDisplay.value = net.toFixed(2);
            netSalaryHidden.value = net.toFixed(2);
        }

        // Initial calculation on page load
        updateGrossAndNet();

        // Update when deductions change
        deductionsInput.addEventListener('input', updateGrossAndNet);
    });
</script>


@endsection
