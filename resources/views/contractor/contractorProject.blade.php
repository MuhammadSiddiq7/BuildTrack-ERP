@extends('layout.master')
@section('title', 'Contractor Project')
@section('header-title', 'Contractor Project')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                <h4 class="mb-0">Contractor Project</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive rounded shadow-sm mt-3">
                    <table id="datatables-reponsive-CP" class="table table-hover align-middle text-center border">
                        <thead class="bg-light text-dark fw-bold border-bottom">
                            <tr>
                                <th class="text-uppercase small">S.NO</th>
                                <th class="text-uppercase small">Contract Number</th>
                                <th class="text-uppercase small">Contractor Name</th>
                                <th class="text-uppercase small">House Number</th>
                                <th class="text-uppercase small">Project Name</th>
                                <th class="text-uppercase small">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @php $i = 1; @endphp
                            @foreach ($projects as $project)
                            @foreach ($project->contractors as $contractor)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $contractor->pivot->contract_number ?? 'N/A' }}</td>
                                <td>{{ $contractor->name ?? 'N/A' }}</td>
                                <td>{{ $contractor->pivot->house_project_id ?? 'N/A' }}</td>
                                <td>{{ $project->project_name ?? 'N/A' }}</td>
                                <td>
                                    @can('contractorProject_status')
                                    <span class="badge status-toggle {{ $contractor->pivot->status == 'active' ? 'bg-success' : 'bg-danger' }}" style="cursor: pointer;" data-project-id="{{ $project->id }}" data-contractor-id="{{ $contractor->id }}" data-house-id="{{ $contractor->pivot->house_project_id }}" data-status="{{ $contractor->pivot->status }}">
                                        {{ ucfirst($contractor->pivot->status) }}
                                    </span>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $("#datatables-reponsive-CP").DataTable({
            pageLength: 20
            , lengthMenu: [
                [20, 40, 60, 80, 100]
                , [20, 40, 60, 80, 100]
            ]
            , responsive: true
        });

        $(document).on('click', '.status-toggle', function() {
            let el = $(this);
            let contractorId = el.data('contractor-id');
            let projectId = el.data('project-id');
            let houseId = el.data('house-id');
            let currentStatus = el.data('status');
            let newStatus = currentStatus === 'active' ? 'inactive' : 'active';

            Swal.fire({
                title: 'Are you sure?'
                , text: `You are about to mark this contractor as ${newStatus.toUpperCase()}.`
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonText: 'Yes, Change it!'
                , cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("contractor.toggleStatus") }}'
                        , method: 'POST'
                        , data: {
                            _token: '{{ csrf_token() }}'
                            , contractor_id: contractorId
                            , project_id: projectId
                            , house_project_id: houseId
                        }
                        , success: function(res) {
                            if (res.success) {
                                el.text(res.new_status.charAt(0).toUpperCase() + res.new_status.slice(1));
                                el.data('status', res.new_status);

                                if (res.new_status === 'active') {
                                    el.removeClass('bg-danger').addClass('bg-success');
                                } else {
                                    el.removeClass('bg-success').addClass('bg-danger');
                                }

                                Swal.fire({
                                    icon: 'success'
                                    , title: 'Status Updated!'
                                    , text: `Contractor status is now ${res.new_status.toUpperCase()}.`
                                    , timer: 1500
                                    , showConfirmButton: false
                                });
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            }
                        }
                        , error: function() {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });
    });

</script>


@endsection
