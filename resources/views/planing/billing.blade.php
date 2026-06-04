@extends('layout.master')
@section('title', 'Billing')
@section('header-title', 'Billing')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.NO</th>
                            <th>Contractor</th>
                            <th>Total Billings</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 1;
                            $modals = '';
                        @endphp
                        @foreach ($groupedBillings as $contractorId => $billings)
                            @php
                                $contractor = $billings->first()->planActivity->plan->contractor ?? null;
                            @endphp
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $contractor->name ?? 'N/A' }}</td>
                                <td>{{ $billings->count() }}</td>
                                <td>
                                    @can('billing_view')
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#billingModal-{{ $contractorId }}">
                                        View Billings
                                    </button>
                                    @endcan
                                </td>
                            </tr>
                            @php
                                $modals .= view('planing.billing_model', compact('contractor', 'contractorId', 'billings'))->render();
                            @endphp
                        @endforeach
                    </tbody>
                </table>
                {!! $modals !!}
            </div>
        </div>
    </div>
</div>

@endsection
