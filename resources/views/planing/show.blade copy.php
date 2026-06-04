@extends('layout.master')
@section('title', 'Plan Details')
@section('header-title', 'Plan Details')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4>Plan #{{ $plan->id }}</h4>
                </div>
                <div class="card-body">
                    <p><strong>Project:</strong> {{ $plan->project->project_name ?? 'N/A' }}</p>
                    <p><strong>Type:</strong> {{ $plan->type->house_type_id ?? 'N/A' }}</p>
                    <p><strong>House:</strong> {{ $plan->house->house_number ?? 'N/A' }}</p>

                    <p><strong>Project Manager:</strong>
                        @if ($plan->plan_manager_id)
                            {{ $plan->planManager->name ?? 'N/A' }}
                            @if ($plan->planManager->roles->count())
                                ({{ $plan->planManager->roles->pluck('name')->join(', ') }})
                            @endif
                        @else
                            N/A
                        @endif
                    </p>

                    <p><strong>Client:</strong> {{ $plan->plan_client_id ? $plan->planClient->name ?? 'N/A' : 'N/A' }}</p>

                    <p><strong>Contractor:</strong>
                        {{ $plan->plan_contractor_id ? $plan->planContractor->name ?? 'N/A' : 'N/A' }}</p>

                    {{-- Manager Section --}}
                    <h5 class="mt-4">Manager Decision</h5>
                    @if ($plan->manager_status == 'pending' && auth()->user()->hasRole('Project Manager'))
                        <form action="{{ route('plans.manager.approve', $plan->id) }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success">Approve (Manager)</button>
                        </form>
                        <form action="{{ route('plans.manager.reject', $plan->id) }}" method="POST"
                            style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-danger">Reject (Manager)</button>
                        </form>
                    @else
                        <span
                            class="badge bg-{{ $plan->manager_status == 'approved' ? 'success' : ($plan->manager_status == 'rejected' ? 'danger' : 'warning') }}">
                            Manager {{ ucfirst($plan->manager_status) }}
                        </span>
                    @endif

                    {{-- Client Section --}}
                    <h5 class="mt-4">Client Decision</h5>
                    @if ($plan->client_status == 'pending' && auth()->user()->hasRole('Client'))
                        <form action="{{ route('plans.client.approve', $plan->id) }}" method="POST"
                            style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success">Approve (Client)</button>
                        </form>
                        <form action="{{ route('plans.client.reject', $plan->id) }}" method="POST"
                            style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-danger">Reject (Client)</button>
                        </form>
                    @else
                        <span
                            class="badge bg-{{ $plan->client_status == 'approved' ? 'success' : ($plan->client_status == 'rejected' ? 'danger' : 'warning') }}">
                            Client {{ ucfirst($plan->client_status) }}
                        </span>
                    @endif

                    {{-- Contractor Section --}}
                    <h5 class="mt-4">Contractor Decision</h5>
                    @if ($plan->contractor_status == 'pending' && auth()->user()->is_contractor && auth()->id() == $plan->plan_contractor_id)
                        <form action="{{ route('plans.contractor.approve', $plan->id) }}" method="POST"
                            style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success">Approve (Contractor)</button>
                        </form>
                        <form action="{{ route('plans.contractor.reject', $plan->id) }}" method="POST"
                            style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-danger">Reject (Contractor)</button>
                        </form>
                    @else
                        <span
                            class="badge bg-{{ $plan->contractor_status == 'approved' ? 'success' : ($plan->contractor_status == 'rejected' ? 'danger' : 'warning') }}">
                            Contractor {{ ucfirst($plan->contractor_status) }}
                        </span>
                    @endif


                </div>
            </div>
        </div>
    </div>
@endsection
