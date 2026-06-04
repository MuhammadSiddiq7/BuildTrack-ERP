@extends('layout.master')
@section('title', 'Dashboard')
@section('header-title', 'Deleted Item')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.NO</th>
                            <th>Item Name</th>
                            <th>Size</th>
                            <th>Deno</th>
                            {{-- <th>Rate</th>
                            <th>Total Amount Per House</th> --}}
                            <th>Ststus</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->item }}</td>
                                <td>{{ $item->size }}</td>
                                <td>{{ $item->deno }}</td>
                                {{-- <td>{{ $item->rate }}</td>
                                <td>{{ $item->total_amount_per_house }}</td> --}}
                                <td>{{ $item->status ?? 'N/A' }}</td>
                                <td>
                                    @can('item_d_restore')
                                        <a href="{{ route('item.restore', $item->id) }}"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Are you sure you want to restore this item?')">
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
