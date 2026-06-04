@extends('layout.master')
@section('title', 'Create Holiday')
@section('header-title', 'Create Holiday')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('holiday.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="name"><b>Title</b><span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" placeholder="Enter title" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="type"><b>Type</b></label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="" disabled selected>Select Type</option>
                                        <option value="weekend">Weekend</option>
                                        <option value="holiday">Holiday</option>
                                    </select>
                                    </select>
                                    @error('type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3 d-none" id="weekend-date">
                                     <label class="form-label"><b>Select Weekend Date</b></label>
                                     <input type="date" name="date" class="form-control">
                                      {{-- <small class="text-muted">Pick any Friday/Sunday etc. It will be applied yearly.</small> --}}
                                    @error('date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div id="holiday-dates" class="d-none">
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Start Date</b></label>
                                            <input type="date" name="start_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label"><b>End Date</b></label>
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
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
                            <div class="col-12 col-sm-12">
                                <div class="mb-3">
                                    <label><b>Description</b></label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary"><b>Save Holiday</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.getElementById('type').addEventListener('change', function () {
        let type = this.value;
        let holidayFields = document.getElementById('holiday-dates');
        let weekendField = document.getElementById('weekend-date');

        holidayFields.classList.add('d-none');
        weekendField.classList.add('d-none');

        if (type === 'holiday') {
            holidayFields.classList.remove('d-none');
        } else if (type === 'weekend') {
            weekendField.classList.remove('d-none');
        }
    });
</script>
@endsection
