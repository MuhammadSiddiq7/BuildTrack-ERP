@extends('layout.master')
@section('title', 'Purchase Order')
@section('header-title', 'Purchase Order')

@section('content')
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">Purchase Order Items</h4>

                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PO Number</th>
                                <th>PO Date</th>
                                <th>PO Copy</th>
                                <th>DC Number</th>
                                <th>DC Copy</th>
                                <th>Contractor</th>
                                <th>Item</th>
                                  <th>Unit</th>
                                <th>Rate</th>
                                <th>Demanded Quantity</th>
                                <th>Received Quantity</th>
                                <th>Total Amount Demanded Qty</th>
                                <th>Total Amount Received Qty</th>

                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseOrders->items as $index => $order)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $purchaseOrders->po_number ?? 'N/A' }}</td>
                                    <td>{{ $purchaseOrders->po_date ?? 'N/A' }}</td>
                                    <td>
                                        @if (!empty($order->po_copy))
                                            <a href="{{ asset('storage/' . $order->po_copy) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $order->po_copy) }}" alt="DC Copy"
                                                    width="60" height="60"
                                                    style="object-fit: cover; border-radius: 5px;">
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $order->dc_number ?? 'N/A' }}</td>
                                    <td>
                                        @if (!empty($order->dc_copy))
                                            <a href="{{ asset('storage/' . $order->dc_copy) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $order->dc_copy) }}" alt="DC Copy"
                                                    width="60" height="60"
                                                    style="object-fit: cover; border-radius: 5px;">
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->itemDemand && $order->itemDemand->contractors->isNotEmpty())
                                            {{ $order->itemDemand->contractors->pluck('name')->unique()->join(', ') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $order->item->item ?? 'N/A' }}</td>
                                    <td>{{ $order->unit ?? 'N/A' }}</td>
                                    <td>{{ number_format($order->rate, 2) }}</td>
                                    <td>{{ $order->qty ?? 'N/A' }}</td>
                                    <td>{{ $order->received_qty ?? 'N/A' }}</td>
                                    <td>{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        @if(!is_null($order->received_qty) && !is_null($order->rate))
                                            {{ number_format($order->received_qty * $order->rate, 2) }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-M-Y') }}</td>
                                    <td>
                                        @if (empty($order->status) || $order->status != 'approved')
                                            @can('purchaseOrder_receive_item')
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#receiveModal" data-id="{{ $order->id }}"
                                                    data-item="{{ $order->item->item }}">
                                                    Receive
                                                </button>
                                            @endcan
                                        @else
                                            <span class="badge bg-success">Received</span>
                                        @endif
                                        @if (empty($order->po_copy) || $order->po_copy == 'N/A')
                                            @can('purchaseOrder_upload_pdf')
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#poModal" data-id="{{ $order->id }}"
                                                    data-item="{{ $order->item->item }}">
                                                    Upload
                                                </button>
                                            @endcan
                                        @else
                                            <span class="badge bg-success">Uploaded</span>
                                        @endif
                                        @can('purchaseOrder_view_pdf')
                                            <a href="{{ route('po.invoice', $order->id) }}" class="btn btn-sm btn-primary">
                                                View </a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Receive Modal -->
                    <div class="modal fade" id="receiveModal" tabindex="-1" aria-labelledby="receiveModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="receiveModalLabel">Receive Item</h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <form id="receiveForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="form_type" value="receive">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" id="order_id">

                                        <div class="mb-3">
                                            <label class="form-label">Item</label>
                                            <input type="text" id="item_name" class="form-control" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Received Quantity</label>
                                            <input type="number" min="0" step="0.01" class="form-control"
                                                name="received_qty" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">DC Number</label>
                                            <input type="text" class="form-control" name="dc_number">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">DC Copy</label>
                                            <input type="file" class="form-control" name="dc_copy" multiple>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light border"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Confirm Receive</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Upload PO Modal -->
                    <div class="modal fade" id="poModal" tabindex="-1" aria-labelledby="poModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="poModalLabel">Upload PO</h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <form id="receiveForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="form_type" value="po_upload">

                                    <div class="modal-body">
                                        <input type="hidden" name="id" id="order_id">
                                        <div class="mb-3">
                                            <label class="form-label">Upload PO Date</label>
                                            <input type="date" class="form-control" name="po_date">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">PO Copy</label>
                                            <input type="file" class="form-control" name="po_copy" multiple>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light border"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var receiveModal = document.getElementById('receiveModal');

            receiveModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;

                var id = button.getAttribute('data-id');
                var item = button.getAttribute('data-item');

                var form = receiveModal.querySelector('#receiveForm');
                form.action = "/purchaseOrder/po-items/" + id + "/receive";

                // Fill hidden + readonly fields
                receiveModal.querySelector('#order_id').value = id;
                receiveModal.querySelector('#item_name').value = item;
            });
            var poModal = document.getElementById('poModal');
            poModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var item = button.getAttribute('data-item');

                var form = poModal.querySelector('form'); // Select this modal's form
                form.action = "/purchaseOrder/po-items/" + id + "/receive";

                // Set hidden field (id)
                poModal.querySelector('#order_id').value = id;
            });
        });
    </script>


@endsection
