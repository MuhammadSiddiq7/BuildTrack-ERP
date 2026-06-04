@extends('layout.master')
@section('title', 'Edit Bank')
@section('header-title', 'Edit Bank')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('employee.bank.update', $bank->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Bank Name</b><span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required
                                        value="{{ old('name', $bank->name) }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="branch"><b>Branch</b></label>
                                    <input type="text" name="branch" id="branch" class="form-control"
                                        value="{{ old('branch', $bank->branch) }}">
                                    @error('branch')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="account_number"><b>Account Number</b></label>
                                    <input type="text" name="account_number" id="account_number" class="form-control"
                                        value="{{ old('account_number', $bank->account_number) }}">
                                    @error('account_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="iban"><b>IBAN</b></label>
                                    <input type="text" name="iban" id="iban" class="form-control"
                                        value="{{ old('iban', $bank->iban) }}">
                                    @error('iban')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="edenred_exchange"><b>Edenred Exchange</b></label>
                                    <input type="text" id="edenred_exchange" name="edenred_exchange" class="form-control"
                                        placeholder="Enter Edenred Exchange"
                                        value="{{ old('edenred_exchange', $bank->edenred_exchange ?? '') }}">
                                    @error('edenred_exchange')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status"><b>Status</b></label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="" disabled
                                            {{ old('status', $bank->status ?? '') == '' ? 'selected' : '' }}>Select Status
                                        </option>
                                        <option value="active"
                                            {{ old('status', $bank->status ?? '') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $bank->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary"><b>Update Bank</b></button>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
