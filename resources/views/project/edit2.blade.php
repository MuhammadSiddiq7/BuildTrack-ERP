{{--  
@extends('layout.master')
@section('title', 'Edit Project')
@section('header-title', 'Edit Project')
@section('content')
   

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Form Start -->
                    <form action="{{ route('project.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Project Name -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="project_name" class="form-label fw-semibold">Project Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="project_name" name="project_name"
                                        placeholder="Enter Project Name"
                                        value="{{ old('project_name', $project->project_name) }}" class="form-control"
                                        required>
                                    @error('project_name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Project Number -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="project_number" class="form-label fw-semibold">Project Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="project_number" name="project_number"
                                        placeholder="Enter Project Number"
                                        value="{{ old('project_number', $project->project_number) }}" class="form-control"
                                        required>
                                    @error('project_number')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Number of Houses -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="number_of_houses" class="form-label fw-semibold">Number of Houses <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="number_of_houses" name="number_of_houses"
                                        placeholder="Enter Number of Houses"
                                        value="{{ old('number_of_houses', $project->number_of_houses) }}"
                                        class="form-control" required min="1">
                                    @error('number_of_houses')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Project Location -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="project_location" class="form-label fw-semibold">Project Location <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="project_location" name="project_location"
                                        placeholder="Enter Project Location"
                                        value="{{ old('project_location', $project->project_location) }}"
                                        class="form-control" required>
                                    @error('project_location')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Bank -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_bank_id" class="form-label fw-semibold">Bank <span
                                            class="text-danger">*</span></label>
                                    <select name="company_bank_id" id="company_bank_id" class="form-select" required>
                                        <option value="">Select Bank</option>
                                        @foreach ($companyBanks as $bank)
                                            <option value="{{ $bank->id }}"
                                                {{ old('company_bank_id', $project->company_bank_id) == $bank->id ? 'selected' : '' }}>
                                                {{ $bank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_bank_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Categories Section -->
                        <hr class="my-4">
                        <h5 class="text-primary mb-3">Add Categories</h5>
                        @php
                            $houseProject = $houseProjects->first();
                        @endphp
                        <div id="">
                            @foreach ($project->houseProjects as $waqas)
                                {{-- @dd($waqas); --}}
                              
                              {{-- 
                                <input type="text" value="{{ $waqas->site_square_yard }}" name="waqas">
                                <div class="border rounded p-3 mb-4 bg-light">
                                    <div class="row g-3 project-row">
                                        <!-- House Type -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">House Type <span
                                                    class="text-danger">*</span></label>
                                            <select name="house_type_id[]" class="form-select">
                                                <option value="">Select House Type</option>
                                                <option value="ATH"
                                                    {{ $waqas->house_type_id == 'ATH' ? 'selected' : '' }}>ATH</option>
                                                <option value="BTH"
                                                    {{ $waqas->house_type_id == 'BTH' ? 'selected' : '' }}>BTH</option>
                                                <option value="CTH"
                                                    {{ $waqas->house_type_id == 'CTH' ? 'selected' : '' }}>CTH</option>
                                                <option value="DTH"
                                                    {{ $waqas->house_type_id == 'DTH' ? 'selected' : '' }}>DTH</option>
                                            </select>
                                        </div>

                                        <!-- Site Square Yard -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Site Square Yard <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="" name="site_square_yard[]"
                                                placeholder="Enter Site Square Yard" class="form-control"
                                                value="{{ $waqas->site_square_yard ?? '' }}" required>
                                        </div>

                                        <!-- Contractor -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Contractor <span
                                                    class="text-danger">*</span></label>
                                            <select name="contractor_id[0][]" class="form-select select2" multiple>
                                                @foreach ($contractors as $contractor)
                                                    <option value="{{ $contractor->id }}"
                                                        @if (isset($houseProject) && $houseProject->contractors->contains($contractor->id)) selected @endif>
                                                        {{ $contractor->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <!-- Houses -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Houses <span
                                                    class="text-danger">*</span></label>
                                            <select name="houses[0][]" class="form-select select2 house-field" multiple
                                                required>
                                                @foreach ($houseSeries as $series)
                                                    <option value="{{ $series->id }}">
                                                        {{ $series->total_of_houses }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <!-- Total Selected Houses -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Total Selected Houses</label>
                                            <input type="number" value="{{ $houseProject->total_houses }}"
                                                class="form-control total-houses bg-light" readonly value="0">
                                        </div>

                                        <!-- Warehouses -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Warehouses</label>
                                            <select name="warehouse_id[]" class="form-select">
                                                <option>Select Warehouse</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}"
                                                        {{ $houseProject->warehouse_id == '' . $warehouse->id . '' ? 'selected' : '' }}>
                                                        {{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="text" value="{{ $waqas->description }}" name="description">
                                        <!-- Description -->
                                        <div class="col-md-12">
                                            <label class="form-label">Description</label>
                                            <textarea name="description[]" placeholder="Enter description" class="form-control" {{ $waqas->description }}></textarea>
                                        </div>

                                        <!-- Remove Button -->
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <div id="project-wrapper">
                            <div class="project-box border rounded p-3 mb-4 bg-light">
                                <div class="row g-3 project-row">
                                    <!-- House Type -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">House Type <span
                                                class="text-danger">*</span></label>
                                        <select name="house_type_id[]" class="form-select">
                                            <option value="">Select House Type</option>
                                            <option value="ATH"
                                                {{ $houseProject->house_type_id == 'ATH' ? 'selected' : '' }}>ATH</option>
                                            <option value="BTH"
                                                {{ $houseProject->house_type_id == 'BTH' ? 'selected' : '' }}>BTH</option>
                                            <option value="CTH"
                                                {{ $houseProject->house_type_id == 'CTH' ? 'selected' : '' }}>CTH</option>
                                            <option value="DTH"
                                                {{ $houseProject->house_type_id == 'DTH' ? 'selected' : '' }}>DTH</option>
                                        </select>
                                    </div>

                                    <!-- Site Square Yard -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Site Square Yard <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="site_square_yard" name="site_square_yard[]"
                                            placeholder="Enter Site Square Yard" class="form-control"
                                            value="{{ old('site_square_yard'), $houseProject->site_square_yard }}"
                                            required>
                                    </div>

                                    <!-- Contractor -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Contractor <span
                                                class="text-danger">*</span></label>
                                        <select name="contractor_id[0][]" class="form-select select2" multiple>
                                            @foreach ($contractors as $contractor)
                                                <option value="{{ $contractor->id }}">{{ $contractor->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Houses -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Houses <span
                                                class="text-danger">*</span></label>
                                        <select name="houses[0][]" class="form-select select2 house-field" multiple
                                            required>
                                            @foreach ($houseProjects as $houseProject)
                                                @foreach ($houseProject->houseSeries as $series)
                                                    <option
                                                        value="{{ $series->total_of_houses }}"{{ $houseProject->houses == '' . $series->total_of_houses . '' ? 'selected' : '' }}>
                                                        House
                                                        {{ $series->total_of_houses }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Total Selected Houses -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Total Selected Houses</label>
                                        <input type="number" value="{{ $houseProject->total_houses }}"
                                            class="form-control total-houses bg-light" readonly value="0">
                                    </div>

                                    <!-- Warehouses -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Warehouses</label>
                                        <select name="warehouse_id[]" class="form-select">
                                            <option>Select Warehouse</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    {{ $houseProject->warehouse_id == '' . $warehouse->id . '' ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-12">
                                        <label class="form-label">Description</label>
                                        <textarea name="description[]" placeholder="Enter description" class="form-control"
                                            rows="2"{{ $waqas->description }}></textarea>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Add More Button -->
                        <div class="mb-3">
                            <button type="button" id="addMoreBtn" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-plus-circle"></i> Add More
                            </button>
                        </div>

                        <!-- Submit -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success px-4">Update Project</button>
                        </div>

                    </form>
                    <!-- Form End -->

                </div>
            </div>
        </div>
    </div>

    <style>
        .project-box {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background: #ffffff;
            transition: all 0.2s ease-in-out;
        }

        .project-box:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        .category-title {
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 5px;
        }

        .form-label {
            font-size: 0.9rem;
            color: #495057;
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('project-wrapper');
            const addMoreBtn = document.getElementById('addMoreBtn');
            let formIndex = 0;

            // 🔹 helper: update total houses count
            function updateHouseCount(selectElement) {
                const totalInput = selectElement.closest('.project-box')
                    .querySelector('.total-houses');
                if (totalInput) {
                    totalInput.value = $(selectElement).val()?.length || 0;
                }
            }

            // 🔹 helper: disable already selected houses
            function refreshHouseOptions() {
                let selected = [];

                // collect all selected houses
                document.querySelectorAll('.house-field').forEach(select => {
                    const values = $(select).val() || [];
                    selected = selected.concat(values);
                });

                // disable options already used
                document.querySelectorAll('.house-field').forEach(select => {
                    $(select).find('option').each(function() {
                        if (selected.includes(this.value)) {
                            if (!($(select).val() || []).includes(this.value)) {
                                $(this).prop('disabled', true);
                            }
                        } else {
                            $(this).prop('disabled', false);
                        }
                    });
                    $(select).trigger('change.select2');
                });
            }

            function setupRow(rowBox) {
                // reset all values in cloned row
                rowBox.querySelectorAll('input, textarea').forEach(field => field.value = '');
                rowBox.querySelectorAll('select').forEach(select => select.selectedIndex = -1);

                // remove old select2 instances if any
                rowBox.querySelectorAll('.select2').forEach(select => {
                    if ($(select).hasClass('select2-hidden-accessible')) {
                        $(select).select2('destroy');
                        $(select).next('.select2-container').remove();
                    }
                    $(select).select2({
                        placeholder: 'Select Option',
                        allowClear: true,
                        width: '100%'
                    });
                });

                // reset houses count
                const totalHouseInput = rowBox.querySelector('.total-houses');
                if (totalHouseInput) totalHouseInput.value = 0;

                // remove button
                const removeBtn = rowBox.querySelector('.remove-row');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        if (document.querySelectorAll('.project-box').length > 1) {
                            rowBox.remove();
                            refreshHouseOptions();
                        } else {
                            alert('At least one category box is required.');
                        }
                    });
                }

                rowBox.querySelectorAll('.house-field').forEach(select => {
                    $(select).on('change', function() {
                        updateHouseCount(this);
                        refreshHouseOptions();
                    });
                });
            }

            document.querySelectorAll('.project-box').forEach(box => setupRow(box));

            addMoreBtn.addEventListener('click', function() {
                formIndex++;
                const firstBox = wrapper.querySelector('.project-box');

                const newBox = firstBox.cloneNode(true);
                newBox.querySelectorAll('.select2-container').forEach(el => el.remove());

                newBox.querySelectorAll('[name]').forEach(field => {
                    field.name = field.name.replace(/\[\d+\]/, `[${formIndex}]`);
                });
xcd
                wrapper.appendChild(newBox);

                setupRow(newBox);
                refreshHouseOptions();
            });

            refreshHouseOptions();
        });
    </script> 


@endsection
 --}}