@extends('layout.master')
@section('title', 'Edit Holiday')
@section('header-title', 'Edit Holiday')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('holiday.update', $holiday->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="title"><b>title</b><span class="text-danger">*</span></label>
                                    <input type="text" id="title" name="title" value="{{ $holiday->title }}"
                                        placeholder="Enter title" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="date"><b>Date</b></label>
                                    <input type="date" id="date" name="date" value="{{ $holiday->date }}"
                                        placeholder="Enter Date" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="type"><b>Type</b></label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="" disabled {{ old('type', $holiday->type) == '' ? 'selected' : '' }}>Select type</option>
                                        <option value="weekend" {{ old('type', $holiday->type) == 'weekend' ? 'selected' : '' }}>Weekend</option>
                                        <option value="holiday" {{ old('type', $holiday->type) == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                    </select>
                                    @error('type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="status"><b>Status</b></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="" disabled>Select Status</option>
                                        <option value="active" {{ $holiday->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $holiday->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label><b>Description</b></label>
                                    <textarea name="description" class="form-control" rows="3">
                                        {{ $holiday->description }}
                                    </textarea>
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
            $('#roles').select2({
                placeholder: "Select Role(s)"
            });
        });
    </script>
@endsection
