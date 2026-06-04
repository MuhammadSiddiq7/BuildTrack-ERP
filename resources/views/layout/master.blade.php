<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Anchor Development and Construction Company ADCC ">
    <meta name="author" content="Bootlab">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/adcc.png') }}" />
    <title>Anchor Development and Construction Company - @yield('title')</title>
    <link href="{{ asset('assets/css/modern.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/classic.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dark.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/light.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    {{-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"> --}}
    <style>
        /* Sidebar Base */
        .sidebar {
            background-color: #f1f1f1;
            min-height: 100vh;
            padding-top: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: width 0.3s ease;
            border-radius: 20px;
            overflow-x: hidden;
        }

        /* Sidebar Width */
        .sidebar-collapsed {
            width: 80px !important;
        }

        .sidebar-expanded {
            width: 240px !important;
        }

        /* Sidebar Items */
        .sidebar-nav .sidebar-item>a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            color: #002C5F;
            font-weight: 500;
            font-size: 15px;
            border-radius: 8px;
            transition: background 0.3s, color 0.3s;
            white-space: nowrap;
            overflow: hidden;
        }

        /* Icons */
        .sidebar-nav .sidebar-item i {
            font-size: 18px;
            margin-right: 10px;
            color: #0056b3;
            transition: color 0.3s ease;
        }

        /* Hover + Active Styles */
        .sidebar-nav .sidebar-item>a:hover,
        .sidebar-nav .sidebar-item.active>a {
            background-color: #e9f1fb;
            color: #002C5F;
        }

        .sidebar-nav .sidebar-item>a:hover i,
        .sidebar-nav .sidebar-item.active>a i {
            color: #002C5F;
        }

        /* Submenus */
        .sidebar-dropdown {
            padding-left: 1.5rem;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.4s ease;
        }

        .sidebar-dropdown.show {
            max-height: 500px;
            /* Adjust as needed */
        }

        .sidebar-dropdown .sidebar-link {
            padding: 0.5rem 1.25rem;
            display: block;
            color: #444;
            font-size: 14px;
            border-radius: 6px;
            transition: background 0.3s, color 0.3s;
        }

        .sidebar-dropdown .sidebar-link:hover {
            background-color: #dbefff;
            color: #002C5F;
        }


        /* Sidebar Toggle Button */
        .transition-icon {
            transition: transform 1.5s ease;
            font-size: 1.3rem;
            color: #ffffff;
        }

        .transition-icon.rotate {
            transform: rotate(90deg);
        }

        .sidebar {
            background-color: #f1f1f1;
            color: #000;
            min-height: 100vh;
            padding-top: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: width 0.3s ease;
            border-radius: 20px;
        }

        .sidebar-collapsed {
            width: 80px !important;
        }

        .sidebar-expanded {
            width: 240px !important;

        }

        /* + Add Over & Above start */
        .over-above-section {
            transition: all 0.4s ease-in-out;
            border-radius: 10px;
            border-left: 5px solid #0d6efd;
            background-color: #f0f8ff;
        }

        .over-above-section input {
            border: 1px solid #0d6efd;
        }

        /* + Add Over & Above end */

    </style>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-120946860-7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-120946860-7');

    </script>
