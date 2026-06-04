@extends('layout.master')
@section('title', 'Edit Warehouse')
@section('header-title', 'Edit Warehouse')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('warehouse.update', $warehouse->id) }}" method="POST" enctype="multipart/form-data">
                     @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name"><b>Warehouse Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ $warehouse->name }}"
                                        placeholder="Enter Warehouse Name" class="form-control">
                                </div>
                            </div>
                            {{-- <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="project"><b>Project</b> <span class="text-danger">*</span></label>
                                    <select name="project_ids[]" class="form-control select2" multiple>
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ in_array($project->id, $warehouse->projects->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $project->site_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('contractor_ids')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="contractor"><b>Contractors</b> <span class="text-danger">*</span></label>
                                    <select name="contractor_ids[]" class="form-control select2" multiple>
                                        <option value="">Select Contractor</option>
                                        @foreach ($contractors as $contractor)
                                            <option value="{{ $contractor->id }}"
                                                {{ in_array($contractor->id, $warehouse->contractors->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $contractor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('contractor_ids')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="status"><b>Status</b></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="" disabled>Select Status</option>
                                        <option value="active" {{ $warehouse->status == 'active' ? 'selected' : '' }}>Active </option>
                                        <option value="inactive" {{ $warehouse->status == 'inactive' ? 'selected' : '' }}> Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="address"><b>Address</b></label>
                                    <textarea id="address" name="address"
                                        placeholder="Enter address" class="form-control">
                                    {{ $warehouse->address }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="description"><b>Description</b></label>
                                    <textarea id="description" name="description"
                                        placeholder="Enter description" class="form-control">
                                    {{ $warehouse->description }}</textarea>
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
      <script>
    $(document).ready(function() {
        $('#contractor').select2({
            placeholder: "Select Contractor(s)"
        });
    });
</script>
@endsection
