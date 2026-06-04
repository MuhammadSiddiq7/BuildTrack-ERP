@extends('layout.master')
@section('title', 'Edit Item')
@section('header-title', 'Edit Item')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('item.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="item"><b>Item Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="item" name="item" value="{{ $item->item }}"
                                        placeholder="Enter item Name" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="size"><b>Size</b></label>
                                    <input type="text" id="size" name="size" value="{{ $item->size }}"
                                        placeholder="Enter size" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="deno"><b>Deno</b></label>
                                    <input type="text" id="deno" name="deno" value="{{ $item->deno }}"
                                        placeholder="Enter deno" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="per_house_qty"><b>Qty Per House</b></label>
                                    <input type="text" id="per_house_qty" name="per_house_qty" value="{{ $item->per_house_qty }}"
                                        placeholder="Enter Qty per house" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="specification"><b>Specification</b></label>
                                    <input type="text" id="specification" name="specification" placeholder="Enter specification"
                                        value="{{ $item->specification }}" class="form-control">
                                    @error('specification')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="status"><b>Status</b></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="" disabled>Select Status</option>
                                        <option value="active" {{ $item->status == 'active' ? 'selected' : '' }}>Active </option>
                                        <option value="inactive" {{ $item->status == 'inactive' ? 'selected' : '' }}> Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
