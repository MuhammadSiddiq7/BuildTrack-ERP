@extends('layout.master')
@section('title', 'Create Supplier')
@section('header-title', 'Create Supplier')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Vendors / Supplier Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" placeholder="Enter Vendors / Supplier Name"
                                        value="{{ old('name') }}" class="form-control">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Product Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="brand_name" placeholder="Enter brand Name"
                                        value="{{ old('brand_name') }}" class="form-control">
                                    @error('brand_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Vendors Representative Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="owner_name" name="owner_name" placeholder="Enter Owner Name"
                                        value="{{ old('owner_name') }}" class="form-control">
                                    @error('owner_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>CNIC</b><span class="text-danger">*</span></label>
                                    <input type="number" id="cnic" name="cnic" placeholder="Enter Owner CNIC"
                                        value="{{ old('cnic') }}" class="form-control">
                                    @error('cnic')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Vendors / Supplier NTN</b><span class="text-danger">*</span></label>
                                    <input type="text" id="ntn" name="ntn" placeholder="Enter Vendors / Supplier NTN"
                                        value="{{ old('name') }}" class="form-control">
                                    @error('ntn')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="contact"><b>Contact No</b></label>
                                    <input type="number" min="0" id="contact" name="contact" placeholder="Enter Contact No"
                                    value="{{ old('contact') }}" class="form-control">
                                    @error('contact')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="mou_no"><b>MOU No</b></label>
                                    <input type="text" step="0.01" id="mou_no" name="mou_no" placeholder="Enter MOU No"
                                    value="{{ old('mou_no') }}" class="form-control">
                                    @error('mou_no')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="mou_date"><b>MOU Date</b></label>
                                    <input type="date" id="mou_date" name="mou_date"
                                    value="{{ old('mou_date') }}" class="form-control">
                                    @error('mou_date')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="addendum_no"><b>Addendum Number</b></label>
                                        <input type="text" step="0.01" id="addendum_no" name="addendum_no" placeholder="Enter Addendum No"
                                            value="{{ old('addendum_no') }}" class="form-control">
                                        @error('addendum_no')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="addendum_date"><b>Addendum Date</b></label>
                                        <input type="date" id="addendum_date" name="addendum_date"
                                            value="{{ old('addendum_date') }}" class="form-control">
                                        @error('addendum_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="status"><b>Status</b></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="address"><b>Address</b></label>
                                    <textarea type="text" id="address" name="address" placeholder="Enter address"
                                        value="{{ old('address') }}" class="form-control"></textarea>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="description"><b>Description</b></label>
                                    <textarea type="text" id="description" name="description" placeholder="Enter description"
                                        value="{{ old('description') }}" class="form-control"></textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary"><b>Save</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
