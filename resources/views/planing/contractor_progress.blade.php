@extends('layout.master')
@section('title', 'All Contractors Progress')
@section('header-title', 'All Contractors Progress Overview')

@section('content')

<div class="card flex-fill">
    <div class="card-header">
        <h5 class="card-title mb-0">Contractors Summary</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Contractor</th>
                    <th>Total Projects</th>
                    <th>Total Houses</th>
                    <th>Total Demands</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chartData as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data['name'] }}</td>
                        <td>{{ $data['projects'] }}</td>
                        <td>{{ $data['houses'] }}</td>
                        <td>{{ $data['demands'] }}</td>
                        <td>
                            <a href="{{ route('plan.contractor.progress.detail', $contractors[$index]->id) }}" 
                                class="btn btn-sm btn-primary">
                                View Progress
                                </a>


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ===================== CHART SECTION ===================== --}}
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Contractor Progress Chart</h5>
    </div>
    <div class="card-body">
        <div style="width: 100%; overflow-x: auto;">
            <canvas id="contractorProgressChart" height="120"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const data = @json($chartData);

        const labels = data.map(item => item.name);
        const projectCounts = data.map(item => item.projects);
        const houseCounts = data.map(item => item.houses);
        const demandCounts = data.map(item => item.demands);

        new Chart(document.getElementById('contractorProgressChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Projects',
                        data: projectCounts,
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Houses',
                        data: houseCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Demands',
                        data: demandCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'All Contractors Progress Overview' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>

@endsection
