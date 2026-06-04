@extends('layout.master')
@section('title', 'Edit House Type')
@section('header-title', 'Edit House Type')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('houseType.update', $houseType->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="mb-3">
                                <label for="name"><b>House type</b><span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" value="{{ $houseType->name}}" placeholder="Enter Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="mb-3">
                                <label for="description"><b>Description</b></label>
                                <textarea type="text" id="description" name="description" placeholder="Enter Description" class="form-control">{{ $houseType->description}}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="mb-3">
                                <label for="status"><b>Status</b></label>
                                <select name="status" id="status" class="form-control">
                                    <option value="" disabled>Select Status</option>
                                    <option value="active" {{ $houseType->status == 'active' ? 'selected' : '' }}>Active </option>
                                    <option value="inactive" {{ $houseType->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
