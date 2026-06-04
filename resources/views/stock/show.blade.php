@extends('layout.master')
@section('title', 'Stock Check')
@section('header-title', 'Stock Check')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-3 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 d-flex align-items-center fw-bold">
                        <i class="bi bi-box-seam-fill me-2"></i> Stock Check Overview
                    </h5>
                    <span class="badge bg-light text-primary fs-6 px-3 py-2">
                        {{ $show->count() }} Items
                    </span>
                </div>
                <div class="bg-light py-3 px-4 border-bottom">
                    <form method="GET" action="{{ route('stock.check') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="bi bi-house-door-fill me-1"></i> Warehouse
                                </label>
                                <select name="warehouse_id" class="form-select shadow-sm" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">-- Select Warehouse --</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="bi bi-layers-fill me-1"></i> Item Type
                                </label>
                                <select name="type" class="form-select shadow-sm" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">-- Select Type --</option>
                                    @foreach ($types as $t)
                                        <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                                            {{ ucfirst($t) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="bi bi-building-fill-check me-1"></i> Project
                                </label>
                                <select name="project_id" class="form-select shadow-sm" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">-- Select Project --</option>
                                    @foreach ($projects as $project)
                                         <option value="{{ $project->id }}"
                                            {{ request()->has('project_id') && request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->project_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center table-bordered rounded shadow-sm" id="datatables-reponsive">
                            <thead class="table-primary text-dark fw-semibold">
                                <tr>
                                    <th><i class="bi bi-hash"></i> ID</th>
                                    <th><i class="bi bi-tag-fill"></i> Item</th>
                                    <th><i class="bi bi-box2-heart-fill"></i> Type</th>
                                    <th><i class="bi bi-building"></i> Project</th>
                                    <th><i class="bi bi-diagram-3"></i> Total Project QTY</th>
                                    <th><i class="bi bi-box-arrow-in-down"></i> Stock In</th>
                                    <th><i class="bi bi-box-arrow-up"></i> Stock Out</th>
                                    <th><i class="bi bi-box2-heart"></i> Balance Warehouse</th>
                                    <th><i class="bi bi-clipboard2-check"></i> Total Remaining</th>
                                    <th><i class="bi bi-graph-up-arrow"></i> Total Issued</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($show as $index => $row)
                                    <tr>
                                        <td class="fw-semibold">{{ $index + 1 }}</td>
                                        <td>{{ $row['item_name'] }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $row['item_type'] }}</span></td>
                                        <td>{{ $row['project_name'] }}</td>
                                        <td><span class="badge bg-dark">{{ $row['total_houses'] }}</span></td>
                                        <td><span class="badge bg-success">{{ $row['stock_in'] }}</span></td>
                                        <td><span class="badge bg-danger">{{ $row['stock_out'] }}</span></td>
                                        <td><span class="badge bg-secondary">{{ $row['total_stock'] }}</span></td>
                                        <td><span class="badge bg-primary">{{ $row['total_remaining'] }}</span></td>
                                        <td><span class="badge bg-warning text-dark">{{ $row['stock_issued'] }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light text-muted text-end py-3 px-4 border-top">
                    <small><i class="bi bi-info-circle"></i> Stock figures are updated in real-time.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(90deg, #007bff 0%, #0056d2 100%);
    }

    .table-hover tbody tr:hover {
        background-color: #f2f6ff;
        transition: 0.2s;
    }

    select.form-select {
        border-radius: 0.5rem;
    }

    .form-label {
        font-size: 0.9rem;
    }

    .badge {
        font-size: 0.85rem;
        padding: 6px 10px;
    }
</style>
@endsection
