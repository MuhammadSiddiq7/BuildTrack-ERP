@extends('layout.master')
@section('title', 'Contractor Progress Detail')

@section('header-title')
    Contractor Progress for {{ $contractor->name ?? 'N/A' }}
@endsection

@section('content')
{{-- <pre>{{ json_encode($allActivities, JSON_PRETTY_PRINT) }}</pre> --}}
    
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Contractor Progress Chart</h5>
            <a href="{{ route('plan.index') }}" class="btn btn-sm btn-secondary">Back</a>
        </div>

        <div class="card-body">
            @if($plans->isEmpty())
                <p class="text-muted mb-0">No plans found for this contractor.</p>
            @else
                <div id="chartWrapper" style="overflow-x:auto; overflow-y:hidden; width:100%;">
                    <div id="timelineChart" style="min-width:1200px; height:500px;"></div>
                </div>
            @endif
        </div>
    </div>

    {{-- Contractor Houses & Plans Summary --}}
    @if(!$plans->isEmpty())
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">Contractor Houses & Plans Summary</h6>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>House</th>
                        <th>Total Activities</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $index => $plan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $plan->house->house_number ?? 'N/A' }}</td>
                            <td>{{ $plan->activities->count() }}</td>
                            <td>
                                {{ $plan->activities->min(fn($a) => $a->pivot->start_date) ?? '-' }}
                            </td>
                            <td>
                                {{ $plan->activities->max(fn($a) => $a->pivot->finish_date) ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

{{-- ApexCharts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div id="chartWrapper" style="overflow-x:auto; overflow-y:hidden; width:100%;">
    <div id="timelineChart" style="min-width:1000px; height:500px;"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const mergedActivities = @json($mergedActivities);

    if (!mergedActivities.length) {
        document.querySelector("#timelineChart").innerHTML =
            "<p class='text-danger text-center mt-3'>No activities found.</p>";
        return;
    }

    const seriesData = mergedActivities.map(item => ({
        x: `${item.activity_name} (${item.avg_progress || 0}%)`,
        y: [
            new Date(item.start_date).getTime(),
            new Date(item.end_date).getTime()
        ],
        progress: item.avg_progress || 0
    }));

    const options = {
        chart: {
            type: 'rangeBar',
            height: 500,
            toolbar: { show: true }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '70%'
            }
        },
        xaxis: { type: 'datetime' },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                const progress = opts.w.config.series[0].data[opts.dataPointIndex].progress;
                return progress ? `${progress}%` : '';
            },
            style: {
                colors: ['#000'],
                fontSize: '11px'
            }
        },
        tooltip: {
            y: {
                formatter: function (val, opts) {
                    const d = opts.w.config.series[0].data[opts.dataPointIndex];
                    const start = new Date(d.y[0]).toLocaleDateString();
                    const end = new Date(d.y[1]).toLocaleDateString();
                    return `Start: ${start}<br>End: ${end}<br>Avg Progress: ${d.progress}%`;
                }
            }
        },
        series: [{
            name: 'Activity Progress',
            data: seriesData
        }],
        colors: ['#1E90FF']
    };

    const chart = new ApexCharts(document.querySelector("#timelineChart"), options);
    chart.render();
});
</script>

@endsection
