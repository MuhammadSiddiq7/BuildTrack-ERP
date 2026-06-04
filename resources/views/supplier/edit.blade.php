@extends('layout.master')
@section('title', 'Edit Supplier')
@section('header-title', 'Edit Supplier')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('supplier.update', $supplier->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <ddiv class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Vendors / Supplier Name</b><span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ $supplier->name }}"
                                        placeholder="Enter Vendors / Supplier Name" value="{{ old('name') }}"
                                        class="form-control">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Product Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="brand_name"
                                        value="{{ $supplier->brand_name }}" placeholder="Enter brand Name"
                                        value="{{ old('brand_name') }}" class="form-control">
                                    @error('brand_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Vendors Representative Name</b><span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="owner_name" name="owner_name"
                                        value="{{ $supplier->owner_name }}" placeholder="Enter Owner Name"
                                        value="{{ old('owner_name') }}" class="form-control">
                                    @error('owner_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>CNIC</b><span class="text-danger">*</span></label>
                                    <input type="number" id="cnic" name="cnic" value="{{ $supplier->cnic }}"
                                        placeholder="Enter Owner CNIC" value="{{ old('cnic') }}" class="form-control">
                                    @error('cnic')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Vendors / Supplier NTN</b><span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="ntn" name="ntn" value="{{ $supplier->ntn }}"
                                        placeholder="Enter Vendors / Supplier NTN" value="{{ old('name') }}"
                                        class="form-control">
                                    @error('ntn')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="contact"><b>Contact No</b></label>
                                    <input type="text" min="0" id="contact" value="{{ $supplier->contact }}"
                                        name="contact" placeholder="Enter Contact No" value="{{ old('contact') }}"
                                        class="form-control">
                                    @error('contact')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="mou_no"><b>MOU No</b></label>
                                    <input type="text" step="0.01" id="mou_no" name="mou_no" value="{{ $supplier->mou_no }}"
                                        placeholder="Enter MOU No" value="{{ old('mou_no') }}" class="form-control">
                                    @error('mou_no')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="mou_date"><b>MOU Date</b></label>
                                    <input type="date" id="mou_date" value="{{ $supplier->mou_date }}" name="mou_date"
                                        value="{{ old('mou_date') }}" class="form-control">
                                    @error('mou_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="addendum_no"><b>Addendum Number</b></label>
                                    <input type="text" step="0.01" id="addendum_no" name="addendum_no"
                                        placeholder="Enter Addendum No"
                                        value="{{ $supplier->addendum_no }}"
                                        class="form-control">
                                    @error('addendum_no')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="addendum_date"><b>Addendum Date</b></label>
                                    <input type="date" id="addendum_date" name="addendum_date"
                                        value="{{ $supplier->addendum_date  }}"
                                        class="form-control">
                                    @error('addendum_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>



                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="status"><b>Status</b></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="" disabled {{ $supplier->status == null ? 'selected' : '' }}>
                                            Select Status</option>
                                        <option value="active" {{ $supplier->status == 'active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="inactive" {{ $supplier->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="address"><b>Address</b></label>
                                    <textarea id="address" name="address" placeholder="Enter address" class="form-control">{{ old('address', $supplier->address) }}</textarea>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="description"><b>Description</b></label>
                                    <textarea id="description" name="description" placeholder="Enter description" class="form-control">{{ old('description', $supplier->description ?? '') }}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection
