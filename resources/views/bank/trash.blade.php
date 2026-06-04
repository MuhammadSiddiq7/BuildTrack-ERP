@extends('layout.master')
@section('title', 'Trashed Banks')
@section('header-title', 'Trashed Banks')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                            <thead class="{{ session('theme') === 'dark' ? 'table-dark' : 'table-light' }}">
                                <tr>
                                    <th>S.No</th>
                                    <th>Bank Name</th>
                                    <th>Branch</th>
                                    <th>Account No</th>
                                    <th>IBAN</th>
                                    {{-- <th>Edenred Exchange</th> --}}
                                    <th>Status</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($banks as $index => $bank)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $bank->name ?? 'N/A' }}</td>
                                        <td>{{ $bank->branch ?? 'N/A' }}</td>
                                        <td>{{ $bank->account_number ?? 'N/A' }}</td>
                                        <td>{{ $bank->iban ?? 'N/A' }}</td>
                                        {{-- <td>{{ $bank->edenred_exchange ?? 'N/A' }}</td> --}}
                                    <td>
                                        @if ($bank->status)
                                            <span class="badge bg-{{ $bank->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($bank->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                        <td>{{ $bank->deleted_at ? $bank->deleted_at->format('d M Y h:i A') : 'N/A' }}</td>
                                        <td>
                                            @can('bank_restore')
                                                <a href="{{ route('banks.restore', $bank->id) }}"
                                                    class="btn btn-sm btn-outline-success me-1"
                                                    onclick="return confirm('Are you sure you want to restore this bank?');">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                </a>
                                            @endcan
                                            @cannot('bank_restore')
                                                @cannot('bank_force_delete')
                                                    <span class="text-muted">N/A</span>
                                                @endcannot
                                            @endcannot
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
