@extends('layout.master')
@section('title', 'Dashboard')
@section('header-title', 'Welcome' . ' ' . Auth::user()->name)

@section('content')

    <style>
        .dashboard-card {
            border: none;
            border-radius: 1rem;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease-in-out;
            overflow: hidden;
            position: relative;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        /* Card Body */
        .dashboard-card-body {
            padding: 1.8rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-radius: 1rem;
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            position: relative;
            overflow: hidden;
        }

        .dashboard-card-body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.08), transparent 70%);
            z-index: 0;
        }

        .dashboard-card-body>* {
            position: relative;
            z-index: 1;
        }

        /* Icon Wrapper */
        .dashboard-icon-wrapper {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #fff;
            margin-right: 1rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .dashboard-card:hover .dashboard-icon-wrapper {
            transform: rotate(8deg) scale(1.1);
        }

        /* Different colors per card */
        .projects-icon {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
        }

        .contractors-icon {
            background: linear-gradient(135deg, #0ea5e9, #06b6d4);
        }

        .stockin-icon {
            background: linear-gradient(135deg, #059669, #10b981);
        }

        .stockout-icon {
            background: linear-gradient(135deg, #f59e0b, #f97316);
        }

        /* Title */
        .dashboard-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            transition: all 0.3s ease-in-out;
        }

        .dashboard-card:hover .dashboard-title {
            transform: scale(1.05);
        }

        /* Count */
        .dashboard-count {
            font-size: 2.4rem;
            font-weight: 800;
            margin-top: 1.2rem;
            background: linear-gradient(to right, #2563eb, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            display: inline-block;
        }

        .dashboard-count::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, #3b82f6, #10b981);
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .dashboard-card:hover .dashboard-count::after {
            opacity: 1;
        }
    </style>



    <div class="row">
        <div class="col-12 mb-5">
            <div class="row g-4">
                <!-- ✅ Total Projects -->
                <!-- Projects -->
                <div class="col-md-6 col-lg-3">
                    <a href="{{ route('projects.list') }}" class="text-decoration-none text-dark">
                        <div class="dashboard-card h-100">
                            <div class="card-body dashboard-card-body">
                                <div class="d-flex align-items-center">
                                    <div class="dashboard-icon-wrapper projects-icon">
                                        <i class="fas fa-project-diagram"></i>
                                    </div>
                                    <h5 class="dashboard-title">Total Projects</h5>
                                </div>
                                <h1 class="dashboard-count">{{ $projects_count }}</h1>
                            </div>
                        </div>
                    </a>
                </div>


                <!-- Contractors -->
                <div class="col-md-6 col-lg-3">
                    <div class="dashboard-card h-100">
                        <div class="card-body dashboard-card-body">
                            <div class="d-flex align-items-center">
                                <div class="dashboard-icon-wrapper contractors-icon">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <h5 class="dashboard-title">Total Contractors</h5>
                            </div>
                            <h1 class="dashboard-count">{{ $contractors_count }}</h1>
                        </div>
                    </div>
                </div>

                <!-- StockIn -->
                <div class="col-md-6 col-lg-3">
                    <div class="dashboard-card h-100">
                        <div class="card-body dashboard-card-body">
                            <div class="d-flex align-items-center">
                                <div class="dashboard-icon-wrapper stockin-icon">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h5 class="dashboard-title">Total StockIn Amount</h5>
                            </div>
                            <h1 class="dashboard-count">{{ $stockIn }}</h1>
                        </div>
                    </div>
                </div>

                <!-- StockOut -->
                <div class="col-md-6 col-lg-3">
                    <div class="dashboard-card h-100">
                        <div class="card-body dashboard-card-body">
                            <div class="d-flex align-items-center">
                                <div class="dashboard-icon-wrapper stockout-icon">
                                    <i class="fas fa-dolly-flatbed"></i>
                                </div>
                                <h5 class="dashboard-title">Total StockOut Amount</h5>
                            </div>
                            <h1 class="dashboard-count">{{ $stockOut }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAP -->
        <style>
            @keyframes pulse {
                0% {
                    transform: translate(-50%, -50%) scale(1);
                    opacity: 1;
                }

                70% {
                    transform: translate(-50%, -50%) scale(1.4);
                    opacity: 0.5;
                }

                100% {
                    transform: translate(-50%, -50%) scale(1);
                    opacity: 1;
                }
            }
        </style>

        {{-- <div class="col-lg-4 col-md-12 col-xxl-6 d-flex" style="width: fit-content;">

        </div> --}}



        <div class="col-lg-4 col-md-12 col-xxl-6 d-flex"style="width: fit-content;">

            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">NHS Mauripur</h5>
                </div>
                <div class="card-body px-4">
                    <div style="height:200px; position:relative;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3617.8166773499756!2d66.93158327414392!3d24.938314342140114!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb36bd3b2261489%3A0x35612833d47a4ada!2sNHS%20Mauripur!5e0!3m2!1sen!2s!4v1757575271678!5m2!1sen!2s"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>

                        <div
                            style=" position:absolute; top:45%; left:50%; transform:translate(-50%, -50%);
                        width:80px; height:80px; background:rgba(0, 123, 255, 0.9);
                        border:3px solid #fff; border-radius:50%; box-shadow:0 0 10px rgba(0,123,255,0.8);
                        animation:pulse 1.5s infinite; ">
                        </div>

                    </div>
                </div>
            </div>

            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">PNWHS Bin Qasim</h5>
                </div>
                <div class="card-body px-4">
                    <div style="height:200px; position:relative;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3045.354419147868!2d67.44412307364102!3d24.810613588126433!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x394cd26c1ba3a3c9%3A0x3380c537de91092b!2sPNWHS%20Bin%20Qasim!5e0!3m2!1sen!2s!4v1757575432977!5m2!1sen!2s"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>

                        <!-- Custom red circle marker -->
                        <div
                            style=" position:absolute; top:45%; left:50%; transform:translate(-50%, -50%);
                            width:80px; height:80px; background:rgba(220, 53, 69, 0.9);
                            border:3px solid #fff; border-radius:50%;
                            box-shadow:0 0 10px rgba(220,53,69,0.8); animation:pulse 1.5s infinite; ">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Anchorage Karachi</h5>
                </div>
                <div class="card-body px-4">
                    <div id="world_map" style="height:200px; position:relative;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7436.2863132796665!2d67.62596556145083!3d25.12572019814803!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x394cbb1c1b80e0fd%3A0x95f6e4375557fbf9!2sAnchorage%20Karachi!5e0!3m2!1sen!2s!4v1757575558345!5m2!1sen!2s"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>

                        <!-- Custom red circle marker -->
                        <div
                            style=" position:absolute; top:45%; left:50%; transform:translate(-50%, -50%);
                        width:80px; height:80px; background:rgba(40, 167, 69, 0.9); border:3px solid #fff;
                        border-radius:50%; box-shadow:0 0 10px rgba(40,167,69,0.8); animation:pulse 1.5s infinite; ">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- <div class="col-lg-4 col-md-12 col-xxl-6 d-flex"> --}}


    <!-- MAP -->
    </div>

    <div class="row">
        <div class="col-12 col-lg-8 col-xxl-9 d-flex">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Latest Projects</h5>
                </div>
                <table id="datatables-dashboard-projects" class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash"></i> S:NO</th>
                            <th><i class="bi bi-building"></i> Project Name</th>
                            <th><i class="bi bi-card-list"></i> Project Number</th>
                            <th><i class="bi bi-geo-alt"></i> Project Location</th>
                            <!-- <th><i class="bi bi-gear"></i> Action</th> -->

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $project->project_name ?? 'N/A' }}</td>
                                <td>{{ $project->project_number ?? 'N/A' }}</td>
                                <td>{{ $project->project_location ?? 'N/A' }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-12 col-md-4 col-xxl-3 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Project Budgets (Rs.)</h5>
                </div>
                <div class="card-body d-flex">
                    <div class="align-self-center w-100">
                        <div class="py-3">
                            <div class="chart chart-xs">
                                <canvas id="chartjs-dashboard-pie"></canvas>
                            </div>
                        </div>

                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-circle text-primary fa-fw"></i> 70 X DTH</td>
                                    <td class="text-end">1,504,656,426</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-circle text-warning fa-fw"></i> 301 Houses PNWHS</td>
                                    <td class="text-end">1,493,176,900</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-circle text-danger fa-fw"></i> 40 X DTH</td>
                                    <td class="text-end">799,750,960</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-circle text-danger fa-fw"></i> 60 X DTH</td>
                                    <td class="text-end">1,163,027,403</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Actual vs Planned</h5>

                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="apexcharts-column"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contractor Projects --}}
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contractor Projects</h5>
                </div>

                <table id="datatables-dashboard-projects" class="table table-striped my-0">
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
                        @foreach ($contractors as $contractor)
                            @php
                                $groupedProjects = $contractor->houseProjects->groupBy('project.project_name');
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $contractor->name ?? 'N/A' }}</td>
                                <td colspan="4"></td>
                            </tr>

                            @foreach ($groupedProjects as $projectName => $houseProjects)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $projectName ?? 'No Project' }}</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#houses-{{ $contractor->id }}-{{ \Illuminate\Support\Str::slug($projectName ?? 'no-project') }}"
                                            aria-expanded="false"
                                            aria-controls="houses-{{ $contractor->id }}-{{ \Illuminate\Support\Str::slug($projectName ?? 'no-project') }}">
                                            View Houses
                                        </button>
                                    </td>
                                    <td>{{ $contractor->description ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('contractor.view.demand', $contractor->id) }}"
                                            class="btn btn-sm btn-warning">
                                            View Demands
                                        </a>
                                    </td>
                                </tr>

                                <tr class="collapse"
                                    id="houses-{{ $contractor->id }}-{{ \Illuminate\Support\Str::slug($projectName ?? 'no-project') }}">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        {{-- <div class="row">
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
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var options = {
                    chart: {
                        height: 350,
                        type: "bar",
                        stacked: true,
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                        },
                    },
                    stroke: {
                        width: 1,
                        colors: ["#fff"]
                    },
                    series: [

                        {
                            name: "Total Allocated Stock",
                            data: [97, 87, 74, 89, 35, 41]
                        }, {
                            name: "Stock In",
                            data: [44, 55, 41, 37, 22, 43]
                        }, {
                            name: "Stock Out",
                            data: [53, 32, 33, 52, 13, 67]
                        }
                    ],
                    colors: ["#28a745", "#dc3545", "#007bff"],
                    xaxis: {
                        categories: ["Steel", "Cement", "Blocks", "Tiles", "Colour", "Termite"],
                    },
                    yaxis: {
                        title: {
                            text: undefined
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " units";
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    legend: {
                        position: "top",
                        horizontalAlign: "left",
                        offsetX: 40
                    }
                }

                var chart = new ApexCharts(document.querySelector("#apexcharts-bar"), options);
                chart.render();
            });
        </script> --}}

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

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Get data from Laravel
    var stockData = @json($stockData);

    // Extract categories (item types)
    var categories = Object.keys(stockData);

    // Map data for each type
    var dataIn = categories.map(item => Number(stockData[item]?.in || 0));
    var dataOut = categories.map(item => Number(stockData[item]?.out || 0));
    var dataTotal = categories.map(item => Number(stockData[item]?.total || 0));

    // Configure ApexChart
    var options = {
        chart: {
            height: 350,
            type: "bar",
            stacked: true,
        },
        plotOptions: {
            bar: {
                horizontal: true,
            },
        },
        stroke: {
            width: 1,
            colors: ["#fff"],
        },
        series: [
            { name: "Total Stock", data: dataTotal },
            { name: "Stock In", data: dataIn },
            { name: "Stock Out", data: dataOut },
        ],
        colors: ["#28a745", "#007bff", "#dc3545"], // Total=green, In=blue, Out=red
        xaxis: {
            categories: categories,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " units";
                },
            },
        },
        fill: { opacity: 1 },
        legend: {
            position: "top",
            horizontalAlign: "left",
            offsetX: 40,
        },
    };

    // Render chart
    var chart = new ApexCharts(document.querySelector("#apexcharts-bar"), options);
    chart.render();
});
</script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Column chart
                var options = {
                    chart: {
                        height: 350,
                        type: "bar",
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            endingShape: "rounded",
                            columnWidth: "55%",
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ["transparent"]
                    },
                    series: [{
                        name: "Planned",
                        data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
                    }, {
                        name: "Actual",
                        data: [20, 0, 0, 0, 0, 0, 0, 0, 0]
                    }, {
                        name: "Accumulated",
                        data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
                    }],
                    xaxis: {
                        categories: ["Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May"],
                    },
                    yaxis: {
                        title: {
                            text: "% (Site Progress)"
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return "$ " + val + " thousands"
                            }
                        }
                    }
                }
                var chart = new ApexCharts(
                    document.querySelector("#apexcharts-column"), options
                );
                chart.render();
            });
        </script>


    @endsection
