@extends('layout.master')
@section('title', 'Edit Contractor')
@section('header-title', 'Edit Contractor')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('contractor.update', $contractor->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ $contractor->name}}"
                                        placeholder="Enter Name" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="contact_number"><b>Contact Number</b><span class="text-danger">*</span></label>
                                    <input type="text" id="contact_number" name="contact_number" value="{{ $contractor->contact_number}}"
                                        placeholder="Enter Contact Number" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="code"><b>Contractor Code</b><span class="text-danger">*</span></label>
                                    <input type="text" id="code" name="code" value="{{ $contractor->code}}"
                                        placeholder="Enter Code" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="no_of_houses"><b>No Of Houses</b></label>
                                    <input type="text" id="no_of_houses" name="no_of_houses" value="{{ $contractor->no_of_houses}}"
                                        placeholder="Enter No Of Houses" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="description"><b>Description</b></label>
                                    <input type="text" id="description" name="description" value="{{ $contractor->description}}"
                                        placeholder="Enter Description" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="status"><b>Status</b></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="" disabled>Select Status</option>
                                        <option value="active" {{ $contractor->status == 'active' ? 'selected' : '' }}>Active </option>
                                        <option value="inactive" {{ $contractor->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
