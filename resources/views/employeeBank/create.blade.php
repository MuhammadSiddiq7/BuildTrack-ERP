@extends('layout.master')
@section('title', 'Create Bank')
@section('header-title', 'Create Bank')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('employee.bank.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="name"><b>Employee<span class="text-danger"> *</span></b></label>
                               <select name="employee_id" id="" class="form-control">
                                   <option value="">Select Employee</option>
                                   @foreach ($employees as $employee)
                                       <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                   @endforeach
                               </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="bank_name"><b>Bank Name<span class="text-danger"> *</span></b></label>
                                <input type="text" id="bank_name" name="bank_name" class="form-control" placeholder="Enter Bank name"
                                    value="{{ old('bank_name') }}">
                                @error('bank_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="account_title"><b>Account Title</b></label>
                                <input type="text" id="account_title" name="account_title" class="form-control" placeholder="Enter Account Title"
                                    value="{{ old('account_title') }}">
                                @error('account_title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="account_number"><b>Account Number<span class="text-danger"> *</span></b></label>
                                <input type="text" id="account_number" name="account_number" class="form-control"
                                    placeholder="Enter Account Number" value="{{ old('account_number') }}">
                                @error('account_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="iban"><b>IBAN</b></label>
                                <input type="text" id="iban" name="iban" class="form-control" placeholder="Enter IBAN"
                                    value="{{ old('iban') }}">
                                @error('iban')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="edenred_exchange"><b>Edenred Exchange</b></label>
                                <input type="text" id="edenred_exchange" name="edenred_exchange" class="form-control" placeholder="Enter Edenred Exchange"
                                    value="{{ old('iban') }}">
                                @error('edenred_exchange')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status"><b>Status</b></label>
                                <select name="status" id="status" class="form-control select2">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="inactive"
                                        {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><b>Create Bank</b></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
