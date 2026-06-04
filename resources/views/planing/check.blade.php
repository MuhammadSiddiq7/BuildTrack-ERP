@extends('layout.master')
@section('title', 'Planning Graph')
@section('header-title', 'Planning Graph')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header text-center">
                <h5 class="mb-0">TENTATIVE SITE EXECUTION PLAN / CONSTRUCTION TIMELINE</h5>
            </div>
            <div class="card flex-fill w-100">
                <div id="timelineChart"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ✅ Proper data encoding
    let data = @json($activities);

    console.log(data);

    let validItems = data.filter(item => item.start_date && item.finish_date);

    // ✅ Calculate min & max
    let allDates = [];
    validItems.forEach(item => {
        let start = new Date(item.start_date).getTime();
        let end = new Date(item.finish_date).getTime();
        if (!isNaN(start) && !isNaN(end)) {
            allDates.push(start, end);
        }
    });

    let minDate = Math.min(...allDates);
    let maxDate = Math.max(...allDates);

    let series = [{ data: [] }];

    validItems.forEach(item => {
        let start = new Date(item.start_date).getTime();
        let end = new Date(item.finish_date).getTime();
        if (isNaN(start) || isNaN(end)) return;

        let totalDuration = end - start;
        let progress = Number(item.progress || 0);
        let progressDuration = totalDuration * (progress / 100);
        let progressEnd = start + progressDuration;

        // ✅ Minimum red width for 0%
        let minVisible = totalDuration * 0.4;
        if (progress === 0) {
            progressEnd = start;
            if (end - progressEnd < minVisible) {
                end = progressEnd + minVisible;
            }
        }

        // ✅ Completed (green)
        if (progress > 0) {
            series[0].data.push({
                x: item.name,
                y: [start, progressEnd],
                fillColor: "#10B981",
                meta: { progress: progress, type: 'completed' }
            });
        }

        // ✅ Remaining (red)
        if (progress < 100) {
            let remaining = 100 - progress;
            series[0].data.push({
                x: item.name,
                y: [progressEnd, end],
                fillColor: "#EF4444",
                meta: { progress: remaining, type: 'remaining' }
            });
        }
    });

    var options = {
        chart: {
            type: 'rangeBar',
            height: 420,
            zoom: { enabled: false },
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '75%',
                borderRadius: 0
            }
        },
        xaxis: {
            type: 'datetime',
            min: minDate,
            max: maxDate,
            labels: {
                datetimeUTC: false,
                format: 'MMM yyyy'
            },
            title: { text: 'Timeline' }
        },
        tooltip: {
            custom: function({ seriesIndex, dataPointIndex, w }) {
                var point = w.config.series[seriesIndex].data[dataPointIndex];
                if (!point || !point.x || !point.y) return '';

                var orig = data.find(d => String(d.name) === String(point.x));
                var fragStart = point.y[0];
                var fragEnd = point.y[1];

                var startStr = fragStart ? new Date(fragStart).toLocaleDateString() : '-';
                var endStr = fragEnd ? new Date(fragEnd).toLocaleDateString() : '-';

                // ✅ Determine progress or remaining
                let progressLabel = point.meta.type === 'completed'
                    ? `Progress: ${point.meta.progress.toFixed(0)}%`
                    : `Remaining: ${point.meta.progress.toFixed(0)}%`;

                return `
                    <div style="padding:8px; max-width:320px;">
                        <div style="font-weight:600; margin-bottom:6px;">${point.x}</div>
                        <div style="font-size:13px;"><strong>${progressLabel}</strong></div>
                        <div style="font-size:13px;"><strong>Duration:</strong> ${startStr} — ${endStr}</div>
                    </div>
                `;
            }
        },
        grid: { borderColor: '#e0e0e0' },
        dataLabels: { enabled: false },
        series: series
    };

    new ApexCharts(document.querySelector("#timelineChart"), options).render();
});
</script>


@endsection
