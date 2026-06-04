@extends('layout.master')
@section('title', 'Create Payslip')
@section('header-title', 'Create Payslip')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                 <form action="{{ route('fuel.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_id">Select Employee</label>
                            <select id="employee_id" name="employee_id" class="form-control" required>
                                <option value="">-- Select Employee --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}"> {{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="fuel_authorized">Fuel Authorized (Ltrs)</label>
                            <input type="number" step="0.01" class="form-control" name="fuel_authorized" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="petrol_rate">Petrol Rate (per Ltr)</label>
                            <input type="number" step="0.01" class="form-control" name="petrol_rate" value="272.15" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="date">Month</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Report</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
