@extends('layout.master')
@section('title', 'Leave Applications')
@section('header-title', 'Leave Applications')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S:NO</th>
                                <th>ID:NO</th>
                                <th>Name</th>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days Request</th>
                                <th>Date Of Request</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leave as $show)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $show->employee->employee_id ?? 'N/A' }}</td>
                                    <td>{{ $show->name ?? 'N/A' }}</td>
                                    <td>{{ $show->application_type ?? 'N/A' }}</td>
                                    <td>{{ formatDateToCustom ($show->start_date ?? 'N/A') }}</td>
                                    <td>{{ formatDateToCustom ($show->end_date ?? 'N/A') }}</td>
                                    <td>{{ $show->days_request ?? 'N/A' }}</td>
                                    <td>{{ formatDateToCustom ($show->date_of_request ?? 'N/A') }}</td>
                                    <td><a href="{{ route('leave.detail', $show->id) }}"
                                            class="btn btn-sm btn-info">Show</a></td>
                                    <td>
                                        <div class="card-header p-0 m-0">
                                            <div class="card-actions float-end">
                                                <div class="d-inline-block dropdown show">
                                                    <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                                                        <i class="align-middle" data-feather="more-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end p-2" style="min-width: 180px;">
                                                        <!-- HR Button -->
                                                        <button type="button"
                                                            class="btn btn-sm w-100 rounded-2 shadow-sm mb-2
                                                            @if ($show->approved_by_hr == 1) btn-success
                                                            @elseif($show->approved_by_hr == -1) btn-danger
                                                            @else btn-outline-secondary @endif"
                                                            @if (auth()->user()->department != 'hr' || $show->approved_by_hr != 0) disabled @endif
                                                            data-id="{{ $show->id }}" data-dept="hr"
                                                            data-bs-toggle="modal" data-bs-target="#approveModal">
                                                            @if ($show->approved_by_hr == 1)
                                                                HR Approved
                                                            @elseif($show->approved_by_hr == -1)
                                                                HR Rejected
                                                            @else
                                                                HR Decision
                                                            @endif
                                                        </button>

                                                        {{-- Operation Button --}}
                                                      {{-- Operation Button (only if department_check == 1) --}}
                                                    @if ($show->department && $show->department->department_check == 1)
                                                        <button type="button"
                                                            class="btn btn-sm w-100 rounded-2 shadow-sm mb-2
                                                    @if ($show->approved_by_operation == 1) btn-success
                                                    @elseif($show->approved_by_operation == -1) btn-danger
                                                    @else btn-outline-secondary @endif"
                                                            @if (auth()->user()->department != 'operation' ||
                                                                $show->approved_by_hr != 1 ||
                                                                $show->approved_by_operation != 0 ||
                                                                $show->approved_by_hr == -1)
                                                                disabled
                                                            @endif
                                                            data-id="{{ $show->id }}" data-dept="operation"
                                                            data-bs-toggle="modal" data-bs-target="#approveModal">
                                                            @if ($show->approved_by_operation == 1)
                                                                Operation Approved
                                                            @elseif($show->approved_by_operation == -1)
                                                                Operation Rejected
                                                            @else
                                                                Operation Decision
                                                            @endif
                                                        </button>
                                                    @endif

                                                        {{-- Higher Mgmt Button --}}
                                                        @php
                                                        $departmentCheck = $show->department->department_check ?? 0;
                                                        $hrApproved = $show->approved_by_hr == 1;
                                                        $operationApproved = $show->approved_by_operation == 1;
                                                    @endphp

                                                    {{-- Higher Management Button --}}
                                                    <button type="button"
                                                        class="btn btn-sm w-100 rounded-2 shadow-sm
                                                    @if ($show->approved_by_higher_management == 1) btn-success
                                                    @elseif($show->approved_by_higher_management == -1) btn-danger
                                                    @else btn-outline-secondary @endif"
                                                        @if (
                                                            auth()->user()->department != 'Super Admin' ||
                                                            $show->approved_by_higher_management != 0 ||
                                                            !$hrApproved || // HR must approve in both cases
                                                            ($departmentCheck == 1 && !$operationApproved) // if dept_check == 1, operation must also approve
                                                        )
                                                            disabled
                                                        @endif
                                                        data-id="{{ $show->id }}" data-dept="Super Admin"
                                                        data-bs-toggle="modal" data-bs-target="#approveModal">
                                                        @if ($show->approved_by_higher_management == 1)
                                                            Higher Mgmt Approved
                                                        @elseif($show->approved_by_higher_management == -1)
                                                            Higher Mgmt Rejected
                                                        @else
                                                            Higher Mgmt Decision
                                                        @endif
                                                    </button>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Approval Modal -->
                                    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="modalTitle"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="POST" id="approvalForm">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalTitle">Confirm Decision</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="remarks" class="form-label">Remarks
                                                                (optional)
                                                            </label>
                                                            <textarea class="form-control" name="remarks" id="remarks" rows="3" placeholder="Write remarks here..."></textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <button type="submit" name="action" value="reject"
                                                                class="btn btn-danger">Reject</button>
                                                            <button type="submit" name="action" value="approve"
                                                                class="btn btn-success">Approve</button>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">

                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const approvalModal = document.getElementById('approveModal');
        approvalModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const leaveId = button.getAttribute('data-id');
            const dept = button.getAttribute('data-dept');

            const baseUrl = @json(route('leave.approve', '__id__'));
            document.getElementById('approvalForm').action = baseUrl.replace('__id__', leaveId);
            const titleMap = {
                hr: 'HR Decision',
                operation: 'Operation Decision',
                higher_management: 'Higher Management Decision'
            };
            document.getElementById('modalTitle').innerText = titleMap[dept] || 'Confirm Decision';
        });
        approvalModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('remarks').value = '';
        });
    </script>
@endsection
