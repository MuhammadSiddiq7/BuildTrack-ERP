@extends('layout.master')
@section('title', 'Employees List')
@section('header-title', 'Employees List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- Header Buttons -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    @can('employee_create')
                    <a href="{{ route('employee.create') }}" class="btn btn-primary">Create Employee</a>
                    @endcan
                    {{-- @can('employee_import')
                            <button type="button" class="btn btn-secondary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                data-bs-target="#importModal">
                                <i class="bi bi-upload"></i>
                                <span>Import Employees</span>
                            </button>
                        @endcan --}}
                </div>
                @can('employee_trash_view')
                <a href="{{ route('employee.trash') }}" class="btn btn-danger d-flex align-items-center gap-2 ms-auto"
                    title="Deleted">
                    <i class="bi bi-trash-fill"></i>
                    <span>Trash</span>
                    <span class="badge bg-light text-dark">{{ $trashemployee ?? 0 }}</span>
                </a>
                @endcan
            </div>
            {{-- <div class="container mt-4">
    <h4>Import Employees (Excel/CSV)</h4>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{!! session('error') !!}</div> @endif

    <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Choose file (.xlsx, .xls, .csv)</label>
            <input type="file" name="file" accept=".xlsx,.xls,.csv" class="form-control" required>
        </div>
        <button class="btn btn-primary">Upload & Import</button>
    </form>

    <hr>
    <h6>Required headers (example):</h6>
    <pre>employee_id,name,father_name,designation,joining_date,tenure,cnic,mobile_number,account_number,dob,age,education,deployment_area,project,employee_department,employee_status,comment,basic_salary,house_rent,medical,utilities,fuel_allowance_monthly,gross_salary,company_vehicle,fuel_allowance_vehicle,card_account,calling_card,scale_level</pre>
</div> --}}



            {{-- <form method="POST" action="{{ route('employee.bulk-delete') }}" id="bulkDeleteForm">
                @csrf
                @method('DELETE') --}}

                <div class="card-body">
                    {{-- <button type="submit" class="btn btn-danger mb-3"
                            onclick="return confirm('Are you sure you want to delete selected employees?')">
                            Delete Selected
                        </button> --}}

                    <div class="row">
                        <div class="col-12 col-lg-12">
                            <div class="tab tab-primary">
                                <!-- <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation"><a class="nav-link active" href="#primary-tab-1" data-bs-toggle="tab" role="tab" aria-selected="true">Active Employees</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="#primary-tab-2" data-bs-toggle="tab" role="tab" aria-selected="false" tabindex="-1">Inactive Employees</a></li>
                    </ul> -->


                                <ul class="nav nav-tabs border-0" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active d-flex align-items-center px-4 py-2 rounded-3 me-2 fs-6 fw-normal"
                                            style="font-size: 15px !important; font-weight: 600 !important; letter-spacing: 1px !important" href="#primary-tab-1" data-bs-toggle="tab" role="tab" aria-selected="true">
                                            <i class="bi bi-person-check me-2" style="font-size: 20px !important; font-weight: 600 !important;"></i> Active Employees
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link d-flex align-items-center px-4 py-2 rounded-3 fs-6 fw-normal"
                                            href="#primary-tab-2" style="font-size: 15px !important; font-weight: 600 !important; letter-spacing: 1px !important" data-bs-toggle="tab" role="tab" aria-selected="false">
                                            <i class="bi bi-person-x me-2" style="font-size: 20px !important; font-weight: 600 !important;"></i> Inactive Employees
                                        </a>
                                    </li>
                                </ul>


                                <div class="tab-content" style="background-color: white !important; color: black !important">
                                    <div class="tab-pane active" id="primary-tab-1" role="tabpanel">
                                        <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    {{-- <th><input type="checkbox" id="select_all"></th> --}}
                                                    <th>S:NO</th>
                                                    <th>Name</th>
                                                    <th>Employee ID</th>
                                                    {{-- <th>Company</th> --}}
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employees as $employee)
                                                @if ($employee->employee_status === 'active')
                                                <tr>
                                                    {{-- <td>
                                            <input type="checkbox" name="employee_ids[]" class="row_checkbox"
                                                value="{{ $employee->id }}">
                                                    </td> --}}
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $employee->name ?? 'N/A' }}</td>
                                                    <td>{{ $employee->employee_id ?? 'N/A' }}</td>
                                                    <td>
                                                        @if (!empty($employee->employee_status))
                                                        <span
                                                            class="badge bg-{{ $employee->employee_status == 'active' ? 'success' : 'danger' }}">
                                                            {{ ucfirst($employee->employee_status) }}
                                                        </span>
                                                        @else
                                                        <span class="badge bg-secondary">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="card-header">
                                                            <div class="card-actions float-center">
                                                                <div class="d-inline-block dropdown show">
                                                                    <a href="#" data-bs-toggle="dropdown"
                                                                        data-bs-display="static">
                                                                        <i class="align-middle" data-feather="more-vertical"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-center">
                                                                        @can('employee_show')
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('employee.show', $employee->id) }}">
                                                                            <i class="bi bi-eye"></i> Show
                                                                        </a>
                                                                        @endcan
                                                                        @can('employee_edit')
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('employee.edit', $employee->id) }}">
                                                                            <i class="bi bi-pencil-square"></i> Edit
                                                                        </a>
                                                                        @endcan
                                                                        @can('employee_trash')
                                                                        <form action="{{ route('employee.delete', $employee->id) }}"
                                                                            method="POST"
                                                                            onsubmit="return confirm('Are you sure you want to delete this Employee?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item text-danger">
                                                                                <i class="bi bi-trash"></i> Delete
                                                                            </button>
                                                                        </form>
                                                                        @endcan
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="primary-tab-2" role="tabpanel">
                                        <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    {{-- <th><input type="checkbox" id="select_all"></th> --}}
                                                    <th>S:NO</th>
                                                    <th>Name</th>
                                                    <th>Employee ID</th>
                                                    {{-- <th>Company</th> --}}
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employees as $employee)
                                                @if ($employee->employee_status === 'inactive')
                                                <tr>
                                                    {{-- <td>
                                            <input type="checkbox" name="employee_ids[]" class="row_checkbox"
                                                value="{{ $employee->id }}">
                                                    </td> --}}
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $employee->name ?? 'N/A' }}</td>
                                                    <td>{{ $employee->employee_id ?? 'N/A' }}</td>
                                                    <td>
                                                        @if (!empty($employee->employee_status))
                                                        <span
                                                            class="badge bg-{{ $employee->employee_status == 'active' ? 'success' : 'danger' }}">
                                                            {{ ucfirst($employee->employee_status) }}
                                                        </span>
                                                        @else
                                                        <span class="badge bg-secondary">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="card-header">
                                                            <div class="card-actions float-center">
                                                                <div class="d-inline-block dropdown show">
                                                                    <a href="#" data-bs-toggle="dropdown"
                                                                        data-bs-display="static">
                                                                        <i class="align-middle" data-feather="more-vertical"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-center">
                                                                        @can('employee_show')
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('employee.show', $employee->id) }}">
                                                                            <i class="bi bi-eye"></i> Show
                                                                        </a>
                                                                        @endcan
                                                                        @can('employee_edit')
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('employee.edit', $employee->id) }}">
                                                                            <i class="bi bi-pencil-square"></i> Edit
                                                                        </a>
                                                                        @endcan
                                                                        @can('employee_trash')
                                                                        <form action="{{ route('employee.delete', $employee->id) }}"
                                                                            method="POST"
                                                                            onsubmit="return confirm('Are you sure you want to delete this Employee?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item text-danger">
                                                                                <i class="bi bi-trash"></i> Delete
                                                                            </button>
                                                                        </form>
                                                                        @endcan
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            {{-- </form> --}}
        </div>
    </div>



</div>
<script>
    document.getElementById('select_all').addEventListener('click', function() {
        document.querySelectorAll('.row_checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
<script>
    document.getElementById('importForm').addEventListener('submit', function(e) {
        const employeeType = document.getElementById('employee_type').value;
        const form = this;

        if (employeeType === 'uae') {
            form.action = "{{ route('employees.import') }}";
        } else if (employeeType === 'uk') {
            form.action = "{{ route('employees.import.uk') }}";
        }
    });
</script>
@endsection
