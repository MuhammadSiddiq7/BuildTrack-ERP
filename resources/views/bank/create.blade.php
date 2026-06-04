@extends('layout.master')
@section('title', 'Create Bank')
@section('header-title', 'Create Bank')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('company.bank.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="name"><b>Bank Name<span class="text-danger"> *</span></b></label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter Bank Name"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="branch"><b>Branch Name</b></label>
                                <input type="text" id="branch" name="branch" class="form-control" placeholder="Enter Branch Name"
                                    value="{{ old('branch') }}">
                                @error('branch')
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
                        <div class="col-md-6">
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
                        </div>
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
