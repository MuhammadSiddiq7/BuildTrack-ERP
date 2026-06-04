@extends('layout.master')
@section('title', 'Payslips')
@section('header-title', 'Payslips')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        @can('payroll_create')
                            <a href="{{ route('payrolls.create') }}" class="btn btn-primary">Add Payslip</a>
                        @endcan
                        {{-- @can('payroll_run') --}}
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#runPayslipModalMonthly">
                            Generate Monthly Payroll
                        </a>
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#runPayslipModalYearly">
                            Generate Yearly Payroll
                        </a>
                        {{-- @endcan --}}

                    </div>
                    @can('payroll_trash_view')
                        <a href="{{ route('payrolls.trash') }}" class="btn btn-danger d-flex align-items-center gap-2 ms-auto"
                            title="Deleted Payrolls">
                            <i class="bi bi-trash-fill"></i>
                            <span>Trash</span>
                            <span class="badge bg-light text-dark">{{ $trashedCount ?? 0 }}</span>
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S. No.</th>
                                <th>Employee Code</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Month</th>
                                <th>Net Pay</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $key => $payroll)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $payroll->employee->employee_id }}</td>
                                    <td>{{ $payroll->employee->name }}</td>
                                    <td>{{ $payroll->employee->designation->name }}</td>
                                    <td>{{ $payroll->pay_date }}</td>
                                    <td><strong>{{ number_format($payroll->net_pay,2) }}</strong></td>
                                    <td>
                                         <div class="dropdown">
                                                <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                                                    <i class="align-middle" data-feather="more-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    @can('payroll_show')
                                                        <a class="dropdown-item" href="{{ route('payrolls.show', $payroll->id) }}">
                                                            <i class="align-middle me-1" data-feather="eye"></i>
                                                            View
                                                        </a>
                                                        <a href="{{ route('payrolls.pdf', $payroll->id) }}" target="_blank"
                                                            class="dropdown-item">
                                                            <i class="fas fa-file-pdf text-danger"></i> PDF
                                                        </a>
                                                    @endcan
                                                </div>
                                            </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



 <!-- Run Payslip Modal -->
 <div class="modal fade" id="runPayslipModalMonthly" tabindex="-1" aria-labelledby="runPayslipModalMonthlyLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('payrolls.download-by-month') }}" method="GET" target="_blank">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="runPayslipModalMonthlyLabel">Download Monthly Payroll</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="month" class="form-label">Select Month:</label>
            <input type="month" id="month" name="month" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Download Monthly Excel</button>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="modal fade" id="runPayslipModalYearly" tabindex="-1" aria-labelledby="runPayslipModalYearlyLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('payrolls.download-by-year') }}" method="GET" target="_blank">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="runPayslipModalYearlyLabel">Download Yearly Payroll</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
                <label for="from_year" class="form-label">Select Start Year:</label>
                <input type="date" id="from_year" name="from_year" class="form-control" required>

                <label for="to_year" class="form-label">Select End Year:</label>
                <input type="date" id="to_year" name="to_year" class="form-control" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Download Yearly Excel</button>
        </div>
      </div>
    </form>
  </div>
</div>


@endsection
