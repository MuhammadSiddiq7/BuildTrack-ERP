@extends('layout.master')
@section('title', 'Create Contractor')
@section('header-title', 'Create Contractor')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('contractor.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Contractor Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" placeholder="Enter Contractor Name"
                                        value="{{ old('name') }}" class="form-control">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Contact Number</b><span class="text-danger">*</span></label>
                                    <input type="text" id="contact_number" name="contact_number" placeholder="Enter Contact Number"
                                        value="{{ old('contact_number') }}" class="form-control">
                                    @error('contact_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Contractor Code</b><span class="text-danger">*</span></label>
                                    <input type="text" id="code" name="code" placeholder="Enter Contractor Code"
                                        value="{{ old('code') }}" class="form-control">
                                    @error('code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="no_of_houses"><b>No Of Houses</b></label>
                                    <input type="text" id="no_of_houses" name="no_of_houses" placeholder="Enter No Of Houses"
                                        value="{{ old('no_of_houses') }}" class="form-control">
                                    @error('no_of_houses')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="description"><b>Description</b></label>
                                    <input type="text" id="description" name="description" placeholder="Enter Description"
                                        value="{{ old('description') }}" class="form-control">
                                    @error('description')
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
