@extends('layout.master')
@section('title', 'Fuel Report')
@section('header-title', 'Fuel Report')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        @can('payroll_create')
                            <a href="{{ route('fuel.create') }}" class="btn btn-primary">Add Fuel Report</a>
                        @endcan
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#runPayslipModalMonthly">
                            Generate Monthly Report
                        </a>
                        {{-- <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#runPayslipModalYearly">
                            Generate Yearly Payroll
                        </a> --}}

                    </div>
                    {{-- @can('payroll_trash_view')
                        <a href="{{ route('payrolls.trash') }}" class="btn btn-danger d-flex align-items-center gap-2 ms-auto"
                            title="Deleted Payrolls">
                            <i class="bi bi-trash-fill"></i>
                            <span>Trash</span>
                            <span class="badge bg-light text-dark">{{ $trashedCount ?? 0 }}</span>
                        </a>
                    @endcan --}}
                </div>
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>MBL Account #</th>
                                    <th>D.O.J</th>
                                    <th>Fuel Authorized (Ltrs)</th>
                                    <th>Total Reimbursement</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $key => $report)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $report->employee->name }}</td>
                                        <td>{{ $report->employee->designation->name ?? '-' }}</td>
                                        <td>{{ $report->employee->account_number ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($report->employee->joining_date)->format('d-M-Y') }}</td>
                                        <td>{{ $report->fuel_authorized }}</td>
                                        <td>{{ number_format($report->reimbursement, 0) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No fuel reports found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($reports->count() > 0)
                            <tfoot>
                                <tr>
                                    <th colspan="5">Total</th>
                                    <th>{{ $reports->sum('fuel_authorized') }}</th>
                                    <th>{{ number_format($reports->sum('reimbursement'), 0) }}</th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>

                </div>
            </div>
        </div>
    </div>



 <!-- Run Payslip Modal -->
 <div class="modal fade" id="runPayslipModalMonthly" tabindex="-1" aria-labelledby="runPayslipModalMonthlyLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('month.fuel') }}" method="GET" target="_blank">
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
{{-- <div class="modal fade" id="runPayslipModalYearly" tabindex="-1" aria-labelledby="runPayslipModalYearlyLabel" aria-hidden="true">
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
</div> --}}


@endsection
