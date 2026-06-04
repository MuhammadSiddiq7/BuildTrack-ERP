@extends('layout.master')
@section('title', 'Create Project')
@section('header-title', 'Create Project')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('project.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="site_name"><b>Site Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="site_name" name="site_name" placeholder="Enter Site Name"
                                        value="{{ old('site_name') }}" class="form-control">
                                    @error('site_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="project_number"><b>Project Number</b><span class="text-danger">*</span></label>
                                    <input type="text" id="project_number" name="project_number" placeholder="Enter Project Number"
                                        value="{{ old('project_number') }}" class="form-control">
                                    @error('project_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="site_square_yard"><b>Site Squre Yard</b><span class="text-danger">*</span></label>
                                    <input type="text" id="site_square_yard" name="site_square_yard" placeholder="Enter Site Square Yard"
                                        value="{{ old('site_name') }}" class="form-control">
                                    @error('site_square_yard')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="warehouses"><b>Warehouses</b><span class="text-danger">*</span></label>
                                    <select name="warehouse_id" class="form-control" >
                                        <option value="" >Select Warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id}}">{{ $warehouse->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="contractor"><b>Contractor</b><span class="text-danger">*</span></label>
                                    <select name="contractor_id[]" class="form-control select2" multiple>
                                        <option value="" >Select Contractor</option>
                                        @foreach ($contractors as $contractor)
                                        <option value="{{ $contractor->id}}">{{ $contractor->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('site_name')
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
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="address"><b>Address</b></label>
                                    <textarea id="address" name="address" placeholder="Enter address" class="form-control"></textarea>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="description"><b>Description</b></label>
                                    <textarea id="description" name="description"
                                    placeholder="Enter description" class="form-control"></textarea>
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
     <script>
    $(document).ready(function() {
        $('#contractor').select2({
            placeholder: "Select Contractor(s)"
        });
    });
</script>
@endsection
