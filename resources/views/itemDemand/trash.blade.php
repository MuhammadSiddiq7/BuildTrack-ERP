@extends('layout.master')
@section('title', 'Dashboard')
@section('header-title', 'Deleted Item Demand')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.NO</th>
                            <th>Date</th>
                            <th>Project</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itemDemands as $itemDemand)
                            <tr>
                               <td>{{ $itemDemand->id }}</td>
                               <td>{{ $itemDemand->date }}</td>
                                <td>{{ $itemDemand->project->project_name ?? 'N/A' }}</td>
                                <td>{{ $itemDemand->creator->name ?? 'N/A' }}</td>
                                <td>
                                    @can('itemDemand_restore')
                                        <a href="{{ route('itemDemand.restore', $itemDemand->id) }}"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Are you sure you want to restore this itemDemand?')">
                                        Restore
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
