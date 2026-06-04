@extends('layout.master')
@section('title', 'Attendance')
@section('header-title', 'Attendance')
@section('content')

<style>
    .attendance-options {
        white-space: nowrap;
        text-align: center;
    }

    .attendance-options label {
        font-size: 11px;
        margin: 0 1px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }

    .attendance-options input {
        margin: 0 2px 0 0;
        vertical-align: middle;
    }

    .table {
        table-layout: fixed;
        text-align: center;
    }

    .table th,
    .table td {
        padding: 8px 4px 10px 4px;
        line-height: 1;
        vertical-align: middle;
    }

    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 150px;
    }

    .table th,
    .table td {
        width: 90px;
        font-weight: 700;
    }

    .table-header-color th {
        background-color: #153d77;
        color: white !important;
    }

    .attendance-options.present {
        background: #28a745;
        color: white !important;
        font-weight: bold;
    }

    .attendance-options.absent {
        background: #dc3545;
        color: white !important;
        font-weight: bold;
    }

    .attendance-options.disabled-cell {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
        /* Prevent clicks */
    }

    .attendance-options label {
        font-size: 12px;
        cursor: pointer;
        margin: 0 2px;
    }

    .attendance-options input {
        margin-right: 2px;
    }

    .card-header label {
        font-size: 0.9rem;
        cursor: pointer;
    }

    .card-header button {
        padding: 3px 10px;
        font-size: 0.85rem;
    }

    .card-header .form-check-input {
        width: 16px;
        height: 16px;
    }

    .selected-cell {
        outline: 2px dashed #007bff;
        cursor: pointer;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center flex-wrap" style="gap: 10px; background-color: #f5f6fa;">
                <!-- Check All -->
                <label class="d-flex align-items-center gap-2 mb-0 fw-semibold" style="font-size: 0.95rem; color: #333;">
                    <input type="checkbox" id="checkAll" class="form-check-input" style="width: 18px; height: 18px;border: 2px solid #041b32 !important;"> Check All
                </label>

                <div id="markButtons" style="display:none; gap:5px;">
                    <button type="button" id="markAllPresent" class="btn btn-success btn-sm" style="padding: 4px 12px; font-weight: 500;">Present</button>
                    <button type="button" id="markAllAbsent" class="btn btn-danger btn-sm" style="padding: 4px 12px; font-weight: 500;">Absent</button>
                </div>


                <!-- Button to Open Modal -->
<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#attendanceReportModal">
    Download Attendance Report
</button>

<!-- Modal -->
<div class="modal fade" id="attendanceReportModal" tabindex="-1" aria-labelledby="attendanceReportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-semibold" id="attendanceReportModalLabel">Generate Attendance Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="GET" action="{{ route('attendances.report') }}">
        <div class="modal-body">
            <!-- Employee Select -->
            <div class="mb-3">
                <label for="employee_id" class="form-label">Select Employee</label>
                <select name="employee_id" id="employee_id" class="form-select" required>
                    <option value="">-- Choose Employee --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">
                            {{ $employee->name }} ({{ $employee->employee_id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Month & Year -->
            <div class="mb-3">
                <label for="month" class="form-label">Select Month</label>
                <input type="month" name="month" id="month" class="form-control" value="{{ now()->format('Y-m') }}" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Download PDF</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>


                <!-- Month Filter -->
                <div class="ms-auto">
                    <form method="GET" action="{{ route('attendances.index') }}" class="d-flex align-items-center" style="gap:5px;">
                        <label class="fw-semibold me-2 mb-0" style="font-size: 0.9rem; color:#333;">Filter by Month:</label>
                        <input type="month" name="month"
                            value="{{ request('month', $selectedMonth ?? now()->format('Y-m')) }}"
                            class="form-control form-control-sm" style="width:180px;">

                        <!-- Filter button -->
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>

                        <!-- Reset button -->
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary btn-md">Reset</a>
                    </form>
                </div>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr class="table-header-color">
                                <th>#</th>
                                <th>Employee Name</th>
                                @for ($i = 1; $i <= $daysInMonth; $i++)
                                    <th>{{ $i }}</th>
                                    @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $emp)
                            <tr>
                                <th scope="row">{{ $emp->id }}</th>
                                <td>{{ $emp->name }}</td>
                                @for ($i = 1; $i <= $daysInMonth; $i++)
                                    @php
                                    // Create date for the cell
                                    $date=\Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)
                                    ->startOfMonth()
                                    ->addDays($i - 1)
                                    ->startOfDay()
                                    ->format('Y-m-d');

                                    $attendance = $emp->attendances->firstWhere('attendance_date', $date);

                                    $today = isset($today) ? \Carbon\Carbon::parse($today)->startOfDay()->format('Y-m-d') : \Carbon\Carbon::today()->format('Y-m-d');

                                    $isCurrentMonth = $selectedMonth === \Carbon\Carbon::now()->format('Y-m');
                                    $isToday = $date === $today;

                                    $disabled = !$isCurrentMonth || !$isToday || $attendance !== null;

                                    @endphp
                                    <td class="attendance-options {{ $attendance ? ($attendance->attendance === 'Present' ? 'present' : 'absent') : '' }} {{ $disabled ? 'disabled-cell' : '' }}"
                                        data-emp="{{ $emp->id }}"
                                        data-date="{{ $date }}">
                                        @if ($attendance)

                                        <span>{{ $attendance->attendance === 'Present' ? 'P' : 'A' }}</span>
                                        @elseif ($isCurrentMonth && $isToday)

                                        <input type="radio"
                                            name="attendance[{{ $emp->id }}][{{ $date }}]"
                                            value="Present"
                                            data-emp="{{ $emp->id }}"
                                            data-date="{{ $date }}"> P
                                        <input type="radio"
                                            name="attendance[{{ $emp->id }}][{{ $date }}]"
                                            value="Absent"
                                            data-emp="{{ $emp->id }}"
                                            data-date="{{ $date }}"> A
                                        @else

                                        <span>-</span>
                                        @endif
                                    </td>
                                    @endfor
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

        $('td.attendance-options').each(function() {
            console.log('Initial state: emp=' + $(this).data('emp') + ', date=' + $(this).data('date') + ', disabled=' + $(this).hasClass('disabled-cell'));
        });

        function toggleMarkButtons() {
            var selectedCells = $('td.attendance-options.selected-cell:not(.disabled-cell)');
            if (selectedCells.length > 0) {
                $('#markButtons').fadeIn(150);
            } else {
                $('#markButtons').fadeOut(150);
            }
        }


        $('#checkAll').change(function() {
            var checked = $(this).prop('checked');
            $('td.attendance-options:not(.disabled-cell)').each(function() {
                $(this).toggleClass('selected-cell', checked);
            });
            toggleMarkButtons();
        });


        $('#markAllPresent').click(function() {
            $('td.attendance-options.selected-cell:not(.disabled-cell)').each(function() {
                var radio = $(this).find('input[value=Present]');
                if (!radio.prop('disabled')) {
                    radio.prop('checked', true).trigger('change');
                }
                $(this).removeClass('selected-cell');
            });
            toggleMarkButtons();
            $('#checkAll').prop('checked', false);
        });


        $('#markAllAbsent').click(function() {
            $('td.attendance-options.selected-cell:not(.disabled-cell)').each(function() {
                var radio = $(this).find('input[value=Absent]');
                if (!radio.prop('disabled')) {
                    radio.prop('checked', true).trigger('change');
                }
                $(this).removeClass('selected-cell');
            });
            toggleMarkButtons();
            $('#checkAll').prop('checked', false);
        });


        $(document).on('click', 'td.attendance-options:not(.disabled-cell)', function(e) {
            if (!$(e.target).is('input[type=radio]')) {
                $(this).toggleClass('selected-cell');
                toggleMarkButtons();
            }
        });


        $(document).on('change', 'td.attendance-options input[type=radio]', function() {
            var td = $(this).closest('td');
            var status = $(this).val();
            var empId = $(this).data('emp');
            var date = $(this).data('date');

            // Update UI
            td.find('input[type=radio]').prop('disabled', true);
            td.removeClass('present absent selected-cell');
            td.addClass(status === 'Present' ? 'present' : 'absent');
            td.addClass('disabled-cell');

            // AJAX save
            $.ajax({
                url: "{{ route('attendance.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    employee_id: empId,
                    attendance_date: date,
                    attendance: status
                },
                success: function(response) {
                    console.log('Attendance saved:', response);
                    // Replace radio buttons with static P or A
                    td.html(status === 'Present' ? 'P' : 'A');
                },
                error: function(xhr) {
                    console.error('Error saving attendance:', xhr.responseText);
                    // Revert UI on error
                    td.find('input[type=radio]').prop('disabled', false);
                    td.removeClass('present absent disabled-cell');
                }
            });

            toggleMarkButtons();
        });
    });
</script>

@endsection
