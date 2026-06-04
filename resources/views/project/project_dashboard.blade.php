@extends('layout.master')
@section('title', 'Project Dashboard')
@section('header-title', 'Project: ' . ($project->project_name ?? 'N/A'))

@section('content')

    <div class="container mt-4">

        {{-- Project Info Cards --}}
        <div class="row g-3 mb-4">
            @php
                $projectCards = [
                    ['title' => 'Project Name', 'value' => $project->project_name ?? 'N/A'],
                    ['title' => 'Project Number', 'value' => $project->project_number ?? 'N/A'],
                    ['title' => 'Bank', 'value' => $project->bank->name ?? 'N/A'],
                    ['title' => 'Total Houses', 'value' => $project->number_of_houses ?? 0],
                ];
            @endphp
            @foreach ($projectCards as $card)
                <div class="col-md-3">
                    <div class="card shadow-sm rounded-4 text-center p-3 h-100 border-0 hover-shadow">
                        <h6 class="text-muted mb-2">{{ $card['title'] }}</h6>
                        <p class="fw-semibold mb-0 fs-5">{{ $card['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Metrics --}}
        <div class="row g-3 mb-4">
            @php
                $metrics = [
                    [
                        'title' => 'Contractors',
                        'value' => $project->contractors->count() ?? 0,
                        'icon' => 'fas fa-users-cog',
                        'target' => 'contractors',
                    ],
                    [
                        'title' => 'Houses',
                        'value' => $houseProjects->sum('total_houses') ?? 0,
                        'icon' => 'fas fa-home',
                        'target' => 'houses',
                    ],
                    [
                        'title' => 'Items',
                        'value' => $houseProjects->sum(fn($hp) => count($hp->available_items ?? [])),
                        'icon' => 'fas fa-boxes',
                        'target' => 'items',
                    ],
                    [
                        'title' => 'Stock',
                        'value' => $stockIn ?? 0,
                        'icon' => 'fas fa-dolly-flatbed',
                        'target' => 'stock',
                    ],
                ];
            @endphp
            @foreach ($metrics as $metric)
                <div class="col-md-3">
                    <div class="card dashboard-card shadow-sm rounded-4 text-center p-3 h-100 cursor-pointer hover-shadow"
                        data-target="{{ $metric['target'] }}">
                        <div class="icon-wrapper mb-2">
                            <i class="{{ $metric['icon'] }} text-primary fs-2"></i>
                        </div>
                        <h6 class="text-muted mb-1">{{ $metric['title'] }}</h6>
                        <p class="fw-bold mb-0 fs-4">{{ $metric['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Contractor / Project Details --}}
        <div class="row g-3">
            <div class="col-12">
                <div class="card flex-fill">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Contractor Projects</h5>
                    </div>
                    <table class="table table-striped my-0">
                        <thead>
                            <tr>
                                <th>S.NO</th>
                                <th>Contractor</th>
                                <th>Project</th>
                                <th>Houses</th>
                                <th>Description</th>
                                <th>Demands</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($project->contractors as $contractor)
                                @php
                                    // ab yahan filter ki zarurat nahi, controller me ho gaya
                                    $groupedProjects = $contractor->houseProjects->groupBy(function ($hp) use (
                                        $contractor,
                                    ) {
                                        $projectName = $hp->project->project_name ?? 'No Project';
                                        $typeName = $hp->houseType->name ?? 'No Type';
                                        return $contractor->id . '||' . $projectName . '||' . $typeName;
                                    });
                                @endphp

                                {{-- Contractor Header Row --}}
                                <tr class="table-primary cursor-pointer" data-bs-toggle="collapse"
                                    data-bs-target="#contractor-{{ $contractor->id }}-{{ $loop->iteration }}"
                                    aria-expanded="false">
                                    <td>{{ $loop->iteration }}</td>
                                    <td colspan="5">{{ $contractor->name ?? 'N/A' }}</td>
                                </tr>

                                {{-- Collapsible Content --}}
                                <tr class="collapse" id="contractor-{{ $contractor->id }}-{{ $loop->iteration }}">
                                    <td colspan="6">
                                        <table class="table mb-0">
                                            @foreach ($groupedProjects as $composite => $houseProjects)
                                                @php
                                                    [$contractorId, $projectName, $typeName] = explode(
                                                        '||',
                                                        $composite . '||||',
                                                    );
                                                    // unique slug taake collapse conflict na ho
                                                    $slug = \Illuminate\Support\Str::slug(
                                                        $contractorId .
                                                            '-' .
                                                            $projectName .
                                                            '-' .
                                                            $typeName .
                                                            '-' .
                                                            $loop->iteration,
                                                    );
                                                @endphp

                                                <tr class="table-light">
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <span class="badge bg-info text-dark">{{ $projectName }}</span>
                                                        <span class="badge bg-secondary ms-1">{{ $typeName }}</span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#houses-{{ $slug }}"
                                                            aria-expanded="false">
                                                            View Houses
                                                        </button>
                                                    </td>
                                                    <td>{{ $contractor->description ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('contractor.demand.dashboard', $contractor->id) }}"
                                                            class="btn btn-sm btn-warning">View Demands</a>
                                                    </td>
                                                </tr>

                                                {{-- Houses --}}
                                                <tr class="collapse" id="houses-{{ $slug }}">
                                                    <td colspan="6">
                                                        <div class="row g-2">
                                                            @foreach ($houseProjects as $houseProject)
                                                                @foreach ($houseProject->houses as $house)
                                                                    <div class="col-auto">
                                                                        <span class="badge bg-secondary p-2">
                                                                            House {{ $house->house_number }}
                                                                        </span>
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
         
       <div class="row">
    <div class="col-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Total Stock</h5>
                <h6 class="card-subtitle text-muted">
                    Compare stock inflow, outflow, and total stock across different items.
                </h6>
            </div>
            <div class="card-body">
                <div class="chart">
                    <div id="apexcharts-bar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <script>
document.addEventListener("DOMContentLoaded", function() {
    var stockData = @json($stockData);
    var categories = Object.keys(stockData);
    var dataIn = categories.map(i => stockData[i]?.in || 0);
    var dataOut = categories.map(i => stockData[i]?.out || 0);
    var dataTotal = categories.map(i => stockData[i]?.total || 0);

    var options = {
        chart: { height: 350, type: "bar", stacked: true },
        plotOptions: { bar: { horizontal: true } },
        stroke: { width: 1, colors: ["#fff"] },
        series: [
            { name: "Total Stock", data: dataTotal },
            { name: "Stock In", data: dataIn },
            { name: "Stock Out", data: dataOut },
        ],
        colors: ["#28a745", "#007bff", "#dc3545"],
        xaxis: { categories },
        legend: { position: "top", horizontalAlign: "left" },
    };

    new ApexCharts(document.querySelector("#apexcharts-bar"), options).render();
});
</script> --}}


<script>
document.addEventListener("DOMContentLoaded", function() {
    var stockData = @json($stockData);

    var categories = Object.keys(stockData);
    var dataIn = categories.map(i => Math.abs(Number(stockData[i]?.in || 0)));
    var dataOut = categories.map(i => Math.abs(Number(stockData[i]?.out || 0)));
    var dataTotal = categories.map(i => Math.abs(Number(stockData[i]?.total || 0)));

    // ✅ Ensure small values still have visible bars
    function addVisualPadding(data, minWidth = 100) {
        let maxValue = Math.max(...data);
        return data.map(v => {
            if (v === 0) return 0;
            // scale small values slightly upward for visibility
            let adjusted = v < maxValue * 0.1 ? v + minWidth : v;
            return adjusted;
        });
    }

    var dataTotalAdjusted = addVisualPadding(dataTotal);
    var dataInAdjusted = addVisualPadding(dataIn);
    var dataOutAdjusted = addVisualPadding(dataOut);

    var options = {
        chart: {
            height: 400,
            type: "bar",
            stacked: true,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: "60%",
                borderRadius: 5,
            },
        },
        series: [
            { name: "Total Stock", data: dataTotalAdjusted },
            { name: "Stock In", data: dataInAdjusted },
            { name: "Stock Out", data: dataOutAdjusted },
        ],
        colors: ["#28a745", "#007bff", "#dc3545"],
        xaxis: {
            categories: categories,
            labels: { style: { fontSize: '13px' } }
        },
        tooltip: {
            y: { formatter: val => val + " Units" }
        },
        dataLabels: {
            enabled: true,
            position: 'right',
            style: { fontSize: '12px', colors: ['#000'] },
            formatter: function(val, opts) {
                let realValue = [dataTotal, dataIn, dataOut][opts.seriesIndex][opts.dataPointIndex];
                return realValue.toFixed(1);
            }
        },
        legend: {
            position: "top",
            horizontalAlign: "center",
        },
        fill: { opacity: 1 },
        grid: {
            xaxis: { lines: { show: true } },
            padding: { left: 20 } // ✅ adds breathing space for small bars
        }
    };

    var chart = new ApexCharts(document.querySelector("#apexcharts-bar"), options);
    chart.render();
});
</script>








    </div>

@endsection
