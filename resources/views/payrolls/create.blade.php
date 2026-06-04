@extends('layout.master')
@section('title', 'Create Payslip')
@section('header-title', 'Create Payslip')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('payrolls.store') }}" method="POST">
                        @csrf
                        <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_id">Select Employee</label>
                            <select id="employee_id" name="employee_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}"
                                        data-basic="{{ $emp->salary->basic_salary }}"
                                        data-account="{{ $emp->account_number }}"
                                        data-deployment="{{ $emp->deployment_area }}"
                                        data-designation="{{ $emp->designation->name }}"
                                        data-project="{{ $emp->project->project_name ?? '-' }}">
                                        {{ $emp->name }} ({{ $emp->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                            <div class="col-md-6 mb-3">
                                <label>Basic Salary</label>
                                <input type="text" id="basic_salary" name="basic_salary" class="form-control" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Designation</label>
                                <input type="text" id="designation" class="form-control" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Deployment</label>
                                <input type="text" id="deployment" class="form-control" readonly>
                            </div>
                                <input type="hidden" id="project" class="form-control" readonly>
                            <div class="col-md-4 mb-3">
                                <label>Account Number</label>
                                <input type="text" id="account_number" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Project</label>
                                <input type="text" id="project_name" class="form-control" readonly>
                                <input type="hidden" name="project_id" id="project_id">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bank</label>
                                <input type="text" id="bank_name" class="form-control" readonly>
                                <input type="hidden" name="company_bank_id" id="bank_id">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Month</label>
                                <input type="date" name="pay_date" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Arrears</label>
                                <input type="number" step="0.01" name="arrears" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Recovery</label>
                                <input type="number" step="0.01" name="recovery" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Absenteeism</label>
                                <input type="number" step="0.01" name="absenteeism" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" name="securityDeposit" type="checkbox" value="1" id="securityDeposit">
                                    <label class="form-check-label" for="securityDeposit">Security Deposit</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Generate Payroll</button>
                    </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('employee_id').addEventListener('change', function () {
        let empId = this.value;
        if (empId) {
            fetch(`/get-employee-details/${empId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('project_name').value = data.project?.name ?? '';
                        document.getElementById('project_id').value = data.project?.id ?? '';
                        document.getElementById('bank_name').value = data.bank?.name ?? '';
                        document.getElementById('bank_id').value = data.bank?.id ?? '';
                    }
                });
        }
    });
</script>
<script>
    document.getElementById('employee_id').addEventListener('change', function () {
        let selected = this.options[this.selectedIndex];
        document.getElementById('basic_salary').value = selected.getAttribute('data-basic');
        document.getElementById('designation').value = selected.getAttribute('data-designation');
        document.getElementById('deployment').value = selected.getAttribute('data-deployment');
        document.getElementById('project').value = selected.getAttribute('data-project');
        document.getElementById('account_number').value = selected.getAttribute('data-account');
    });
</script>
@endsection
