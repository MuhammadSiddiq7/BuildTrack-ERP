@extends('layout.master')
@section('title', 'Activity ')
@section('header-title', 'Plan Details')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4 class="mb-0">YARDSTICK FOR PAYMENT OF IPC</h4>
                    <h4>Contractor: <strong>{{ $plan->contractor->name ?? 'N/A' }}</strong></h4>
                    <div class="d-flex justify-content-center">
                        <h5>House Number: {{ $plan->house->house_number ?? 'N/A' }}</h5>,
                        <h5 class="ms-2">House Type: {{ $plan->type->house_type_id ?? 'N/A' }}</h5>,
                        <h5 class="ms-2">Project: {{ $plan->project->project_name ?? 'N/A' }}</h5>
                    </div>
                </div>

                <div class="card-body">
                    <table id="datatables-reponsive" class="table " style="width:100%">
                        <thead class="table text-center">
                            <tr>
                                <th style="width: 60px;">S.No</th>
                                <th>Description</th>
                                <th>% Yardstick</th>
                                <th>Amount</th>
                                <th>PM</th>
                                <th>Consultant</th>
                                <th>Client</th>
                                <th>Project Manager</th>
                                <th>Planning Engineer</th>
                                <th>CEO Progress</th>
                                <th>Billing</th>
                                <th>View IR</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($plan->planActivities->whereNull('activity.parent_id') as $pa)
                                @php
                                    $hasChildren = $plan->planActivities->where('activity.parent_id', $pa->activity->id)->count() > 0;
                                @endphp
                                <tr class="text-center" style="background-color: #ffffb3;">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pa->activity->name ?? 'N/A' }}</td>
                                    <td>{{ $pa->activity->yardstick ?? 'N/A' }}</td>
                                    <td>{{ $pa->amount ?? 'N/A' }}</td>
                                    @if ($hasChildren)
                                    <td class="text-muted">
                                        <em>-</em>
                                    </td>
                                    <td class="text-muted">
                                        <em>-</em>
                                    </td>
                                    <td class="text-muted">
                                        <em>-</em>
                                    </td>
                                    <td class="text-muted">
                                        <em>-</em>
                                    </td>
                                    <td class="text-muted">
                                        <em>-</em>
                                    </td>
                                    <td class="text-muted">
                                        <em>-</em>
                                    </td>
                                    <td class="text-muted">
                                        <em>-</em>
                                    </td>
                                    <td class="text-muted">
                                        <em>-</em>
                                    </td>
                                    @else
                                    <!-- PM -->
                                    <td>
                                        @if (
                                            $pa->manager_status == 'pending' &&
                                                (auth()->user()->hasRole('Project Manager') || auth()->user()->hasRole('Admin')))
                                            <form action="{{ route('planActivities.manager.approve', $pa->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('planActivities.manager.reject', $pa->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span
                                                class="badge bg-{{ $pa->manager_status == 'approved' ? 'success' : ($pa->manager_status == 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($pa->manager_status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Consultant -->
                                    <td>
                                        @if (
                                            $pa->consultant_status == 'pending' &&
                                                (auth()->user()->hasRole('Consultant') || auth()->user()->hasRole('Admin')))
                                            <form action="{{ route('planActivities.consultant.approve', $pa->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('planActivities.consultant.reject', $pa->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span
                                                class="badge bg-{{ $pa->consultant_status == 'approved' ? 'success' : ($pa->consultant_status == 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($pa->consultant_status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Client -->
                                    <td>
                                        @if ($pa->client_status == 'pending' && (auth()->user()->hasRole('Client') || auth()->user()->hasRole('Admin')))
                                            <form action="{{ route('planActivities.client.approve', $pa->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('planActivities.client.reject', $pa->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span
                                                class="badge bg-{{ $pa->client_status == 'approved' ? 'success' : ($pa->client_status == 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($pa->client_status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <!-- PM -->
                                    <td>
                                        @can('project_manager_progress_update')
                                        @if (auth()->user()->hasAnyRole(['Admin', 'Project Manager']))
                                            <select class="form-select progress-dropdown" data-id="{{ $pa->id }}"
                                                data-field="project_manager_progress">
                                                @foreach (['0', '25', '50', '75', '100'] as $val)
                                                    <option value="{{ $val }}"
                                                        {{ $pa->project_manager_progress == $val ? 'selected' : '' }}>
                                                        {{ $val }}%
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <span>{{ $pa->project_manager_progress }}%</span>
                                        @endif
                                        @endcan
                                    </td>
                                    <!-- Planning Engineer -->
                                    <td>
                                        @can('planning_engineer_progress_update')
                                        @if (auth()->user()->hasAnyRole(['Admin', 'Planning Engineer']))
                                            <select class="form-select progress-dropdown" data-id="{{ $pa->id }}"
                                                data-field="planning_engineer_progress">
                                                @foreach (['0', '25', '50', '75', '100'] as $val)
                                                    <option value="{{ $val }}"
                                                        {{ $pa->planning_engineer_progress == $val ? 'selected' : '' }}>
                                                        {{ $val }}%
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <span>{{ $pa->planning_engineer_progress }}%</span>
                                        @endif
                                        @endcan
                                    </td>
                                    <!--CEO -->
                                    <td>
                                        @can('ceo_progress_update')
                                            @if (auth()->user()->hasAnyRole(['Admin', 'CEO']))
                                            <input type="number" class="form-control progress-input" data-id="{{ $pa->id }}"
                                            data-field="ceo_progress" value="{{ $pa->ceo_progress }}" min="0" max="100" step="0.01">

                                            @else
                                                <span>{{ $pa->ceo_progress }}%</span>
                                            @endif
                                        @endcan
                                    </td>
                                    <td>
                                        @can('planning_billing_request')
                                        <form action="{{ route('billing.request', $pa->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary"
                                                {{ $pa->ceo_progress <= $pa->billingRequests->sum('percentage') ? 'disabled' : '' }}>
                                                Request Billing
                                            </button>
                                        </form>
                                        @endcan
                                        @can('planning_billing_view')
                                        <a href="{{ route('billing.index') }}" class="btn btn-sm btn-info">View Billing</a>
                                        @endcan
                                    </td>
                                    <td>
                                        @php
                                            $hasIR = \App\Models\InspectionReport::where('plan_activity_id', $pa->id)->exists();
                                        @endphp
                                        @if ($hasIR)
                                            <a href="{{ route('inspection.report.show', $pa->id) }}" class="btn btn-sm btn-info">
                                                View IR
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">No Report</span>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                                @foreach ($plan->planActivities->where('activity.parent_id', $pa->activity->id) as $child)
                                    <tr class="text-center" style="background-color: #f9f9f9;">
                                        <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                                        <td>↳ {{ $child->activity->name ?? 'N/A' }}</td>
                                        <td>{{ $child->activity->yardstick ?? 'N/A' }}</td>
                                        <td>{{ $child->amount ?? 'N/A' }}</td>

                                        <!-- PM -->
                                        <td>
                                            @if ($child->manager_status == 'pending' && (auth()->user()->hasRole('Project Manager') || auth()->user()->hasRole('Admin')))
                                                <form action="{{ route('planActivities.manager.approve', $child->id) }}" method="POST" style="display:inline;">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('planActivities.manager.reject', $child->id) }}" method="POST" style="display:inline;">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-{{ $child->manager_status == 'approved' ? 'success' : ($child->manager_status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($child->manager_status) }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Consultant -->
                                        <td>
                                            @if ($child->consultant_status == 'pending' && (auth()->user()->hasRole('Consultant') || auth()->user()->hasRole('Admin')))
                                                <form action="{{ route('planActivities.consultant.approve', $child->id) }}" method="POST" style="display:inline;">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                                </form>
                                                <form action="{{ route('planActivities.consultant.reject', $child->id) }}" method="POST" style="display:inline;">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i></button>
                                                </form>
                                            @else
                                                <span class="badge bg-{{ $child->consultant_status == 'approved' ? 'success' : ($child->consultant_status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($child->consultant_status) }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Client -->
                                        <td>
                                            @if ($child->client_status == 'pending' && (auth()->user()->hasRole('Client') || auth()->user()->hasRole('Admin')))
                                                <form action="{{ route('planActivities.client.approve', $child->id) }}" method="POST" style="display:inline;">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                                </form>
                                                <form action="{{ route('planActivities.client.reject', $child->id) }}" method="POST" style="display:inline;">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i></button>
                                                </form>
                                            @else
                                                <span class="badge bg-{{ $child->client_status == 'approved' ? 'success' : ($child->client_status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($child->client_status) }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Project Manager Progress -->
                                        <td>
                                            @can('project_manager_progress_update')
                                                @if (auth()->user()->hasAnyRole(['Admin', 'Project Manager']))
                                                    <select class="form-select progress-dropdown" data-id="{{ $child->id }}" data-field="project_manager_progress">
                                                        @foreach (['0', '25', '50', '75', '100'] as $val)
                                                            <option value="{{ $val }}" {{ $child->project_manager_progress == $val ? 'selected' : '' }}>
                                                                {{ $val }}%
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <span>{{ $child->project_manager_progress }}%</span>
                                                @endif
                                            @endcan
                                        </td>

                                        <!-- Planning Engineer Progress -->
                                        <td>
                                            @can('planning_engineer_progress_update')
                                                @if (auth()->user()->hasAnyRole(['Admin', 'Planning Engineer']))
                                                    <select class="form-select progress-dropdown" data-id="{{ $child->id }}" data-field="planning_engineer_progress">
                                                        @foreach (['0', '25', '50', '75', '100'] as $val)
                                                            <option value="{{ $val }}" {{ $child->planning_engineer_progress == $val ? 'selected' : '' }}>
                                                                {{ $val }}%
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <span>{{ $child->planning_engineer_progress }}%</span>
                                                @endif
                                            @endcan
                                        </td>

                                        <!-- CEO Progress -->
                                        <td>
                                            @can('ceo_progress_update')
                                                @if (auth()->user()->hasAnyRole(['Admin', 'CEO']))
                                                    <input type="number" class="form-control progress-input"
                                                        data-id="{{ $child->id }}" data-field="ceo_progress"
                                                        value="{{ $child->ceo_progress }}" min="0" max="100" step="0.01">
                                                @else
                                                    <span>{{ $child->ceo_progress }}%</span>
                                                @endif
                                            @endcan
                                        </td>

                                        <!-- Billing -->
                                        <td>
                                            @can('planning_billing_request')
                                                <form action="{{ route('billing.request', $child->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary"
                                                        {{ $child->ceo_progress <= $child->billingRequests->sum('percentage') ? 'disabled' : '' }}>
                                                        Request Billing
                                                    </button>
                                                </form>
                                            @endcan
                                            @can('planning_billing_view')
                                                <a href="{{ route('billing.index') }}" class="btn btn-sm btn-info">View Billing</a>
                                            @endcan
                                        </td>

                                        <!-- IR -->
                                        <td>
                                            @php
                                                $hasIR = \App\Models\InspectionReport::where('plan_activity_id', $child->id)->exists();
                                            @endphp
                                            @if ($hasIR)
                                                <a href="{{ route('inspection.report.show', $child->id) }}" class="btn btn-sm btn-info">View IR</a>
                                            @else
                                                <span class="badge bg-secondary">No Report</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach

                            @endforeach

                        </tbody>
                    </table>

                    <div class="mt-3">
                        <a href="{{ route('plan.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Plans
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.progress-dropdown, .progress-input').forEach(input => {
            input.addEventListener('change', function() {
                let planActivityId = this.dataset.id;
                let field = this.dataset.field;
                let value = this.value;
                if (value < 0 || value > 100) {
                    alert('Please enter a value between 0 and 100.');
                    return;
                }

                fetch("{{ url('/plan-activities') }}/" + planActivityId + "/progress", {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            field,
                            value
                        })
                    })
                    .then(async res => {
                        let data = await res.json();
                        if (!res.ok) {
                            alert(data.error || "Something went wrong!");
                            location.reload();
                        } else {
                            alert(data.success);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Request failed!");
                    });
            });
        });

    </script>
@endsection
