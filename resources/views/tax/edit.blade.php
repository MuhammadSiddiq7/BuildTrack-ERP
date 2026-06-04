@extends('layout.master')
@section('title', 'Edit Tax')
@section('header-title', 'Edit Tax')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                   <form method="POST" action="{{ route('tax.update', $slab) }}" class="row g-3">
                        @csrf @method('PUT')
                        @include('tax.form', ['slab'=>$slab])
                        <div class="col-12">
                            <button class="btn btn-primary">Update</button>
                            <a href="{{ route('tax.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
