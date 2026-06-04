@extends('layout.master')
@section('title', 'Item Demand')
@section('header-title', 'Item Demand')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Stock In Table -->
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 ">
                            <i class="bi bi-clipboard-check me-2 text-primary"></i>Item Demands List
                        </h5>

                        <div class="d-flex align-items-center gap-2">
                            @can('itemDemand_create')
                                <a href="{{ route('itemDemand.create') }}" id="showFormBtn" class="btn btn-sm btn-success">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    <span>Add Demand</span>
                                </a>
                            @endcan

                            @can('itemDemand_trash_view')
                                <a href="{{ route('itemDemand.trash') }}"
                                    class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1 position-relative"
                                    title="Deleted Items">
                                    <i class="bi bi-trash-fill"></i>
                                    <span>Trash</span>
                                    <span class="badge bg-light text-dark">{{ $trashItemDemand ?? 0 }}</span>
                                </a>
                            @endcan

                        </div>
                    </div>

                    <div class="card-body">
                        <table id="datatables-reponsive" class="table table-hover table-borderless">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-hash"></i> S.NO</th>
                                    <th><i class="bi bi-calendar3"></i> Demand No</th>
                                    <th><i class="bi bi-calendar3"></i> Date</th>
                                    <th><i class="bi bi-diagram-3"></i> Project</th>
                                    {{-- <th><i class="bi bi-person"></i>Contractor Link</th> --}}
                                    <th><i class="bi bi-gear"></i> Details</th>
                                    <th><i class="bi bi-gear"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ItemDemands as $itemDemand)
                                    @php
                                        $hasActivePO = \App\Models\ComparativeStatement::where(
                                            'item_demand_id',
                                            $itemDemand->id,
                                        )
                                            ->where('status', 'active')
                                            ->exists();
                                        $hasQuotation = \App\Models\Quotation::where(
                                            'item_demand_id',
                                            $itemDemand->id,
                                        )->exists();
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $itemDemand->demand_no ?? '-' }}</td>
                                        <td>{{ $itemDemand->date ?? '-' }}</td>
                                        <td>{{ $itemDemand->project->project_name ?? '-' }}</td>
                                        {{-- <td> <a href="{{ $itemDemand->public_token ? route('demand.receive', $itemDemand->public_token) : '#' }}">Contractor Link</a></td> --}}

                                        <!-- Action Buttons Section -->
                                        {{-- btns old <td class="d-flex gap-1 flex-wrap">
                                            @if ($hasQuotation)
                                                @can('quotation_view')
                                                    <a href="{{ route('quotation.show', $itemDemand->id) }}"
                                                        class="btn btn-sm btn-info d-flex align-items-center gap-1"
                                                        title="View Quotation">
                                                        <i class="bi bi-eye-fill me-2"></i> <span>View Quotation</span>
                                                    </a>
                                                @endcan
                                            @else
                                                @can('quotation_create')
                                                    <a href="{{ route('quotation.create', $itemDemand->id) }}"
                                                        class="btn btn-sm btn-dark d-flex align-items-center gap-1"
                                                        title="Create Quotation">
                                                        <i class="bi bi-bar-chart-line-fill me-2"></i> <span>Quotation</span>
                                                    </a>
                                                @endcan
                                            @endif
                                            @can('itemDemand_view_button')
                                                <a href="{{ route('itemDemand.view', $itemDemand->id) }}"
                                                    class="btn btn-sm btn-success d-flex align-items-center gap-1"
                                                    title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                            @can('itemDemand_issue')
                                                <a href="{{ route('itemDemand.issue', $itemDemand->id) }}"
                                                    class="btn btn-sm btn-success d-flex align-items-center gap-1"
                                                    title="View">
                                                    <i class="bi bi-issues"></i>Issue
                                                </a>
                                            @endcan
                                            @can('purchaseOrder_view_button')
                                                <a href="{{ route('direct.purchaseOrder.create', $itemDemand->id) }}"
                                                    class="btn btn-sm btn-dark d-flex align-items-center gap-1" title="View">
                                                    <i class="bi bi-issues"></i>PO
                                                </a>
                                            @endcan
                                            
                                        </td> --}}

                                        <td class="align-middle text-center">
                                            <div class="d-flex flex-wrap justify-content-center gap-2">

                                                @if ($hasQuotation)
                                                    @can('quotation_view')
                                                        <a href="{{ route('quotation.show', $itemDemand->id) }}"
                                                            class="btn btn-sm btn-info d-flex align-items-center gap-1"
                                                            title="View Quotation">
                                                            <i class="bi bi-eye-fill"></i> <span>Quotation</span>
                                                        </a>
                                                    @endcan
                                                @else
                                                    @can('quotation_create')
                                                        <a href="{{ route('quotation.create', $itemDemand->id) }}"
                                                            class="btn btn-sm btn-dark d-flex align-items-center gap-1"
                                                            title="Create Quotation">
                                                            <i class="bi bi-bar-chart-line-fill"></i> <span>Quotation</span>
                                                        </a>
                                                    @endcan
                                                @endif

                                                @can('itemDemand_view_button')
                                                    <a href="{{ route('itemDemand.view', $itemDemand->id) }}"
                                                        class="btn btn-sm btn-success d-flex align-items-center gap-1"
                                                        title="View">
                                                        <i class="bi bi-eye"></i>View
                                                    </a>
                                                @endcan

                                                @can('itemDemand_issue')
                                                    <a href="{{ route('itemDemand.issue', $itemDemand->id) }}"
                                                        class="btn btn-sm btn-success d-flex align-items-center gap-1"
                                                        title="Issue">
                                                        <i class="bi bi-clipboard-check"></i> Issue
                                                    </a>
                                                @endcan

                                                @can('purchaseOrder_view_button')
                                                    <a href="{{ route('direct.purchaseOrder.create', $itemDemand->id) }}"
                                                        class="btn btn-sm btn-dark d-flex align-items-center gap-1"
                                                        title="Purchase Order">
                                                        <i class="bi bi-cart-check"></i> PO
                                                    </a>
                                                @endcan

                                            </div>
                                        </td>

                                        <!-- Approval Section btns -->

                                        {{-- <td>
                                            <div class="card-header p-0 m-0">
                                                <div class="card-actions float-end">
                                                    <div class="d-inline-block dropdown show">
                                                        <a href="#" data-bs-toggle="dropdown"
                                                            data-bs-display="static">
                                                            <i class="align-middle" data-feather="more-vertical"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end p-2"
                                                            style="min-width: 180px;">
                                                            <!-- PM Button -->
                                                            <button type="button"
                                                                class="btn btn-sm w-100 rounded-2 shadow-sm mb-2
                                                                @if ($itemDemand->approved_by_pm == 1) btn-success
                                                                @elseif($itemDemand->approved_by_pm == -1) btn-danger
                                                                @else btn-outline-secondary @endif"
                                                                @if (auth()->user()->department != 'Project-Manager' || $itemDemand->approved_by_pm != 0) disabled @endif
                                                                data-id="{{ $itemDemand->id }}" data-dept="pm"
                                                                data-bs-toggle="modal" data-bs-target="#approveModal">
                                                                @if ($itemDemand->approved_by_pm == 1)
                                                                    Project Manager Approved
                                                                @elseif($itemDemand->approved_by_pm == -1)
                                                                    Project Manager Rejected
                                                                @else
                                                                    Project Manager Decision
                                                                @endif
                                                            </button>

                                                            <!-- SM Button -->
                                                            <button type="button"
                                                                class="btn btn-sm w-100 rounded-2 shadow-sm mb-2
                                                                @if ($itemDemand->approved_by_sm == 1) btn-success
                                                                @elseif($itemDemand->approved_by_sm == -1) btn-danger
                                                                @else btn-outline-secondary @endif"
                                                                @if (auth()->user()->department != 'Store-Manager' || $itemDemand->approved_by_pm != 1 || $itemDemand->approved_by_sm != 0 || $itemDemand->approved_by_pm == -1) disabled @endif
                                                                data-id="{{ $itemDemand->id }}" data-dept="sm"
                                                                data-bs-toggle="modal" data-bs-target="#approveModal">
                                                                @if ($itemDemand->approved_by_sm == 1)
                                                                    Store Manager Approved
                                                                @elseif($itemDemand->approved_by_sm == -1)
                                                                    Store Manager Rejected
                                                                @else
                                                                    Store Manager Decision
                                                                @endif
                                                            </button>

                                                            <!-- MOP Button -->
                                                            <button type="button"
                                                                class="btn btn-sm w-100 rounded-2 shadow-sm
                                                                @if ($itemDemand->approved_by_mp == 1) btn-success
                                                                @elseif($itemDemand->approved_by_mp == -1) btn-danger
                                                                @else btn-outline-secondary @endif"
                                                                @if (auth()->user()->department != 'Manager-of-Procurement' || $itemDemand->approved_by_mp != 0 || $itemDemand->approved_by_pm != 1 || $itemDemand->approved_by_sm != 1) disabled @endif
                                                                data-id="{{ $itemDemand->id }}" data-dept="mop"
                                                                data-bs-toggle="modal" data-bs-target="#approveModal">
                                                                @if ($itemDemand->approved_by_mp == 1)
                                                                    Procurement Manager Approved
                                                                @elseif($itemDemand->approved_by_mp == -1)
                                                                    Procurement Manager Rejected
                                                                @else
                                                                    Procurement Manager Decision
                                                                @endif
                                                            </button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td> --}}
                                        <td class="text-center align-middle">
                                            <div class="d-flex flex-wrap justify-content-center gap-2">

                                                <!-- PM -->
                                                <button type="button"
                                                    class="btn btn-sm rounded-2 shadow-sm 
                                                    @if ($itemDemand->approved_by_pm == 1) btn-success
                                                    @elseif($itemDemand->approved_by_pm == -1) btn-danger
                                                    @else btn-outline-secondary @endif"
                                                    @if (auth()->user()->department != 'Project-Manager' || $itemDemand->approved_by_pm != 0) disabled @endif
                                                    data-id="{{ $itemDemand->id }}" data-dept="pm" data-bs-toggle="modal"
                                                    data-bs-target="#approveModal">
                                                    @if ($itemDemand->approved_by_pm == 1)
                                                        PM ✓
                                                    @elseif($itemDemand->approved_by_pm == -1)
                                                        PM ✗
                                                    @else
                                                        PM
                                                    @endif
                                                </button>

                                                <!-- SM -->
                                                <button type="button"
                                                    class="btn btn-sm rounded-2 shadow-sm
                                                    @if ($itemDemand->approved_by_sm == 1) btn-success
                                                    @elseif($itemDemand->approved_by_sm == -1) btn-danger
                                                    @else btn-outline-secondary @endif"
                                                    @if (auth()->user()->department != 'Store-Manager' ||
                                                            $itemDemand->approved_by_pm != 1 ||
                                                            $itemDemand->approved_by_sm != 0 ||
                                                            $itemDemand->approved_by_pm == -1) disabled @endif
                                                    data-id="{{ $itemDemand->id }}" data-dept="sm" data-bs-toggle="modal"
                                                    data-bs-target="#approveModal">
                                                    @if ($itemDemand->approved_by_sm == 1)
                                                        SM ✓
                                                    @elseif($itemDemand->approved_by_sm == -1)
                                                        SM ✗
                                                    @else
                                                        SM
                                                    @endif
                                                </button>

                                                <!-- MOP -->
                                                <button type="button"
                                                    class="btn btn-sm rounded-2 shadow-sm
                                                    @if ($itemDemand->approved_by_mp == 1) btn-success
                                                    @elseif($itemDemand->approved_by_mp == -1) btn-danger
                                                    @else btn-outline-secondary @endif"
                                                    @if (auth()->user()->department != 'Manager-of-Procurement' ||
                                                            $itemDemand->approved_by_mp != 0 ||
                                                            $itemDemand->approved_by_pm != 1 ||
                                                            $itemDemand->approved_by_sm != 1) disabled @endif
                                                    data-id="{{ $itemDemand->id }}" data-dept="mop" data-bs-toggle="modal"
                                                    data-bs-target="#approveModal">
                                                    @if ($itemDemand->approved_by_mp == 1)
                                                        MOP ✓
                                                    @elseif($itemDemand->approved_by_mp == -1)
                                                        MOP ✗
                                                    @else
                                                        MOP
                                                    @endif
                                                </button>

                                            </div>
                                        </td>
                                        <style>
                                            td .btn {
                                                min-width: 70px;
                                                font-weight: 600;
                                                letter-spacing: 0.3px;
                                                transition: all 0.2s ease-in-out;
                                            }

                                            td .btn:hover:not(:disabled) {
                                                transform: translateY(-2px);
                                            }
                                        </style>




                                        <!-- Approval Modal -->
                                        <div class="modal fade" id="approveModal" tabindex="-1"
                                            aria-labelledby="modalTitle" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="POST" id="approvalForm">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalTitle">Confirm Decision</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="remarks" class="form-label">Remarks
                                                                    (optional)
                                                                </label>
                                                                <textarea class="form-control" name="remarks" id="remarks" rows="3" placeholder="Write remarks here..."></textarea>
                                                            </div>

                                                            <div class="mb-3">
                                                                <button type="submit" name="action" value="reject"
                                                                    class="btn btn-danger">Reject</button>
                                                                <button type="submit" name="action" value="approve"
                                                                    class="btn btn-success">Approve</button>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        const approvalModal = document.getElementById('approveModal');
        approvalModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const leaveId = button.getAttribute('data-id');
            const dept = button.getAttribute('data-dept');

            const baseUrl = @json(route('demand.approve', '__id__'));
            document.getElementById('approvalForm').action = baseUrl.replace('__id__', leaveId);
            const titleMap = {
                pm: 'PM Decision',
                sm: 'SM Decision',
                mop: 'MOP Decision'
            };
            document.getElementById('modalTitle').innerText = titleMap[dept] || 'Confirm Decision';
        });
        approvalModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('remarks').value = '';
        });
    </script>
@endsection
