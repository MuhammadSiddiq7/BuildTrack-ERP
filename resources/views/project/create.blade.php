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
                        <!-- Project Name -->
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="project_name"><b>Project Name</b><span class="text-danger">*</span></label>
                                <input type="text" id="project_name" name="project_name"
                                    placeholder="Enter Project Name" value="{{ old('project_name') }}"
                                    class="form-control" required>
                                @error('project_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Project Number -->
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="project_number"><b>Project Number</b><span class="text-danger">*</span></label>
                                <input type="text" id="project_number" name="project_number"
                                    placeholder="Enter Project Number" value="{{ old('project_number') }}"
                                    class="form-control" required>
                                @error('project_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Number of Houses -->
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="number_of_houses"><b>Number of Houses</b><span class="text-danger">*</span></label>
                                <input type="number" id="number_of_houses" name="number_of_houses"
                                    placeholder="Enter Number of Houses" value="{{ old('number_of_houses') }}"
                                    class="form-control" min="0" step="0.01">
                                @error('number_of_houses')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Project Location -->
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="project_location"><b>Project Location</b><span class="text-danger">*</span></label>
                                <input type="text" id="project_location" name="project_location"
                                    placeholder="Enter Project Location" value="{{ old('project_location') }}"
                                    class="form-control" required>
                                @error('project_location')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Bank -->
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <label for="company_bank_id"><b>Bank</b><span class="text-danger">*</span></label>
                                <select name="company_bank_id" id="company_bank_id" class="form-control" required>
                                    <option value="">Select Bank</option>
                                    @foreach ($companyBanks as $bank)
                                        <option value="{{ $bank->id }}" {{ old('company_bank_id') == $bank->id ? 'selected' : '' }}>
                                            {{ $bank->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_bank_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save Project</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
