@extends('layout.master')
@section('title', 'Tax')
@section('header-title', 'Tax')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @can('user_create')
                        <a href="{{ route('tax.create') }}" class="btn btn-primary">Create Tax</a>
                    @endcan

                    {{-- @can('user_trash_view')
                        <a href="{{ route('user.trash') }}" class="btn btn-danger d-flex align-items-center gap-2 ms-auto"
                            title="Deleted Tax">
                            <i class="bi bi-trash-fill"></i>
                            <span>Trash</span>
                            <span class="badge bg-light text-dark">{{ $trashuser ?? 0 }}</span>
                        </a>
                    @endcan --}}
                </div>

                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S:NO</th>
                                <th>Min Income</th>
                                <th>Max Income</th>
                                <th>Fixed Tax (Rs.)</th>
                                <th>Percent (%)</th>
                                <th>Formula</th>
                                <th width="140">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($taxes as $slab)
                                <tr>
                                    <td>{{ $slab->id }}</td>
                                    <td>{{ number_format($slab->min_income) }}</td>
                                    <td>{{ $slab->max_income ? number_format($slab->max_income) : 'Above' }}</td>
                                    <td>{{ number_format($slab->fixed_tax) }}</td>
                                    <td>{{ rtrim(rtrim(number_format($slab->percentage,2), '0'),'.') }}</td>
                                    <td>
                                        {{ $slab->fixed_tax > 0 ? 'Rs. '.number_format($slab->fixed_tax).' + ' : '' }}
                                        {{ rtrim(rtrim($slab->percentage, '0'),'.') }}% of (Income − {{ number_format($slab->min_income) }})
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-secondary" href="{{ route('tax.edit', $slab) }}">Edit</a>
                                        <form action="{{ route('tax.destroy', $slab) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Delete this tax?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">No slabs found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
