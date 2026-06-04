@extends('layout.master')
@section('title', 'Create Planing')
@section('header-title', 'Create Planing')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('plan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="project_id"><b>Projects</b></label>
                                    <select name="project_id" class="form-control">
                                        <option value="" selected disabled>Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="contractor_id"><b>Contractors</b></label>
                                    <select name="contractor_id" class="form-control">
                                        <option value="" selected disabled>Select Contractor</option>
                                    </select>
                                    @error('contractor_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="house_type_id"><b>All Types</b></label>
                                    <select name="house_type_id" class="form-control">
                                        <option value="" selected disabled>Select House Type</option>
                                    </select>
                                    @error('house_type_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="house_project_houses_id"><b>Houses</b></label>
                                    <select name="house_project_houses_id[]" class="form-control select2" multiple></select>
                                    @error('house_project_houses_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="amount"><b>Amount</b></label>
                                    <input type="text" name="amount" class="form-control" placeholder="Enter Amount">
                                    @error('amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="plan_ceo_id"><b>CEO</b></label>
                                    <select name="plan_ceo_id" class="form-control">
                                        <option value="" selected disabled>Select CEO</option>
                                        @foreach ($ceos as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('plan_ceo_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="project_manager_id"><b>Project Managers</b></label>
                                    <select name="plan_manager_id" class="form-control">
                                        <option value="" selected disabled>Select Project Manager</option>
                                        @foreach ($projectManagers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_manager_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="client_id"><b>Clients</b></label>
                                    <select name="plan_client_id" class="form-control">
                                        <option value="" selected disabled>Select Client</option>
                                        @foreach ($clients as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="plan_consultant_id"><b>Consultant</b></label>
                                    <select name="plan_consultant_id" class="form-control">
                                        <option value="" selected disabled>Select Consultant</option>
                                        @foreach ($consultants as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('plan_consultant_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="plan_planning_engineer_id"><b>Planning Engineer</b></label>
                                    <select name="plan_planning_engineer_id" class="form-control">
                                        <option value="" selected disabled>Select Planning Engineer</option>
                                        @foreach ($planningEngineers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('plan_planning_engineer_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="plan_quality_supervisor_id"><b>Quality Supervisor</b></label>
                                    <select name="plan_quality_supervisor_id" class="form-control">
                                        <option value="" selected disabled>Select Quality Supervisor</option>
                                        @foreach ($qualitySupervisors as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('plan_quality_supervisor_id')
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[name="project_id"]').on('change', function() {
                var projectId = $(this).val();
                if (projectId) {
                    $.get("{{ url('/plan/get-contractors-by-project') }}/" + projectId, function(data) {
                        var contractorSelect = $('select[name="contractor_id"]');
                        contractorSelect.empty().append('<option value="" disabled selected>Select Contractor</option>');
                        $.each(data, function(key, value) {
                            contractorSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        contractorSelect.trigger('change');
                    });
                }
            });

            $('select[name="contractor_id"]').on('change', function() {
                var contractorId = $(this).val();
                var projectId = $('select[name="project_id"]').val();
                if (projectId && contractorId) {
                    $.get("{{ url('/plan/get-house-types-by-contractor') }}/" + projectId + "/" + contractorId, function(data) {
                        var houseTypeSelect = $('select[name="house_type_id"]');
                        houseTypeSelect.empty().append('<option value="" disabled selected>Select House Type</option>');
                        $.each(data, function(key, value) {
                            houseTypeSelect.append('<option value="' + value.id + '">' + value.house_type_id + '</option>');
                        });
                        houseTypeSelect.trigger('change');
                    });
                }
            });

            $('select[name="house_type_id"]').on('change', function() {
                var houseProjectId = $(this).val();
                if (houseProjectId) {
                    $.get("{{ url('/plan/get-houses') }}/" + houseProjectId, function(data) {
                        var houseSelect = $('select[name="house_project_houses_id[]"]');
                        houseSelect.empty();
                        $.each(data, function(key, value) {
                            houseSelect.append('<option value="' + value.id + '">House No: ' + value.house_number + '</option>');
                        });
                        houseSelect.trigger('change');
                    });
                }
            });

            $(".select2").select2({
                placeholder: "Select value",
                allowClear: true,
                width: "100%"
            });
        });

    </script>

    <script>
        // $(document).ready(function() {
        //     $('select[name="project_id"]').on('change', function() {
        //         var projectId = $(this).val();
        //         if (projectId) {
        //             $.get("{{ url('/plan/get-house-types') }}/" + projectId, function(data) {
        //                 var houseTypeSelect = $('select[name="house_type_id"]');
        //                 houseTypeSelect.empty().append(
        //                     '<option value="" disabled selected>Select House Type</option>');
        //                 $.each(data, function(key, value) {
        //                     houseTypeSelect.append('<option value="' + value.id + '">' +
        //                         value.house_type_id + '</option>');
        //                 });
        //             });
        //         }
        //     });
        //     $('select[name="house_type_id"]').on('change', function() {
        //         var houseProjectId = $(this).val();
        //         if (houseProjectId) {

        //             $.get("{{ url('/plan/get-houses') }}/" + houseProjectId, function(data) {
        //                 var houseSelect = $('select[name="house_project_houses_id"]');
        //                 houseSelect.empty().append(
        //                     '<option value="" disabled selected>Select House</option>');
        //                     console.log(data),
        //                 $.each(data, function(key, value) {
        //                     houseSelect.append('<option value="' + value.id +
        //                         '">House No: ' + value.house_number + '</option>');
        //                 });
        //             });
        //             $.get("{{ url('/plan/get-contractors') }}/" + houseProjectId, function(data) {
        //                 var contractorSelect = $('select[name="contractor_id"]');
        //                 contractorSelect.empty().append(
        //                     '<option value="" disabled selected>Select Contractor</option>');
        //                 $.each(data, function(key, value) {
        //                     contractorSelect.append('<option value="' + value.id + '">' +
        //                         value.name + '</option>');
        //                 });
        //             });
        //         }
        //     });

        // });
    </script>

@endsection
