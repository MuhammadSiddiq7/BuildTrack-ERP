@extends('layout.master')
@section('title', 'Banks')
@section('header-title', 'Banks')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @can('bank_create')
                        <a href="{{ route('employee.bank.create') }}" class="btn btn-primary">Create Bank</a>
                    @endcan
                    @can('bank_trash_view')
                        <a href="{{ route('employee.bank.trash') }}" class="btn btn-danger d-flex align-items-center gap-2 ms-auto"
                            title="Trashed Banks">
                            <i class="bi bi-trash-fill"></i>
                            <span>Trash</span>
                            <span class="badge bg-light text-dark">{{ $trashCount ?? 0 }}</span>
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Bank Name</th>
                                <th>Account Title</th>
                                <th>Account No</th>
                                <th>IBAN</th>
                                {{-- <th>Edenred Exchange</th>
                                <th>Bank ID</th> --}}
                                {{-- <th>Status</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banks as $bank)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bank->bank_name ?? 'N/A' }}</td>
                                    <td>{{ $bank->account_title ?? 'N/A' }}</td>
                                    <td>{{ $bank->account_number ?? 'N/A' }}</td>
                                    <td>{{ $bank->iban ?? 'N/A' }}</td>
                                    {{-- <td>{{ $bank->edenred_exchange ?? 'N/A' }}</td>
                                    <td>{{ $bank->id ?? 'N/A' }}</td> --}}
                                    {{-- <td>
                                        @if ($bank->status)
                                            <span class="badge bg-{{ $bank->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($bank->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td> --}}
                                    <td>
                                        <div class="dropdown">
                                            <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                                                <i class="align-middle" data-feather="more-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @can('bank_edit')
                                                    <a class="dropdown-item" href="{{ route('employee.bank.edit', $bank->id) }}">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </a>
                                                @endcan
                                                @can('bank_trash')
                                                    <form action="{{ route('employee.bank.destroy', $bank->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this bank?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
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
