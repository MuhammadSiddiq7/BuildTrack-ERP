@extends('layout.master')
@section('title', 'Create Supplier')
@section('header-title', 'Create Supplier')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('item.supplier.store', $item->id) }}" method="POST">
                        @csrf
                        <h5>Assign Supplier Rates for {{ $item->item }}</h5>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Purchase Price</th>
                                    <th>Date</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $supplier)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="suppliers[{{ $supplier->id }}][selected]">
                                        {{ $supplier->name }}
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="suppliers[{{ $supplier->id }}][purchase_price]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="date" name="suppliers[{{ $supplier->id }}][date]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="suppliers[{{ $supplier->id }}][remarks]" class="form-control">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary">Save Supplier Rates</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
