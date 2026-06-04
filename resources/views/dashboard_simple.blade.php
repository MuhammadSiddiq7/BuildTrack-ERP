@extends('layout.master')
@section('title', 'Dashboard')
@section('header-title', 'Welcome')

@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Projects -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Projects</h5>
                    <h2 class="fw-bold text-primary">{{ $projects_count }}</h2>
                </div>
            </div>
        </div>

        <!-- Contractors -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Contractors</h5>
                    <h2 class="fw-bold text-success">{{ $contractors_count }}</h2>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Items</h5>
                    <h2 class="fw-bold text-warning">{{ $items }}</h2>
                </div>
            </div>
        </div>

        <!-- Demands -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Demands</h5>
                    <h2 class="fw-bold text-danger">{{ $itemDemand }}</h2>
                </div>
            </div>
        </div>

        <!-- Stock In -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Stock In</h5>
                    <h2 class="fw-bold text-info">{{ $stockIn }}</h2>
                </div>
            </div>
        </div>

        <!-- Stock Out -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Stock Out</h5>
                    <h2 class="fw-bold text-secondary">{{ $stockOut }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
