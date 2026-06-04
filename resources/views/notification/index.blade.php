@extends('layout.master')
@section('title', 'All Notifications')
@section('header-title', 'All Notifications')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">

                </div>
                <div class="card-body">
                    <div class="col-md-4">
                        <div class="float-end d-none d-md-block">
                            {{-- <form action="{{ route('notifications.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Clear All Notifications</button>
                            </form> --}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>S.NO</th>
                                            <th>Type</th>
                                            <th>Subject</th>
                                            <th>Message</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($notifications as $key => $notification)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ ucfirst($notification->type) }}</td>
                                                <td>{{ $notification->subject }}</td>
                                                <td>{{ Str::limit($notification->message, 50) }}</td>
                                                <td>
                                                    @if ($notification->pivot->is_read)
                                                        <span class="badge bg-success">Read</span>
                                                    @else
                                                        <span class="badge bg-warning">Unread</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!$notification->pivot->is_read)
                                                        <form action="{{ route('notifications.markRead') }}" method="POST"
                                                            style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $notification->id }}">
                                                            <input type="hidden" name="from" value="notifications">
                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-check"></i> Mark as Read
                                                            </button>
                                                        </form>
                                                    @endif

                                                        {{-- <form action="{{ route('notifications.delete', $notification->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Are you sure you want to delete this notification?');">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form> --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
