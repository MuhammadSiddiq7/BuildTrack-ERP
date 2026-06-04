@extends('layout.master')
@section('title', 'Item B Type Houses')
@section('header-title', 'Item B Type Houses')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                @can('item_b_import')
                   <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-upload me-1"></i> Import B Type Houses
                    </button>
                @endcan



                @can('item_b_trash_view')
                    <a href="{{ route('item.trash.b') }}"
                   class="btn btn-sm btn-outline-danger position-relative">
                        <i class="bi bi-trash-fill me-1"></i>
                        <span>Trash</span>
                       <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $trashuser ?? 0 }} </span>
                    </a>
                @endcan

         </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatables-reponsive" class="table table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash"></i> S.NO</th>
                                <th><i class="bi bi-box"></i> Item Name</th>
                                <th><i class="bi bi-aspect-ratio"></i> Size</th>
                                <th><i class="bi bi-grid-3x3-gap"></i> Deno</th>
                                <th><i class="bi bi-box-3x3-gap"></i> Qty Per House</th>
                                <th><i class="bi bi-box-3x3-gap"></i> Items Type</th>
                                <th><i class="bi bi-check2-circle"></i> Status</th>
                                <th><i class="bi bi-gear"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->item ?? 'N/A' }}</td>
                                    <td>{{ $item->size ?? 'N/A' }}</td>
                                    <td>{{ $item->deno ?? 'N/A' }}</td>
                                    <td>{{ $item->per_house_qty ?? 'N/A' }}</td>
                                    <td>{{ $item->items_type ?? 'N/A' }}</td>
                                    <td>
                                        @if ($item->status == 'active')
                                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Active</span>
                                        @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i> Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @can('item_edit')
                                            <a href="{{ route('item.edit', $item->id) }}"  class="btn btn-sm btn-primary me-1">
                                                    <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endcan

                                        @can('item_trash')
                                            <form action="{{ route('item.delete', $item->id) }}" method="POST"
                                                style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> <!-- /.table-responsive -->
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

      <form action="{{ route('items.import.b') }}" method="POST" enctype="multipart/form-data">
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

</div>
@endsection
