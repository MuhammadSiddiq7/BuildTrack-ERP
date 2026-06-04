@extends('layout.master')
@section('title', 'Activity Edit')
@section('header-title', 'Activity Edit')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('activity.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="activity_code">Activity Code</label>
                                    <input type="text" name="activity_code" class="form-control" value="{{ old('activity_code', $activity->activity_code) }}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                <label for="name">Activity Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $activity->name) }}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="parent_id">Parent Activity</label>
                                        <select name="parent_id" class="form-control">
                                            <option value="">Select Parent</option>
                                            @foreach($parents as $parent)
                                                <option value="{{ $parent->id }}"
                                                    {{ old('parent_id', $activity->parent_id) == $parent->id ? 'selected' : '' }}>
                                                    {{ $parent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="status"><b>Status</b></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="" disabled>Select Status</option>
                                        <option value="active" {{ $activity->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $activity->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="yardstick">Yardstick</label>
                                        <input type="text" name="yardstick" class="form-control" value="{{ old('yardstick', $activity->yardstick) }}">
                                    </div>
                                </div>


                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
