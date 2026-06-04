@extends('layout.master')
@section('title', 'Assign Activity')
@section('header-title', 'Assign Activity')
@section('content')


    <style>
        .assign-header {
            background: linear-gradient(to right, #153d77, #1e4e9c);
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            color: white;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .supplier-name-box {
            background: white;
            color: #153d77;
            font-weight: 600;
            padding: 0.4rem 1rem;
            border-radius: 25px;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-weight: 600;
            font-size: 1.1rem;
            border-bottom: 2px solid #153d77;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            color: #153d77;
        }

        .btn-theme {
            background-color: #153d77;
            color: white;
            border: none;
        }

        .btn-theme:hover {
            background-color: #0f2b50;
            color: white;
        }

        table th {
            background-color: #f1f5f9;
            font-weight: 600;
        }

        table td,
        table th {
            vertical-align: middle !important;
        }

        .form-control {
            height: 32px;
            font-size: 0.9rem;
        }

        .btn {
            font-size: 0.875rem;
        }
        /* Parent row color */
    .table-primary.fw-bold {
        background-color: #e8f0ff !important; /* light blueish tone for parent */
    }

    /* Child row color */
    .child-row {
        background-color: #ffffb3 !important; /* soft yellow */
    }

    /* Optional: hover effect */
    .child-row:hover,
    .table-primary:hover {
        background-color: #f5f5a5 !important;
        transition: 0.2s ease-in-out;
    }
    </style>


    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="container-fluid">
                        <!-- Header -->
                        <div class="assign-header">
                            <h5 class="mb-0" style="color: white;">📦 Assign </h5>
                            <div class="supplier-name-box">
                                Project: {{ $planing->project->project_name ?? 'N/A' }}
                            </div>
                            <div class="supplier-name-box">
                                Type: {{ $planing->type->house_type_id ?? 'N/A' }}
                            </div>
                            <div class="supplier-name-box">
                                House No: {{ $planing->house->house_number ?? 'N/A' }}
                            </div>
                            <div class="supplier-name-box">
                                Contractor: {{ $planing->contractor->name ?? 'N/A' }}
                            </div>
                        </div>
                        <!-- Form -->
                        <form action="{{ route('plan.storeAssignedActivity') }}" method="POST" id="itemAssignForm">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $planing->id ?? '' }}">
                            <input type="hidden" name="project_id" value="{{ $planing->project->id ?? '' }}">
                            <input type="hidden" name="house_project_houses_id" value="{{ $planing->house->id ?? '' }}">
                            <input type="hidden" name="house_project_id" value="{{ $planing->type->id ?? '' }}">
                            <input type="hidden" name="contractor_id" value="{{ $planing->contractor->id ?? '' }}">
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <div class="section-title">🟢 Activities</div>
                                    <button type="button" class="btn btn-sm btn-theme mb-2"
                                        onclick="selectAll('available')">Select All</button>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered"
                                            id="datatables-reponsive-supplier">
                                            <thead>
                                                <tr>
                                                    <th>Select</th>
                                                    <th>Code</th>
                                                    <th>Activity</th>
                                                    <th>Yardstick</th>
                                                    <th>Schedule</th>
                                                    <th>Start Date</th>
                                                    <th>Finsish Date</th>
                                                    <th>Original</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($activity as $parent)
                                                    <tr class="table-primary fw-bold">
                                                        <td>
                                                            <input type="checkbox" class="available-checkbox"
                                                                name="activity_ids[]" value="{{ $parent->id }}">
                                                        </td>
                                                        <td>{{ $parent->activity_code }}</td>
                                                        <td>{{ $parent->name }}</td>
                                                        <td>{{ $parent->yardstick }}</td>
                                                        <td><input type="text" class="form-control schedule-input"
                                                                name="schedule[{{ $parent->id }}]"></td>
                                                        <td><input type="date" class="form-control start-date"
                                                                name="start_date[{{ $parent->id }}]"></td>
                                                        <td><input type="date" class="form-control finish-date"
                                                                name="finish_date[{{ $parent->id }}]"></td>
                                                        <td><input type="text" class="form-control" readonly
                                                                name="original[{{ $parent->id }}]"></td>
                                                    </tr>

                                                    @foreach ($parent->children as $child)
                                                        <tr class="child-row">
                                                            <td>
                                                                <input type="checkbox" class="available-checkbox"
                                                                    name="activity_ids[]" value="{{ $child->id }}">
                                                            </td>
                                                            <td> {{ $child->activity_code }}</td>
                                                            <td><span style="margin-left: 25px; margin-right: 10px;">
                                                                    {{ $child->name }}</span></td>
                                                            <td>{{ $child->yardstick }}</td>
                                                            <td><input type="text" class="form-control schedule-input"
                                                                    name="schedule[{{ $child->id }}]"></td>
                                                            <td><input type="date" class="form-control start-date"
                                                                    name="start_date[{{ $child->id }}]"></td>
                                                            <td><input type="date" class="form-control finish-date"
                                                                    name="finish_date[{{ $child->id }}]"></td>
                                                            <td><input type="text" class="form-control original-days"
                                                                    readonly name="original[{{ $child->id }}]"></td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                    <button type="submit" class="btn btn-success mt-2" onclick="assignSelected()">✅
                                        Assign</button>
                                </div>
                                <div class="col-lg-12">
                                    <div class="section-title">🔴 Assigned Activities</div>
                                    <button type="button" class="btn btn-sm btn-theme mb-2"
                                        onclick="selectAll('assigned')">Select All</button>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered assignedTable"
                                            id="datatables-reponsive-unassigned">
                                            <thead>
                                                <tr>
                                                    <th>Select</th>
                                                    <th>Code</th>
                                                    <th>Activity</th>
                                                    <th>Yardstick</th>
                                                    <th>Start Date</th>
                                                    <th>Finsish Date</th>
                                                    <th>Original</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (isset($assignedActivities['root']))
                                                    @foreach ($assignedActivities['root'] as $parent)
                                                        <tr class="table-primary fw-bold">
                                                            <td><input type="checkbox" class="assigned-checkbox"
                                                                    value="{{ $parent->id }}"></td>
                                                            <td>{{ $parent->activity->activity_code }}</td>
                                                            <td>{{ $parent->activity->name }}</td>
                                                            <td>{{ $parent->activity->yardstick }}</td>
                                                            <td>{{ $parent->start_date }}</td>
                                                            <td>{{ $parent->finish_date }}</td>
                                                            <td>{{ $parent->original }}</td>
                                                            <td>{{ $parent->created_at->format('Y-m-d') }}</td>
                                                        </tr>

                                                        {{-- children --}}
                                                        @foreach ($assignedActivities[$parent->activity->id] ?? [] as $child)
                                                            <tr class="child-row">
                                                                <td><input type="checkbox" class="assigned-checkbox"
                                                                        value="{{ $child->id }}"></td>
                                                                <td>{{ $child->activity->activity_code }}</td>
                                                                <td><span style="margin-left: 25px; margin-right: 10px;">
                                                                    {{ $child->activity->name }}<span></td>
                                                                <td>{{ $child->activity->yardstick }}</td>
                                                                <td>{{ $child->start_date }}</td>
                                                                <td>{{ $child->finish_date }}</td>
                                                                <td>{{ $child->original }}</td>
                                                                <td>{{ $child->created_at->format('Y-m-d') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="7" class="text-center">No activities assigned yet.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>


                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-danger mt-2"
                                        onclick="unassignSelected('{{ route('plan.unassignActivity') }}')">
                                        ❌ Unassign
                                    </button>
                                </div>
                        </form>
                        <!-- End form -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Make sure jQuery is loaded -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            function calculateDays(startDate, endDate) {
                let start = new Date(startDate);
                let end = new Date(endDate);
                if (isNaN(start.getTime()) || isNaN(end.getTime())) return '';
                let diff = end - start;
                let days = Math.ceil(diff / (1000 * 60 * 60 * 24)) + 1;
                return days > 0 ? days : '';
            }
            $('.start-date, .finish-date').on('change', function() {
                let id = $(this).data('id');
                let startDate = $(`input[name="start_date[${id}]"]`).val();
                let finishDate = $(`input[name="finish_date[${id}]"]`).val();
                let days = calculateDays(startDate, finishDate);
                $(`#original_${id}`).val(days);
            });
        });
    </script>

    <script>
        if (!$.fn.dataTable.isDataTable('#datatables-reponsive-supplier')) {
            $('#datatables-reponsive-supplier').DataTable({
                pageLength: 20,
                lengthMenu: [
                    [20, 40, 60, 80, 100],
                    [20, 40, 60, 80, 100]
                ],
                responsive: true
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive-unassigned").DataTable({
                pageLength: 20,
                lengthMenu: [
                    [20, 40, 60, 80, 100],
                    [20, 40, 60, 80, 100]
                ],
                responsive: true
            });
        });
    </script>

    <script>
        document.getElementById('itemAssignForm').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('.available-checkbox:checked');
            let valid = false;

            checkboxes.forEach(function(checkbox) {
                const row = checkbox.closest('tr');
                const priceInput = row.querySelector('.rate-input');
                const value = parseFloat(priceInput.value);

                if (!isNaN(value) && value > 0) {
                    valid = true;
                }
            });

            if (!valid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No Items Assigned',
                    text: 'Please assign at least one item with a valid rate before submitting.',
                });
            }
        });

        function assignSelected() {
            const checkboxes = document.querySelectorAll('.available-checkbox:checked');
            if (checkboxes.length === 0) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No items selected',
                    text: 'Please select at least one item to assign!',
                });
            }
        }

        function unassignSelected(unassignUrl) {
            const checkboxes = document.querySelectorAll('.assigned-checkbox:checked');
            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No items selected',
                    text: 'Please select at least one item to unassign!',
                });
                return;
            }

            const items = [];
            checkboxes.forEach(row => {
                const tr = row.closest('tr');
                const itemId = tr.getAttribute('data-id');
                if (itemId) {
                    items.push({
                        id: itemId
                    });
                }
            });

            fetch(unassignUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        items
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Unassigned!',
                            text: 'Items unassigned successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed!',
                            text: 'Something went wrong during unassigning.',
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while unassigning items.',
                    });
                });
        }

        // selectAll function to toggle checkboxes codde
        function selectAll(type) {
            const selector = type === 'available' ? '.available-checkbox' : '.assigned-checkbox';
            const checkboxes = document.querySelectorAll(selector);
            let allChecked = true;

            checkboxes.forEach(cb => {
                if (!cb.checked) {
                    allChecked = false;
                }
            });

            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
            });
        }
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Holidays from backend
        let holidays = @json($holidays);
        let weekendDays = @json($weekendDays);

        // Convert holidays (single date + range) into array of disabled dates
        let disabledDates = [];

        holidays.forEach(h => {
            if (h.date) {
                disabledDates.push(h.date); // single holiday
            }
            if (h.start_date && h.end_date) {
                // range holiday
                let start = new Date(h.start_date);
                let end = new Date(h.end_date);
                while (start <= end) {
                    disabledDates.push(start.toISOString().split('T')[0]);
                    start.setDate(start.getDate() + 1);
                }
            }
        });

        // Attach Flatpickr to all start_date and finish_date inputs
        document.querySelectorAll(".start-date, .finish-date").forEach(function (input) {
            flatpickr(input, {
                dateFormat: "Y-m-d",
                disable: [
                    // holidays
                    ...disabledDates,

                    // weekends (by weekday number)
                    function (date) {
                        return weekendDays.includes(date.getDay());
                    }
                ]
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let holidays = @json($holidays); // backend se holidays aa rahe hain
        let weekendDays = @json($weekendDays);

        // Convert holidays (single + range) into array of disabled dates
        let disabledDates = [];
        holidays.forEach(h => {
            if (h.date) {
                disabledDates.push(h.date);
            }
            if (h.start_date && h.end_date) {
                let start = new Date(h.start_date);
                let end = new Date(h.end_date);
                while (start <= end) {
                    disabledDates.push(start.toISOString().split("T")[0]);
                    start.setDate(start.getDate() + 1);
                }
            }
        });

        function isHolidayOrWeekend(date) {
            let dateStr = date.toISOString().split("T")[0];
            if (disabledDates.includes(dateStr)) return true;
            if (weekendDays.includes(date.getDay())) return true;
            return false;
        }

        function calculateFinishDate(startDate, scheduleDays) {
            let current = new Date(startDate);
            let daysAdded = 0;

            while (daysAdded < scheduleDays) {
                current.setDate(current.getDate() + 1);
                if (!isHolidayOrWeekend(current)) {
                    daysAdded++;
                }
            }
            return current;
        }

        $(document).on("input change", ".start-date, .schedule-input", function () {
            let row = $(this).closest("tr");
            let startDate = row.find(".start-date").val();
            let schedule = parseInt(row.find('input[name^="schedule"]').val());

            if (startDate && schedule > 0) {
                let start = new Date(startDate);
                let finish = calculateFinishDate(start, schedule - 1); // -1 because start day is counted

                let finishDateStr = finish.toISOString().split("T")[0];
                row.find(".finish-date").val(finishDateStr);

                // Original days calculation (including weekends/holidays)
                let diff = finish - start;
                let originalDays = Math.ceil(diff / (1000 * 60 * 60 * 24)) + 1;
                row.find('input[name^="original"]').val(originalDays);
            }
        });
    });
</script>

    {{-- start_date finish_date days auto count start --}}
    <script>
        $(document).ready(function() {
            function calculateDays(startDate, endDate) {
                let start = new Date(startDate);
                let end = new Date(endDate);
                if (isNaN(start.getTime()) || isNaN(end.getTime())) return '';
                let diff = end - start;
                let days = Math.ceil(diff / (1000 * 60 * 60 * 24)) + 1;
                return days > 0 ? days : '';
            }
            $(document).on('change', '.start-date, .finish-date', function() {
                let row = $(this).closest('tr');
                let startDate = row.find('.start-date').val();
                let finishDate = row.find('.finish-date').val();
                let days = calculateDays(startDate, finishDate);
                row.find('input[name^="original"]').val(days);
            });
        });
    </script>
    {{-- start_date finish_date days auto count end --}}

    {{-- unassign plan --}}
    <script>
        function unassignSelected(unassignUrl) {
            const checkboxes = document.querySelectorAll('.assigned-checkbox:checked');
            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No activities selected',
                    text: 'Please select at least one activity to unassign!',
                });
                return;
            }

            const items = [];
            checkboxes.forEach(row => {
                items.push({
                    id: row.value
                });
            });

            fetch(unassignUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        items
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Unassigned!',
                            text: 'Activities unassigned successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed!',
                            text: data.message || 'Something went wrong during unassigning.',
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while unassigning activities.',
                    });
                });
        }
    </script>
    {{-- unassign plan --}}
@endsection
