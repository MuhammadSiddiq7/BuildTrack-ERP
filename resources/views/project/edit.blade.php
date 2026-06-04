@extends('layout.master')
@section('title', 'Edit Project')
@section('header-title', 'Edit Project')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <!-- Form Start -->
                    <form action="{{ route('project.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Project Name -->
                            <div class="col-md-6">
                                <label for="project_name" class="form-label fw-semibold">Project Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="project_name" name="project_name" class="form-control"
                                    value="{{ old('project_name', $project->project_name) }}"
                                    placeholder="Enter Project Name" required>
                                @error('project_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Project Number -->
                            <div class="col-md-6">
                                <label for="project_number" class="form-label fw-semibold">Project Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="project_number" name="project_number" class="form-control"
                                    value="{{ old('project_number', $project->project_number) }}"
                                    placeholder="Enter Project Number" required>
                                @error('project_number')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Number of Houses -->
                            <div class="col-md-6">
                                <label for="number_of_houses" class="form-label fw-semibold">Number of Houses <span
                                        class="text-danger">*</span></label>
                                <input type="number" id="number_of_houses" name="number_of_houses" class="form-control"
                                    value="{{ old('number_of_houses', $project->number_of_houses) }}"
                                    placeholder="Enter Number of Houses" min="0" step="0.01" required>
                                @error('number_of_houses')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Project Location -->
                            <div class="col-md-6">
                                <label for="project_location" class="form-label fw-semibold">Project Location <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="project_location" name="project_location" class="form-control"
                                    value="{{ old('project_location', $project->project_location) }}"
                                    placeholder="Enter Project Location" required>
                                @error('project_location')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bank -->
                            <div class="col-md-6">
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


                        <h5 class="text-primary mb-3">Add Categories</h5>
                        <div id="project-wrapper">
                            {{-- @foreach ($project->houseProjects as $index => $houseProject) --}}
                            @foreach ($houseProjects as $index => $houseProject)

                                @if (
                                    $houseProject->house_type_id ||
                                        $houseProject->site_square_yard ||
                                        $houseProject->warehouse_id ||
                                        $houseProject->description)
                                    <div class="project-box border rounded p-3 mb-4 bg-light">
                                        <div class="row g-3 project-row">

                                            <!-- Hidden ID -->
                                            <input type="hidden" name="rows[{{ $index }}][house_project_id]"
                                                value="{{ $houseProject->id }}">

                                            <!-- House Type -->
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">House Type <span
                                                        class="text-danger">*</span></label>
                                                <select name="rows[{{ $index }}][house_type_id]" class="form-select"
                                                    required>
                                                    <option value="">Select House Type</option>
                                                    <option value="ATH"
                                                        {{ $houseProject->house_type_id == 'ATH' ? 'selected' : '' }}>ATH
                                                    </option>
                                                    <option value="BTH"
                                                        {{ $houseProject->house_type_id == 'BTH' ? 'selected' : '' }}>BTH
                                                    </option>
                                                    <option value="CTH"
                                                        {{ $houseProject->house_type_id == 'CTH' ? 'selected' : '' }}>CTH
                                                    </option>
                                                    <option value="DTH"
                                                        {{ $houseProject->house_type_id == 'DTH' ? 'selected' : '' }}>DTH
                                                    </option>
                                                </select>
                                            </div>

                                            <!-- Site Square Yard -->
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Site Square Yard <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="rows[{{ $index }}][site_square_yard]"
                                                    class="form-control" value="{{ $houseProject->site_square_yard }}"
                                                    placeholder="Enter Site Square Yard" required>
                                            </div>

                                            <!-- Contractor -->
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Contractor <span
                                                        class="text-danger">*</span></label>
                                                <select name="rows[{{ $index }}][contractor_id][]"
                                                    class="form-select select2" multiple required>
                                                    @foreach ($contractors as $contractor)
                                                        <option value="{{ $contractor->id }}"
                                                            {{ $houseProject->contractors->contains($contractor->id) ? 'selected' : '' }}>
                                                            {{ $contractor->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Houses -->
                                            <div class="col-md-4 mt-3">
                                                <label class="form-label fw-semibold">Houses <span
                                                        class="text-danger">*</span></label>
                                                <select name="rows[{{ $index }}][house_ids][]"
                                                    class="form-select select2 house-field" multiple required>
                                                    @php
                                                        $allHouses = $houseProjects
                                                            ->flatMap(
                                                                fn($proj) => $proj->houseSeries->pluck(
                                                                    'total_of_houses',
                                                                ),
                                                            )
                                                            ->unique()
                                                            ->sort()
                                                            ->values();
                                                    @endphp
                                                    @foreach ($allHouses as $house)
                                                        <option value="{{ $house }}"
                                                            {{ in_array($house, $houseProject->houses->pluck('house_number')->toArray()) ? 'selected' : '' }}>
                                                            House {{ $house }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Total Selected Houses -->
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Total Selected Houses</label>
                                                <input type="number" value="{{ $houseProject->total_houses }}"
                                                    class="form-control total-houses bg-light" readonly>
                                            </div>

                                            <!-- Warehouses -->
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Warehouses</label>
                                                <select name="rows[{{ $index }}][warehouse_id]"
                                                    class="form-select">
                                                    <option value="">Select Warehouse</option>
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}"
                                                            {{ $houseProject->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                                            {{ $warehouse->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Description -->
                                            <div class="col-md-12">
                                                <label class="form-label">Description</label>
                                                <textarea name="rows[{{ $index }}][description]" class="form-control" rows="2">{{ $houseProject->description }}</textarea>
                                            </div>

                                            <!-- items  -->
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Items <span class="text-danger">*</span></label>
                                               <select name="rows[{{ $index }}][item_id]" class="form-select" required>
                                                    <option value="">Select Item</option>
                                                    @forelse ($houseProject->available_items ?? [] as $item)
                                                        <option value="{{ $item->id }}">{{ $item->item }}</option>
                                                    @empty
                                                        <option value="">No items available for {{ $houseProject->house_type_id ?? 'N/A' }}</option>
                                                    @endforelse
                                                </select>

                                            </div>


                                            <!-- Remove Button -->
                                            <div class="col-md-12 text-end">
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- ================= TEMPLATE ROW ================= -->
                        <template id="row-template-new">
                            <div class="project-box-new border rounded p-3 mb-4 bg-light">
                                <div class="row g-3 project-row-new">
                                    <!-- Hidden ID -->
                                    <input type="hidden" name="rows[__INDEX__][house_project_id]" value="">

                                    <!-- House Type -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">House Type <span
                                                class="text-danger">*</span></label>
                                        <select name="rows[__INDEX__][house_type_id]" class="form-select house-type-new"
                                            required>
                                            <option value="">Select House Type</option>
                                            <option value="ATH">ATH</option>
                                            <option value="BTH">BTH</option>
                                            <option value="CTH">CTH</option>
                                            <option value="DTH">DTH</option>
                                        </select>
                                    </div>

                                    <!-- Site Square Yard -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Site Square Yard</label>
                                        <input type="text" name="rows[__INDEX__][site_square_yard]"
                                            class="form-control site-yard-new">
                                    </div>

                                    <!-- Contractor -->
                                    {{-- <div class="col-md-4">
                                        <label class="form-label fw-semibold">Contractor <span
                                                class="text-danger">*</span></label>
                                        <select name="rows[__INDEX__][contractor_id][]"
                                            class="form-select select2 contractor-new" multiple required>
                                            <option value="">Select Contractor</option>
                                            @foreach ($contractors as $contractor)
                                                <option value="{{ $contractor->id }}">{{ $contractor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Contractor <span
                                                class="text-danger">*</span></label>
                                        <select name="rows[__INDEX__][contractor_id][]"
                                            class="form-select select2 contractor-new" multiple required>
                                            @foreach ($contractors as $contractor)
                                                <option value="{{ $contractor->id }}">{{ $contractor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <!-- Warehouse -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Warehouse</label>
                                        <select name="rows[__INDEX__][warehouse_id]" class="form-select warehouse-new">
                                            <option value="">Select Warehouse</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>



                                    <!-- Houses -->
                                    {{-- <div class="col-md-4 mt-3">
                                        <label class="form-label fw-semibold">Houses <span
                                                class="text-danger">*</span></label>
                                        <select name="rows[__INDEX__][house_ids][]"
                                            class="form-select select2 house-field-new" multiple required>
                                            @foreach ($allHouses as $house)
                                                <option value="{{ $house }}">House {{ $house }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label fw-semibold">Houses <span
                                                class="text-danger">*</span></label>
                                        <select name="rows[__INDEX__][house_ids][]"
                                            class="form-select select2 house-field-new" multiple required>
                                            @foreach ($allHouses as $house)
                                                <option value="{{ $house }}">House {{ $house }}</option>
                                            @endforeach
                                        </select>
                                    </div>




                                    <!-- Total Selected Houses -->
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label fw-semibold">Total Selected Houses</label>
                                        <input type="number" class="form-control total-houses-new bg-light" readonly
                                            value="0">
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-12">
                                        <label class="form-label">Description</label>
                                        <textarea name="rows[__INDEX__][description]" class="form-control description-new" rows="2"></textarea>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row-new">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>


                        <!-- Add More button -->
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


    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('project-wrapper');
            const addMoreBtn = document.getElementById('addMoreBtn');
            const template = document.getElementById('row-template'); // hidden template row
            let formIndex = document.querySelectorAll('.project-box').length;

            // ✅ Update houses count
            function updateHouseCount(selectElement) {
                const totalInput = selectElement.closest('.project-box').querySelector('.total-houses');
                if (totalInput) {
                    totalInput.value = $(selectElement).val()?.length || 0;
                }
            }

            // ✅ Prevent duplicate house selection across boxes
            function refreshHouseOptions() {
                let selected = [];
                document.querySelectorAll('.house-field').forEach(select => {
                    const values = $(select).val() || [];
                    selected = selected.concat(values);
                });

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
                    $(select).trigger('change.select2'); // ✅ force refresh
                });
            }

            // ✅ Reindex all rows after add/remove
            function reindexRows() {
                document.querySelectorAll('.project-box').forEach((box, i) => {
                    box.querySelectorAll('[name]').forEach(field => {
                        field.name = field.name.replace(/\[\d+\]/, `[${i}]`);
                    });
                });
            }

            // ✅ Setup select2, events, remove btn
            function setupRow(rowBox) {
                // reinit select2
                rowBox.querySelectorAll('.select2').forEach(select => {
                    if ($(select).hasClass('select2-hidden-accessible')) {
                        $(select).select2('destroy');
                        $(select).next('.select2-container').remove();
                    }
                    $(select).select2({
                        placeholder: 'Select Houses',
                        allowClear: true,
                        width: '100%'
                    });
                });

                // house change listener
                rowBox.querySelectorAll('.house-field').forEach(select => {
                    $(select).off('change').on('change', function() {
                        updateHouseCount(this);
                        refreshHouseOptions();
                    });
                });

                // remove btn
                const removeBtn = rowBox.querySelector('.remove-row');
                if (removeBtn) {
                    removeBtn.onclick = function() {
                        if (document.querySelectorAll('.project-box').length > 1) {
                            rowBox.remove();
                            refreshHouseOptions();
                            reindexRows();
                        } else {
                            alert('At least one category box is required.');
                        }
                    };
                }
            }

            // ✅ Apply setup to existing rows (edit mode)
            document.querySelectorAll('.project-box').forEach(box => setupRow(box));

            // ✅ Add More
            addMoreBtn.addEventListener('click', function() {
                const newBox = template.content.firstElementChild.cloneNode(true);
                formIndex++;

                // update names with new index
                newBox.querySelectorAll('[name]').forEach(field => {
                    field.name = field.name.replace(/\[\d+\]/, `[${formIndex}]`);
                });

                wrapper.appendChild(newBox);
                setupRow(newBox);
                refreshHouseOptions();
                reindexRows();
            });

            // ✅ Initial refresh
            refreshHouseOptions();
        });
    </script> --}}

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('project-wrapper');
            const addMoreBtn = document.getElementById('addMoreBtn');
            const template = document.getElementById('row-template-new'); // 🔹 new template
            let formIndex = document.querySelectorAll('.project-box-new').length;

            // ✅ Update total houses count for new template
            function updateHouseCountNew(selectElement) {
                const totalInput = selectElement.closest('.project-box-new').querySelector('.total-houses-new');
                if (totalInput) {
                    totalInput.value = $(selectElement).val()?.length || 0;
                }
            }

            ✅ Prevent duplicate house selection across new boxes
            function refreshHouseOptionsNew() {
                let selected = [];
                document.querySelectorAll('.house-field-new').forEach(select => {
                    const values = $(select).val() || [];
                    selected = selected.concat(values);
                });

                document.querySelectorAll('.house-field-new').forEach(select => {
                    $(select).find('option').each(function() {
                        if (selected.includes(this.value)) {
                            if (!($(select).val() || []).includes(this.value)) {
                                $(this).prop('disabled', true);
                            }
                        } else {
                            $(this).prop('disabled', false);
                        }
                    });
                    $(select).trigger('change.select2'); // force refresh
                });
            }
            // function refreshHouseOptionsNew() {
            //     const allSelects = document.querySelectorAll('.house-field-new');

            //     // 1️⃣ Collect all selected values from all selects
            //     let selected = [];
            //     allSelects.forEach(select => {
            //         const values = $(select).val() || [];
            //         selected = selected.concat(values);
            //     });

            //     // 2️⃣ Disable options only in other selects, not in the current select
            //     allSelects.forEach(select => {
            //         const currentValues = $(select).val() || [];
            //         $(select).find('option').each(function() {
            //             const value = this.value;
            //             if (selected.includes(value) && !currentValues.includes(value)) {
            //                 $(this).prop('disabled', true);
            //             } else {
            //                 $(this).prop('disabled', false);
            //             }
            //         });
            //         $(select).trigger('change.select2'); // force refresh
            //     });
            // }

            // ✅ Reindex new rows after add/remove
            function reindexRowsNew() {
                document.querySelectorAll('.project-box-new').forEach((box, i) => {
                    box.querySelectorAll('[name]').forEach(field => {
                        field.name = field.name.replace(/\[\d+\]/, `[${i}]`);
                    });
                });
            }

            // ✅ Setup select2, events, remove button for new template
            function setupRowNew(rowBox) {
                // destroy/re-init select2
                rowBox.querySelectorAll('.select2').forEach(select => {
                    if ($(select).hasClass('select2-hidden-accessible')) {
                        $(select).select2('destroy');
                        $(select).next('.select2-container').remove();
                    }
                    $(select).select2({
                        placeholder: 'Select Houses',
                        allowClear: true,
                        width: '100%'
                    });
                });

                // house change listener
                rowBox.querySelectorAll('.house-field-new').forEach(select => {
                    $(select).off('change').on('change', function() {
                        updateHouseCountNew(this);
                        refreshHouseOptionsNew();
                    });
                });

                // remove button
                const removeBtn = rowBox.querySelector('.remove-row-new');
                if (removeBtn) {
                    removeBtn.onclick = function() {
                        if (document.querySelectorAll('.project-box-new').length > 1) {
                            rowBox.remove();
                            refreshHouseOptionsNew();
                            reindexRowsNew();
                        } else {
                            alert('At least one category box is required.');
                        }
                    };
                }
            }

            // ✅ Apply setup to existing rows (edit mode) for new template
            document.querySelectorAll('.project-box-new').forEach(box => setupRowNew(box));

            // ✅ Add More button for new template
            addMoreBtn.addEventListener('click', function() {
                const newBox = template.content.firstElementChild.cloneNode(true);
                formIndex++;

                // update names with new index
                newBox.querySelectorAll('[name]').forEach(field => {
                    field.name = field.name.replace(/\[\d+\]/, `[${formIndex}]`);
                });

                wrapper.appendChild(newBox);
                setupRowNew(newBox);
                refreshHouseOptionsNew();
                reindexRowsNew();
            });

            // ✅ Initial refresh for new template
            refreshHouseOptionsNew();
        });
    </script> --}}

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('project-wrapper');
            const addMoreBtn = document.getElementById('addMoreBtn');
            const template = document.getElementById('row-template-new');
            let formIndex = document.querySelectorAll('.project-box-new').length;

            // ✅ Update total houses count
            function updateHouseCountNew(selectElement) {
                const totalInput = selectElement.closest('.project-box-new').querySelector('.total-houses-new');
                if (totalInput) {
                    totalInput.value = $(selectElement).val()?.length || 0;
                }
            }

            // ✅ Refresh house options to prevent duplicates
            function refreshHouseOptionsNew() {
                const allSelects = document.querySelectorAll('.house-field-new');
                let selected = [];
                allSelects.forEach(select => {
                    const values = $(select).val() || [];
                    selected = selected.concat(values);
                });

                allSelects.forEach(select => {
                    const currentValues = $(select).val() || [];
                    $(select).find('option').each(function() {
                        const value = this.value;
                        // disable if selected in other selects, but not in this select
                        if (selected.includes(value) && !currentValues.includes(value)) {
                            $(this).prop('disabled', true);
                        } else {
                            $(this).prop('disabled', false);
                        }
                    });
                    $(select).trigger('change.select2');
                });
            }

            // ✅ Reindex rows after add/remove
            function reindexRowsNew() {
                document.querySelectorAll('.project-box-new').forEach((box, i) => {
                    box.querySelectorAll('[name]').forEach(field => {
                        field.name = field.name.replace(/\[\d+\]/, `[${i}]`);
                    });
                });
            }

            // ✅ Setup row: select2, events, remove button
            function setupRowNew(rowBox) {
                // Destroy old select2 if exists
                rowBox.querySelectorAll('.select2').forEach(select => {
                    if ($(select).hasClass('select2-hidden-accessible')) {
                        $(select).select2('destroy');
                        $(select).next('.select2-container').remove();
                    }
                    $(select).select2({
                        placeholder: 'Select Houses',
                        allowClear: true,
                        width: '100%'
                    });
                });

                // House change event
                rowBox.querySelectorAll('.house-field-new').forEach(select => {
                    $(select).off('change').on('change', function() {
                        updateHouseCountNew(this);
                        refreshHouseOptionsNew();
                    });
                });

                // Remove button
                const removeBtn = rowBox.querySelector('.remove-row-new');
                if (removeBtn) {
                    removeBtn.onclick = function() {
                        if (document.querySelectorAll('.project-box-new').length > 1) {
                            rowBox.remove();
                            refreshHouseOptionsNew();
                            reindexRowsNew();
                        } else {
                            alert('At least one category box is required.');
                        }
                    };
                }

                // Initial count update for existing values
                rowBox.querySelectorAll('.house-field-new').forEach(select => updateHouseCountNew(select));
            }

            // ✅ Apply setup to existing rows
            document.querySelectorAll('.project-box-new').forEach(box => setupRowNew(box));

            // ✅ Add More button
            addMoreBtn.addEventListener('click', function() {
                const newBox = template.content.firstElementChild.cloneNode(true);
                formIndex++;

                // update names with new index
                newBox.querySelectorAll('[name]').forEach(field => {
                    field.name = field.name.replace(/\[\d+\]/, `[${formIndex}]`);
                });

                wrapper.appendChild(newBox);
                setupRowNew(newBox);
                refreshHouseOptionsNew();
                reindexRowsNew();
            });

            // ✅ Initial refresh
            refreshHouseOptionsNew();
        });
    </script> --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('project-wrapper');
            const addMoreBtn = document.getElementById('addMoreBtn');
            const template = document.getElementById('row-template-new');
            let formIndex = wrapper.querySelectorAll('.project-box-new').length;

            // --------- Update total houses count -----------
            function updateHouseCount(selectEl) {
                const totalInput = selectEl.closest('.project-box, .project-box-new')?.querySelector(
                    '.total-houses, .total-houses-new');
                if (totalInput) {
                    totalInput.value = $(selectEl).val()?.length || 0;
                }
            }

            // --------- Refresh all house selects (optional: prevent duplicates) -----------
            function refreshHouseOptions() {
                const allSelects = document.querySelectorAll('.house-field, .house-field-new');
                const selectedValues = [];

                allSelects.forEach(select => {
                    const vals = $(select).val() || [];
                    selectedValues.push({
                        select,
                        vals
                    });
                });

                allSelects.forEach(select => {
                    const currentVals = $(select).val() || [];
                    $(select).find('option').each(function() {
                        const value = this.value;
                        const disabled = selectedValues.some(obj => obj.select !== select && obj
                            .vals.includes(value));
                        $(this).prop('disabled', disabled);
                    });
                    $(select).val(currentVals).trigger('change.select2');
                });
            }

            // --------- Setup a row (existing or new) -----------
            function setupRow(rowBox) {
                $(rowBox).find('.select2').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy').next('.select2-container').remove();
                    }
                    $(this).select2({
                        placeholder: 'Select Houses',
                        allowClear: true,
                        width: '100%'
                    });
                });

                rowBox.querySelectorAll('.house-field, .house-field-new').forEach(select => {
                    $(select).off('change').on('change', function() {
                        updateHouseCount(this);
                        refreshHouseOptions();
                    });
                    updateHouseCount(select);
                });

                const removeBtn = rowBox.querySelector('.remove-row, .remove-row-new');
                if (removeBtn) {
                    removeBtn.onclick = function() {
                        if (wrapper.querySelectorAll('.project-box, .project-box-new').length > 1) {
                            rowBox.remove();
                            refreshHouseOptions();
                        } else {
                            alert('At least one category is required.');
                        }
                    }
                }
            }

            // --------- Initialize existing rows -----------
            document.querySelectorAll('.project-box, .project-box-new').forEach(box => setupRow(box));

            // --------- Add More -----------
            addMoreBtn.addEventListener('click', function() {
                const newBox = template.content.firstElementChild.cloneNode(true);
                formIndex++;
                newBox.querySelectorAll('[name]').forEach(field => {
                    field.name = field.name.replace('__INDEX__', formIndex);
                });
                wrapper.appendChild(newBox);
                setupRow(newBox);
                refreshHouseOptions();
            });

            // --------- Initial refresh -----------
            refreshHouseOptions();
        });
    </script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('project-wrapper');
        const addMoreBtn = document.getElementById('addMoreBtn');
        const template = document.getElementById('row-template-new');
        let formIndex = wrapper.querySelectorAll('.project-box, .project-box-new').length;

        // --------- Update total houses count -----------
        function updateHouseCount(selectEl) {
            const totalInput = selectEl.closest('.project-box, .project-box-new')
                ?.querySelector('.total-houses, .total-houses-new');
            if (totalInput) {
                totalInput.value = $(selectEl).val()?.length || 0;
            }
        }

        // --------- Refresh all house selects (prevent duplicates) -----------
        function refreshHouseOptions() {
            const allSelects = document.querySelectorAll('.house-field, .house-field-new');
            const selectedValues = [];

            allSelects.forEach(select => {
                const vals = $(select).val() || [];
                selectedValues.push({ select, vals });
            });

            allSelects.forEach(select => {
                const currentVals = $(select).val() || [];
                $(select).find('option').each(function() {
                    const value = this.value;
                    const disabled = selectedValues.some(
                        obj => obj.select !== select && obj.vals.includes(value)
                    );
                    $(this).prop('disabled', disabled);
                });
                $(select).val(currentVals).trigger('change.select2');
            });
        }

        // --------- Setup a row (existing or new) -----------
        function setupRow(rowBox) {
            // ✅ Reset any accidental preselected values (only for new rows)
            if (rowBox.classList.contains('project-box-new')) {
                $(rowBox).find('select option:selected').prop('selected', false);
                $(rowBox).find('select').val(null).trigger('change');
            }

            // ✅ Initialize Select2
            $(rowBox).find('.select2').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy').next('.select2-container').remove();
                }
                $(this).select2({
                    placeholder: 'Select Option',
                    allowClear: true,
                    width: '100%'
                });
            });

            // ✅ House field events
            rowBox.querySelectorAll('.house-field, .house-field-new').forEach(select => {
                $(select).off('change').on('change', function() {
                    updateHouseCount(this);
                    refreshHouseOptions();
                });
                updateHouseCount(select);
            });

            // ✅ Remove button
            const removeBtn = rowBox.querySelector('.remove-row, .remove-row-new');
            if (removeBtn) {
                removeBtn.onclick = function() {
                    if (wrapper.querySelectorAll('.project-box, .project-box-new').length > 1) {
                        rowBox.remove();
                        refreshHouseOptions();
                    } else {
                        alert('At least one category is required.');
                    }
                }
            }
        }

        // --------- Initialize existing rows -----------
        document.querySelectorAll('.project-box, .project-box-new').forEach(box => setupRow(box));

        // --------- Add More -----------
        addMoreBtn.addEventListener('click', function() {
            const newBox = template.content.firstElementChild.cloneNode(true);
            formIndex++;
            newBox.querySelectorAll('[name]').forEach(field => {
                field.name = field.name.replace('__INDEX__', formIndex);
            });
            wrapper.appendChild(newBox);
            setupRow(newBox);
            refreshHouseOptions();
        });

        // --------- Initial refresh -----------
        refreshHouseOptions();
    });
</script>




@endsection
