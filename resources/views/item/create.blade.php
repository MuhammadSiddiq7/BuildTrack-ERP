@extends('layout.master')
@section('title', 'Create Item')
@section('header-title', 'Create Item')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="date"><b>Create Item Date</b><span class="text-danger">*</span></label>
                                    <input type="date" id="date" name="date" placeholder="Enter create item date"
                                        value="{{ old('date') }}" class="form-control">
                                    @error('date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="item"><b>Item Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="item" name="item" placeholder="Enter item name"
                                        value="{{ old('item') }}" class="form-control">
                                    @error('item')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="size"><b>Size</b></label>
                                    <input type="text" id="size" name="size" placeholder="Enter size"
                                        value="{{ old('size') }}" class="form-control">
                                    @error('size')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="items_type"><b>Item Type</b><span class="text-danger">*</span></label>
                                    <select name="items_type" id="items_type" class="form-control" required>
                                        <option value="" disabled selected>Select Type</option>
                                        <option value="ATH">ATH</option>
                                        <option value="BTH">BTH</option>
                                        <option value="CTH">CTH</option>
                                        <option value="DTH">DTH</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="deno"><b>Deno</b></label>
                                    <input type="text" id="deno" name="deno" placeholder="Enter deno"
                                        value="{{ old('deno') }}" class="form-control">
                                    @error('deno')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="per_house_qty"><b>Deno</b></label>
                                    <input type="text" id="per_house_qty" name="per_house_qty"
                                        placeholder="Enter per house qty" value="{{ old('per_house_qty') }}"
                                        class="form-control">
                                    @error('per_house_qty')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="specification"><b>Specification</b></label>
                                    <input type="text" id="specification" name="specification"
                                        placeholder="Enter specification" value="{{ old('specification') }}"
                                        class="form-control">
                                    @error('specification')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="specs"><b>Specs</b></label>
                                    <input type="text" id="specs" name="specs" placeholder="Enter specs"
                                        value="{{ old('specs') }}" class="form-control">
                                    @error('specs')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="qty"><b>Item Qty</b></label>
                                    <input type="text" id="qty" name="qty" placeholder="Enter Item Quantity"
                                        value="{{ old('qty') }}" class="form-control">
                                    @error('qty')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="rate"><b>Rate</b></label>
                                    <input type="text" id="rate" name="rate" placeholder="Enter rate"
                                        value="{{ old('rate') }}" class="form-control">
                                    @error('rate')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="total_amount_per_house"><b>Total Amount Per House</b></label>
                                    <input type="text" id="total_amount_per_house" name="total_amount_per_house" placeholder="Enter total amount per house"
                                        value="{{ old('total_amount_per_house') }}" class="form-control">
                                    @error('total_amount_per_house')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="warehouse_id"><b>Warehouse</b></label>
                                    <select name="warehouse_id" id="warehouse_id" class="form-control">
                                        <option value="">Select Warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="brand_id"><b>Brand</b></label>
                                    <select name="brand_id" id="brand_id" class="form-control">
                                        <option value="" selected disabled>Select Brand</option>
                                       @foreach ($brands as $brand)
                                       <option value="{{$brand->id}}">{{$brand->name}}</option>
                                       @endforeach
                                    </select>
                                    @error('brand_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="status"><b>Status</b></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary"><b>Save</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
