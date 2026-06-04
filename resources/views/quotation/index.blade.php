@extends('layout.master')
@section('title', 'Quotation')
@section('header-title', 'Quotation')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 ">
                        <i class="bi bi-truck me-2 text-primary"></i>Quotation List
                    </h5>

                    <div class="d-flex align-items-center gap-2">
                        {{-- @can('brand_trash_view')
                            <a href="{{ route('brand.trash') }}" class="btn btn-sm btn-outline-danger position-relative"
                                title="Deleted Users">
                                <i class="bi bi-trash-fill me-1"></i> Trash
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $trashuser ?? 0 }}
                                </span>
                            </a>
                        @endcan --}}
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatables-reponsive" class="table table-hover align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i> S:NO</th>
                                    <th><i class="bi bi-person-fill me-1"></i>Supplier Name</th>
                                    <th><i class="bi bi-card-text me-1"></i> Description</th>
                                    <th><i class="bi bi-card-text me-1"></i> Item</th>
                                    <th><i class="bi bi-telephone-fill me-1"></i> Rate</th>
                                    <th><i class="bi bi-toggle-on me-1"></i> Amount</th>
                                    {{-- <th><i class="bi bi-gear-fill me-1"></i> Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quotations as $quotation)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $quotation->supplier_name ?? 'N/A' }}</td>
                                        <td>{{ $quotation->description ?? 'N/A' }}</td>
                                        <td>{{ $quotation->item->item ?? 'N/A' }}</td>
                                        <td>{{ $quotation->rate ?? 'N/A' }}</td>
                                        <td>{{ $quotation->amount ?? 'N/A' }}</td>
                                        {{-- <td>
                                            @can('brand_edit')
                                                <a href="{{ route('quotation.edit', $quotation->id) }}"
                                                    class="btn btn-sm btn-primary me-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endcan

                                            @can('brand_trash')
                                                <form action="{{ route('quotation.delete', $quotation->id) }}" method="POST"
                                                    style="display:inline-block;"
                                                    onsubmit="return confirm('Are you sure you want to delete this quotation?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td> --}}
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
