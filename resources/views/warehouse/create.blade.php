@extends('layout.master')
@section('title', 'Create Warehouse')
@section('header-title', 'Create Warehouse')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('warehouse.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Warehouse Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" placeholder="Enter Warehouse Name"
                                        value="{{ old('name') }}" class="form-control">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="project"><b>Project</b><span class="text-danger">*</span></label>
                                    <select name="project_id[]" class="form-control select2" multiple>
                                        <option value="" >Select Project</option>
                                        @foreach ($projects as $project)
                                        <option value="{{ $project->id}}">{{ $project->site_name}}</option>
                                        @endforeach
                                    </select>
                                    @error('project')
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
                                    @error('contractor')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> --}}
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
