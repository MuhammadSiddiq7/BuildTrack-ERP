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
    let houseId = "{{ request('house_id') }}";

    fetch(`/plan/house/${houseId}/activities-gantt`)
        .then(res => res.json())
        .then(data => {
            console.log(data);

            let validItems = data.filter(item => item.start_date && item.finish_date);

            // ✅ Calculate min & max for x-axis
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

                // ✅ Minimum red width (for 0% progress)
                let minVisible = totalDuration * 0.4; // 40% visible red bar
                if (progress === 0) {
                    progressEnd = start; // no green part
                    if (end - progressEnd < minVisible) {
                        end = progressEnd + minVisible; // extend red bar
                    }
                }

                // ✅ Completed (green)
                if (progress > 0) {
                    series[0].data.push({
                        x: item.name,
                        y: [start, progressEnd],
                        fillColor: "#10B981"
                    });
                }

                // ✅ Remaining (red)
                if (progress < 100) {
                    series[0].data.push({
                        x: item.name,
                        y: [progressEnd, end],
                        fillColor: "#EF4444"
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
                        borderRadius: 4
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
                        var progressStr = orig ? (Number(orig.progress || 0)).toFixed(0) + '%' : '0%';

                        return `
                            <div style="padding:8px; max-width:320px;">
                                <div style="font-weight:600; margin-bottom:6px;">${point.x}</div>
                                <div style="font-size:13px;"><strong>Progress:</strong> ${progressStr}</div>
                                <div style="font-size:13px;"><strong>Duration:</strong> ${startStr} — ${endStr}</div>
                            </div>
                        `;
                    }
                },
                grid: { borderColor: '#e0e0e0' },
                dataLabels: { enabled: false },
                series: series
            };

            var chart = new ApexCharts(document.querySelector("#timelineChart"), options);
            chart.render();
        })
        .catch(error => console.error('Error fetching activities:', error));
});
</script>

@endsection
