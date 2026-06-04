@extends('layout.master')
@section('title', 'Create Tax')
@section('header-title', 'Create Tax')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                   <form method="POST" action="{{ route('tax.store') }}" class="row g-3">
                        @csrf
                        @include('tax.form')
                        <div class="col-12">
                            <button class="btn btn-primary">Save</button>
                            <a href="{{ route('tax.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
