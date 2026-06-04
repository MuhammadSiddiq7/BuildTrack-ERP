@extends('layout.master')
@section('title', 'Add Categories')
@section('header-title', 'Add Project Categories')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0">
            <div class="card-body">
                <form action="{{ route('project.categories.store', $projects->id) }}" method="POST">
                    @csrf
                    <div id="project-wrapper">
                        <div class="project-box mb-4 p-4">
                            <h5 class="category-title text-primary">Add Categories</h5>

                            <div class="row project-row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">House Type <span class="text-danger">*</span></label>
                                    <select name="house_type_id[]"  class="form-select">
                                        <option value="{{ $projects->house_type }}" disabled>Select House Type</option>
                                        <option value="ATH">ATH</option>
                                        <option value="BTH">BTH</option>
                                        <option value="CTH">CTH</option>
                                        <option value="DTH">DTH</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Site Square Yard <span class="text-danger">*</span></label>
                                    <input type="text" name="site_square_yard[]" placeholder="Enter Site Square Yard"
                                        class="form-control">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label fw-semibold">Contractor <span class="text-danger">*</span></label>
                                    <select name="contractor_id[]" class="form-select select2" multiple="multiple">
                                        @foreach ($contractors as $contractor)
                                            <option value="{{ $contractor->id }}">{{ $contractor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-4 mt-3">
                                    <label class="form-label fw-semibold">Houses <span class="text-danger">*</span></label>
                                    <select name="houses[0][]" class="form-select select2 house-field" multiple required>
                                        @for ($i = 1; $i <= $projects->number_of_houses; $i++)
                                            <option value="{{ $i }}">House {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div> --}}
                                <div class="col-md-4 mt-3">
                                    <label class="form-label fw-semibold">Houses <span class="text-danger">*</span></label>
                                    <select name="houses[0][]" class="form-select select2 house-field" multiple required>
                                        @foreach ($houseProjects as $houseProject)
                                            @foreach ($houseProject->houseSeries as $series)
                                                <option value="{{ $series->total_of_houses }}">House {{ $series->total_of_houses }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label fw-semibold">Total Selected Houses</label>
                                        <input type="number" class="form-control total-houses bg-light" readonly value="0">
                                    </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label fw-semibold">Warehouses</label>
                                    <select name="warehouse_id[]" class="form-select">
                                        <option value="">Select Warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description[]" placeholder="Enter description" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-md-1 d-flex align-items-end mt-3">
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

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save"></i> <b>Save</b>
                        </button>
                    </div>
                </form>
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



                        {{-- without disabling js already selected houses
                        {{-- <script>
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

                                function initializeSelect2(selectElement) {
                                    $(selectElement).select2({
                                        placeholder: 'Select Contractor',
                                        allowClear: true,
                                        width: '100%'
                                    });
                                    $(selectElement).val(null).trigger('change');
                                }

                                function setupRow(rowBox) {
                                    rowBox.querySelectorAll('.select2').forEach(select => {
                                        if ($(select).hasClass('select2-hidden-accessible')) {
                                            $(select).select2('destroy');
                                        }
                                        $(select).val(null);
                                        $(select).removeAttr('data-select2-id').removeAttr('aria-hidden').removeAttr(
                                        'tabindex');
                                        $(select).next('.select2-container').remove();
                                    });

                                    $('.select2').select2({
                                        placeholder: 'Select Contractor',
                                        allowClear: true,
                                        width: 'resolve'
                                    });

                                    rowBox.querySelectorAll('input, select, textarea').forEach(field => {
                                        if (field.tagName === 'SELECT') {
                                            field.selectedIndex = -1;
                                        } else {
                                            field.value = '';
                                        }
                                    });

                                    rowBox.querySelectorAll('.select2').forEach(select => {
                                        initializeSelect2(select);
                                    });

                                    const removeBtn = rowBox.querySelector('.remove-row');
                                    if (removeBtn) {
                                        removeBtn.addEventListener('click', function() {
                                            if (document.querySelectorAll('.project-box').length > 1) {
                                                rowBox.remove();
                                            } else {
                                                alert('At least one category box is required.');
                                            }
                                        });
                                    }

                                    // 🔹 bind houses change event for count
                                    rowBox.querySelectorAll('.house-field').forEach(select => {
                                        $(select).on('change', function() {
                                            updateHouseCount(this);
                                        });
                                    });
                                }

                                document.querySelectorAll('.project-box').forEach(box => {
                                    setupRow(box);
                                });

                                addMoreBtn.addEventListener('click', function() {
                                    formIndex++;

                                    const firstBox = wrapper.querySelector('.project-box');

                                    firstBox.querySelectorAll('.select2').forEach(select => {
                                        if ($(select).hasClass('select2-hidden-accessible')) {
                                            $(select).select2('destroy');
                                            $(select).next('.select2-container').remove();
                                        }
                                    });

                                    const newBox = firstBox.cloneNode(true);

                                    // Add fresh title
                                    newBox.querySelector('.category-title').textContent = 'Add Categories';

                                    // update input names with new index + reset total
                                    newBox.querySelectorAll('input, select, textarea').forEach(field => {
                                        if (field.name) {
                                            field.name = field.name.replace(/\[\d+\]/, `[${formIndex}]`);
                                        }
                                        if (field.classList.contains('total-houses')) {
                                            field.value = 0; // reset total
                                        }
                                    });

                                    setupRow(newBox);

                                    wrapper.appendChild(newBox);
                                });
                            });
                        </script>  --}}

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
                                                // disable if not chosen in this select
                                                if (!($(select).val() || []).includes(this.value)) {
                                                    $(this).prop('disabled', true);
                                                }
                                            } else {
                                                $(this).prop('disabled', false);
                                            }
                                        });
                                        $(select).trigger('change.select2'); // refresh select2 UI
                                    });
                                }

                                function initializeSelect2(selectElement) {
                                    $(selectElement).select2({
                                        placeholder: 'Select Contractor',
                                        allowClear: true,
                                        width: '100%'
                                    });
                                    $(selectElement).val(null).trigger('change');
                                }

                                function setupRow(rowBox) {
                                    rowBox.querySelectorAll('.select2').forEach(select => {
                                        if ($(select).hasClass('select2-hidden-accessible')) {
                                            $(select).select2('destroy');
                                        }
                                        $(select).val(null);
                                        $(select).removeAttr('data-select2-id').removeAttr('aria-hidden').removeAttr(
                                            'tabindex');
                                        $(select).next('.select2-container').remove();
                                    });

                                    $('.select2').select2({
                                        placeholder: 'Select Contractor',
                                        allowClear: true,
                                        width: 'resolve'
                                    });

                                    rowBox.querySelectorAll('input, select, textarea').forEach(field => {
                                        if (field.tagName === 'SELECT') {
                                            field.selectedIndex = -1;
                                        } else {
                                            field.value = '';
                                        }
                                    });

                                    rowBox.querySelectorAll('.select2').forEach(select => {
                                        initializeSelect2(select);
                                    });

                                    const removeBtn = rowBox.querySelector('.remove-row');
                                    if (removeBtn) {
                                        removeBtn.addEventListener('click', function() {
                                            if (document.querySelectorAll('.project-box').length > 1) {
                                                rowBox.remove();
                                                refreshHouseOptions(); // 🔹 update after removing row
                                            } else {
                                                alert('At least one category box is required.');
                                            }
                                        });
                                    }

                                    // 🔹 bind houses change event for count + refresh
                                    rowBox.querySelectorAll('.house-field').forEach(select => {
                                        $(select).on('change', function() {
                                            updateHouseCount(this);
                                            refreshHouseOptions();
                                        });
                                    });
                                }

                                document.querySelectorAll('.project-box').forEach(box => {
                                    setupRow(box);
                                });

                                refreshHouseOptions(); // 🔹 run on page load

                                addMoreBtn.addEventListener('click', function() {
                                    formIndex++;

                                    const firstBox = wrapper.querySelector('.project-box');

                                    firstBox.querySelectorAll('.select2').forEach(select => {
                                        if ($(select).hasClass('select2-hidden-accessible')) {
                                            $(select).select2('destroy');
                                            $(select).next('.select2-container').remove();
                                        }
                                    });

                                    const newBox = firstBox.cloneNode(true);

                                    // Add fresh title
                                    newBox.querySelector('.category-title').textContent = 'Add Categories';

                                    // update input names with new index + reset total
                                    newBox.querySelectorAll('input, select, textarea').forEach(field => {
                                        if (field.name) {
                                            field.name = field.name.replace(/\[\d+\]/, `[${formIndex}]`);
                                        }
                                        if (field.classList.contains('total-houses')) {
                                            field.value = 0; // reset total
                                        }
                                    });

                                    setupRow(newBox);

                                    wrapper.appendChild(newBox);

                                    refreshHouseOptions(); // 🔹 update after adding new row
                                });
                            });
                        </script>



                    @endsection
