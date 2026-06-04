@extends('layout.master')
@section('title', 'Edit Brand')
@section('header-title', 'Edit Brand')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ $brand->name}}"
                                        placeholder="Enter Name" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="owner_name"><b>Owner Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="owner_name" name="owner_name" value="{{ $brand->owner_name}}"
                                        placeholder="Enter Contact Number" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="cnic"><b>Contact Number</b><span class="text-danger">*</span></label>
                                    <input type="text" id="cnic" name="cnic" value="{{ $brand->cnic}}"
                                        placeholder="Enter Contact Number" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="contact_number"><b>Contact Number</b><span class="text-danger">*</span></label>
                                    <input type="text" id="contact_number" name="contact_number" value="{{ $brand->contact_number}}"
                                        placeholder="Enter Contact Number" class="form-control">
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="description"><b>Description</b></label>
                                    <input type="text" id="description" name="description" value="{{ $brand->description}}"
                                        placeholder="Enter Description" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="status"><b>Status</b></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="" disabled>Select Status</option>
                                        <option value="active" {{ $brand->status == 'active' ? 'selected' : '' }}>Active </option>
                                        <option value="inactive" {{ $brand->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
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
    </div>
@endsection
