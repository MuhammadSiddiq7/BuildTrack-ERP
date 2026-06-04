@extends('layout.master')
@section('title', 'Deleted Payrolls')
@section('header-title', 'Deleted Payrolls')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                            <thead class="{{ session('theme') === 'dark' ? 'table-dark' : 'table-light' }}">
                                <tr>
                                    <th>S.No</th>
                                    <th>Employee</th>
                                    <th>Bank</th>
                                    <th>Net Salary</th>
                                    <th>Pay Date</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payrolls as $index => $payroll)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->name }}</td>
                                        <td>{{ $payroll->bank->name }}</td>
                                        <td><span class="badge bg-success">Rs.
                                                {{ number_format($payroll->net_salary, 2) }}</span></td>
                                        <td>{{ \Carbon\Carbon::parse($payroll->pay_date)->format('d M Y') }}</td>
                                        <td>{{ $payroll->deleted_at->format('d M Y h:i A') }}</td>
                                        <td>
                                            <a href="{{ route('payrolls.restore', $payroll->id) }}"
                                                class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">Back to Payrolls</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
