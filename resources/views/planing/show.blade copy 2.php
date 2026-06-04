@extends('layout.master')
@section('title', 'Plan Details')
@section('header-title', 'Plan Details')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    {{-- <h4>Plan #{{ $plan->id }}</h4> --}}
                    <p class="text-center">YARDSTICK FOR PAYMENT OF IPC <br> CONSTRUCTION OF {{ $plan->house->house_number ?? 'N/A' }} x {{ $plan->type->house_type_id ?? 'N/A' }} AT NHS MAURIPUR</p>
                    {{-- <p></p> --}}
                </div>
                <div class="card-body">
                    {{-- <p><strong>Project:</strong> {{ $plan->project->project_name ?? 'N/A' }}</p>
                    <p><strong>Type:</strong> {{ $plan->type->house_type_id ?? 'N/A' }}</p>
                    <p><strong>House:</strong> {{ $plan->house->house_number ?? 'N/A' }}</p>
                    <p><strong>Contractor:</strong> {{ $plan->contractor->name ?? 'N/A' }}</p>

                    <h5 class="mt-4">📋 Activities</h5> --}}
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                {{-- <th>Activity Code</th> --}}
                                <th>S.NO</th>
                                <th>Description</th>
                                <th>% Yardstick</th>
                                <th>Amount</th>
                                {{-- <th>Start Date</th>
                                <th>Finish Date</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plan->planActivities as $pa)
                                <tr class="table fw-bold text-center">
                                    {{-- <td>{{ $pa->activity->activity_code ?? 'N/A' }}</td> --}}
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $pa->activity->name ?? 'N/A' }}</td>
                                    <td>{{ $pa->activity->yardstick ?? 'N/A' }}</td>
                                    {{-- <td>{{ $pa->start_date ?? 'N/A' }}</td>
                                    <td>{{ $pa->finish_date ?? 'N/A' }}</td>
                                    <td>{{ $pa->original ?? 'N/A' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $pa->status == 'approved' ? 'success' : ($pa->status == 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($pa->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($pa->status == 'pending' && auth()->user()->hasRole('Project Manager'))
                                            <form action="{{ route('activity.approve', $pa->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('activity.reject', $pa->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        @endif
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
