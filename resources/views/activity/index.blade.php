@extends('layout.master')
@section('title', 'Activities')
@section('header-title', 'Activities')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                @can('activity_create')
                <a href="{{ route('activity.create') }}" class="btn btn-primary">Create Activity</a>
                 {{-- <button class="btn btn-md btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-upload me-1"></i> Import Item
                    </button> --}}
                @endcan

                @can('activity_trash_view')
                <a href="{{ route('activity.trash') }}" class="btn btn-danger d-flex align-items-center gap-2 ms-auto"
                    title="Deleted Users">
                    <i class="bi bi-trash-fill"></i>
                    <span>Trash</span>
                    <span class="badge bg-light text-dark">{{ $trashuser ?? 0 }}</span>
                </a>
                @endcan
            </div>

            <div class="card-body">
                <table id="datatables-reponsive" class="table " style="width:100%">
                    <thead>
                        <tr>
                            <th>S:NO</th>
                            <th>Activity</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Yardstick</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                     <tbody>
                        @foreach ($activities as $activity)
                            <tr style="background-color: {{ $activity->parent ? '#ffffff' : '#ffffb3' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $activity->activity_code ?? 'N/A' }}</td>
                                <td>{{ $activity->name ?? 'N/A' }}</td>
                                <td>{{ $activity->parent ? $activity->parent->name : 'N/A' }}</td>
                                <td>{{ $activity->yardstick ?? 'N/A' }}</td>
                                <td>
                                    @if ($activity->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                     @can('activity_edit')
                                                <a href="{{ route('activity.edit', $activity->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                </a>
                                            @endcan
                                    @can('activity_trash')
                                    <form action="{{ route('activity.delete', $activity->id) }}" method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>

                            @foreach ($activity->children as $child)
                                <tr style="background-color: #f9f9f9;">
                                    <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                                    <td>{{ $child->activity_code ?? 'N/A' }}</td>
                                    <td>{{ $child->name ?? 'N/A' }}</td>
                                    <td>{{ $child->parent->name ?? 'N/A' }}</td>
                                    <td>{{ $child->yardstick ?? 'N/A' }}</td>
                                    <td>
                                        @if ($child->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @can('activity_edit')
                                                <a href="{{ route('activity.edit', $child->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                </a>
                                            @endcan
                                        @can('activity_trash')
                                        <form action="{{ route('activity.delete', $child->id) }}" method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Are you sure you want to delete this ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @foreach ($child->children as $subChild)
                                    <tr style="background-color: #f1f1f1;">
                                        <td>{{ $loop->parent->parent->iteration }}.{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                                        <td>{{ $subChild->activity_code ?? 'N/A' }}</td>
                                        <td>{{ $subChild->name ?? 'N/A' }}</td>
                                        <td>{{ $subChild->parent->name ?? 'N/A' }}</td>
                                        <td>{{ $subChild->yardstick ?? 'N/A' }}</td>
                                        <td>
                                            @if ($subChild->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                            @else
                                            <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('activity_trash')
                                            <form action="{{ route('activity.delete', $subChild->id) }}" method="POST"
                                                style="display:inline-block;"
                                                onsubmit="return confirm('Are you sure you want to delete this ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="importModalLabel"><i class="bi bi-upload me-2"></i> Import Items</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('activities.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="mb-3">
                <label for="importFile" class="form-label">Choose Excel File (.xlsx, .xls, .csv)</label>
                <input type="file" name="file" id="importFile" class="form-control" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Import</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