</head>
<body>
    <div class="splash active">
        <div class="splash-icon"></div>
    </div>
    <div class="wrapper">
        @include('layout.sidebar')
        <div class="main">
            @include('layout.header')
            <main class="content">
                <div class="container-fluid">
                    <div class="header">
                        <h1 class="header-title">
                            @yield('header-title')
                        </h1>
                        <p class="header-subtitle"> @yield('header-subtitle')
                        </p>
                    </div>
                    @yield('content')
                   @if (session('success'))
                        <div class="alert alert-success alert-dismissible shadow"
                            style="position: fixed; right: 30px; top: 70px; width: auto; min-width: 250px; max-width: 400px; z-index: 9999;"
                            role="alert">
                            <div class="alert-message" style="margin-right: 30px">
                                <strong>Success!</strong> {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible" style="position: fixed; right: 30px; top: 70px" role="alert">
                        <div class="alert-message" style="margin-right: 30px">
                            <strong>Error!</strong> {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible" style="position: fixed; right: 30px; top: 70px" role="alert">
                        <div class="alert-message" style="margin-right: 30px">
                            <strong>Warning!</strong> {{ session('warning') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                </div>
            </main>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-8 text-start">
                            <ul class="list-inline">
                                <li class="list-inline-item">Developed by
                                    <a class="text-muted" style="color: #2fa09c !important" target="_blank" href="https://synergyintegratedsolutions.pk/">Synergy Integrated Solutions</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-4 text-end">
                            <p class="mb-0">
                                &copy; 2025 - <a class='text-muted' href='#'>Anchor Development and Construction
                                    Company</a>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <svg width="0" height="0" style="position:absolute">
        <defs>
            <symbol viewBox="0 0 512 512" id="ion-ios-pulse-strong">
                <path d="M448 273.001c-21.27 0-39.296 13.999-45.596 32.999h-38.857l-28.361-85.417a15.999 15.999 0 0 0-15.183-10.956c-.112 0-.224 0-.335.004a15.997 15.997 0 0 0-15.049 11.588l-44.484 155.262-52.353-314.108C206.535 54.893 200.333 48 192 48s-13.693 5.776-15.525 13.135L115.496 306H16v31.999h112c7.348 0 13.75-5.003 15.525-12.134l45.368-182.177 51.324 307.94c1.229 7.377 7.397 11.92 14.864 12.344.308.018.614.028.919.028 7.097 0 13.406-3.701 15.381-10.594l49.744-173.617 15.689 47.252A16.001 16.001 0 0 0 352 337.999h51.108C409.973 355.999 427.477 369 448 369c26.511 0 48-22.492 48-49 0-26.509-21.489-46.999-48-46.999z">
                </path>
            </symbol>
        </defs>
    </svg>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select2
            $(".select2").each(function() {
                $(this)
                    .wrap("<div class=\"position-relative\"></div>")
                    .select2({
                        placeholder: "Select value"
                        , dropdownParent: $(this).parent()
                    });
            })
            // Daterangepicker
            $("input[name=\"daterange\"]").daterangepicker({
                opens: "left"
            });
            $("input[name=\"datetimes\"]").daterangepicker({
                timePicker: true
                , opens: "left"
                , startDate: moment().startOf("hour")
                , endDate: moment().startOf("hour").add(32, "hour")
                , locale: {
                    format: "M/DD hh:mm A"
                }
            });
            $("input[name=\"datesingle\"]").daterangepicker({
                singleDatePicker: true
                , showDropdowns: true
            });
            var start = moment().subtract(29, "days");
            var end = moment();

            function cb(start, end) {
                $("#reportrange span").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
            }
            $("#reportrange").daterangepicker({
                startDate: start
                , endDate: end
                , ranges: {
                    "Today": [moment(), moment()]
                    , "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")]
                    , "Last 7 Days": [moment().subtract(6, "days"), moment()]
                    , "Last 30 Days": [moment().subtract(29, "days"), moment()]
                    , "This Month": [moment().startOf("month"), moment().endOf("month")]
                    , "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1
                        , "month").endOf("month")]
                }
            }, cb);
            cb(start, end);
            // Datetimepicker
            $('#datetimepicker-minimum').datetimepicker();
            $('#datetimepicker-view-mode').datetimepicker({
                viewMode: 'years'
            });
            $('#datetimepicker-time').datetimepicker({
                format: 'LT'
            });
            $('#datetimepicker-date').datetimepicker({
                format: 'L'
            });
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const toggleIcon = document.getElementById('sidebarToggleIcon');
            const sidebar = document.querySelector('.sidebar');

            // Add default expanded class
            sidebar.classList.add('sidebar-expanded');

            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-collapsed');
                sidebar.classList.toggle('sidebar-expanded');
                toggleIcon.classList.toggle('rotate');
            });
        });

    </script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Line chart
            new Chart(document.getElementById("chartjs-dashboard-line"), {
                type: 'line'
                , data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov"
                        , "Dec"
                    ]
                    , datasets: [{
                            label: "Orders"
                            , fill: true
                            , backgroundColor: window.theme.primary
                            , borderColor: window.theme.primary
                            , borderWidth: 2
                            , data: [3, 2, 3, 5, 6, 5, 4, 6, 9, 10, 8, 9]
                        }
                        , {
                            label: "Sales ($)"
                            , fill: true
                            , backgroundColor: "rgba(0, 0, 0, 0.05)"
                            , borderColor: "rgba(0, 0, 0, 0.05)"
                            , borderWidth: 2
                            , data: [5, 4, 10, 15, 16, 12, 10, 13, 20, 22, 18, 20]
                        }
                    ]
                }
                , options: {
                    maintainAspectRatio: false
                    , legend: {
                        display: false
                    }
                    , tooltips: {
                        intersect: false
                    }
                    , hover: {
                        intersect: true
                    }
                    , plugins: {
                        filler: {
                            propagate: false
                        }
                    }
                    , elements: {
                        point: {
                            radius: 0
                        }
                    }
                    , scales: {
                        xAxes: [{
                            reverse: true
                            , gridLines: {
                                color: "rgba(0,0,0,0.0)"
                            }
                        }]
                        , yAxes: [{
                            ticks: {
                                stepSize: 5
                            }
                            , display: true
                            , gridLines: {
                                color: "rgba(0,0,0,0)"
                                , fontColor: "#fff"
                            }
                        }]
                    }
                }
            });
        });

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Pie chart
            new Chart(document.getElementById("chartjs-dashboard-pie"), {
                type: 'pie'
                , data: {
                    labels: ["70 X DTH", "301 Houses PNWHS", "40 X DTH", "60 X DTH"]
                    , datasets: [{
                        data: [1504656426, 1493176900, 799750960, 1163027403]
                        , backgroundColor: [
                            window.theme.primary
                            , window.theme.warning
                            , window.theme.danger
                            , window.theme.secondary
                            , "#E8EAED"
                        ]
                        , borderColor: "transparent"
                    }]
                }
                , options: {
                    responsive: !window.MSInputMethodContext
                    , maintainAspectRatio: false
                    , legend: {
                        display: false
                    }
                    , cutoutPercentage: 75
                }
            });
        });

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Bar chart
            new Chart(document.getElementById("chartjs-dashboard-bar"), {
                type: 'bar'
                , data: {
    labels: ["Name1", "Name2", "Name3", "Name4", "Name5", "Name6", "Name7"],
    datasets: [{
        label: "This year",
        backgroundColor: window.theme.primary,
        borderColor: window.theme.primary,
        hoverBackgroundColor: window.theme.primary,
        hoverBorderColor: window.theme.primary,
        data: [54, 67, 41, 55, 62, 45, 55], // sirf 7 data points
        barPercentage: 0.75,
        categoryPercentage: 0.5
    }]
}
                , options: {
                    maintainAspectRatio: false
                    , legend: {
                        display: false
                    }
                    , scales: {
                        yAxes: [{
                            gridLines: {
                                display: false
                            }
                            , stacked: false
                            , ticks: {
                                stepSize: 20
                            }
                        }]
                        , xAxes: [{
                            stacked: false
                            , gridLines: {
                                color: "transparent"
                            }
                        }]
                    }
                }
            });
        });

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var map = new jsVectorMap({
                map: "world"
                , selector: "#world_map"
                , zoomButtons: true
                , selectedRegions: [
                    'US'
                    , 'SA'
                    , 'DE'
                    , 'FR'
                    , 'CN'
                    , 'AU'
                    , 'BR'
                    , 'IN'
                    , 'GB'
                ]
                , regionStyle: {
                    initial: {
                        fill: '#e4e4e4'
                        , "fill-opacity": 0.9
                        , stroke: 'none'
                        , "stroke-width": 0
                        , "stroke-opacity": 0
                    }
                    , selected: {
                        fill: window.theme.primary
                    , }
                }
                , zoomOnScroll: false
            });
            window.addEventListener("resize", () => {
                map.updateSize();
            });
            setTimeout(function() {
                map.updateSize();
            }, 250);
        });

    </script>
    <script>
        $(function() {
            $('#datatables-dashboard-projects').DataTable({
                pageLength: 6
                , lengthChange: false
                , bFilter: false
                , autoWidth: false
            });
        });

    </script>
    <script>
        $(function() {
            $('#datetimepicker-dashboard').datetimepicker({
                inline: true
                , sideBySide: false
                , format: 'L'
            });
        });

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive").DataTable({
                pageLength: 20
                , lengthMenu: [
                    [20, 40, 60, 80, 100]
                    , [20, 40, 60, 80, 100]
                ]
                , responsive: true
            });
        });

    </script>
    <script>
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                alert.remove()
            });
        }, 5000);

    </script>



</body>

</html>
