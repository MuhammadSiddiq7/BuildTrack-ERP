@extends('layout.master')
@section('title', 'Stock Show')
@section('header-title', 'Stock Show')
@section('content')
<div class="container-fluid p-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Item</th>
                                <th>Stock In</th>
                                <th>Stock Out</th>
                                <th>Total Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($show as $row)
                            <tr>
                                <td>{{ $row['id'] }}</td>
                                <td>{{ $row['item']->item }}</td>
                                <td>{{ $row['stock_in'] }}</td>
                                <td>{{ $row['stock_out'] }}</td>
                                <td>{{ $row['total_stock'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
