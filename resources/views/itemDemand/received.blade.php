<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A name you can trust in an uncertain world">
    <meta name="author" content="Bootlab">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/adcc.png') }}" />
    <title>ADCC - Item Demand</title>
    <link href="{{ asset('assets/css/modern.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/classic.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dark.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/light.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            opacity: 0;
        }

    </style>

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
        <div class="main">
            <main class="content">
                <div class="container-fluid">
                    <div class="header text-center" style="margin-bottom: 10px !important">
                        <img src="{{ asset('assets/img/logo/adcc.png') }}" alt="ADCC" style="width: 150px">
                        <h1 class="header-title pt-3">
                            Item Demand
                        </h1>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div>
                                            <!-- Include SweetAlert -->
                                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                                            @if (session('swal_success'))
                                            <script>
                                                Swal.fire({
                                                    icon: 'success'
                                                    , title: 'Success!'
                                                    , text: '{{ session('
                                                    swal_success ') }}'
                                                    , confirmButtonColor: '#3085d6'
                                                });

                                            </script>
                                            @endif

                                            @if (session('swal_error'))
                                            <script>
                                                Swal.fire({
                                                    icon: 'error'
                                                    , title: 'Error!'
                                                    , text: '{{ session('
                                                    swal_error ') }}'
                                                    , confirmButtonColor: '#d33'
                                                });

                                            </script>
                                            @endif

                                            <div class="col-12">
                                                <div class="card shadow-sm p-4">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h5 class="fw-bold">
                                                            @if ($contractors->count() == 1)
                                                            {{ $contractors->first()->name }}
                                                            @else
                                                            {{ $contractors->pluck('name')->join(', ') }}
                                                            @endif
                                                        </h5>
                                                        <span class="fw-semibold">STORE DEMAND FORM</span>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-md-6">
                                                            <p><strong>Demand No:</strong> {{ $itemDemand->demand_no }}</p>
                                                        </div>
                                                        <div class="col-md-6 text-end">
                                                            <p><strong>Dated:</strong> {{ \Carbon\Carbon::parse($itemDemand->date)->format('d-m-Y') }}</p>
                                                        </div>
                                                    </div>
                                                    <table class="table table-bordered align-middle text-center">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th style="width: 50px;">S.No</th>
                                                                <th>Description</th>
                                                                <th>Deno/Unit</th>
                                                                <th>Qty Allowed</th>
                                                                <th>Qty Demand</th>
                                                                <th>Previous Issued</th>
                                                                <th>Current Issued</th>
                                                                <th>Progressive Total</th>
                                                                <th>Balance Qty</th>
                                                                <th>Over & Above</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tbody>

                                                            @foreach ($itemDemand->items as $index => $item)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $item->item }}</td>
                                                                <td>{{ $item->deno ?? '-' }}</td>
                                                                <td>{{ $item->pivot->allocated_qty ?? '-' }}</td>
                                                                <td>{{ $item->pivot->item_qty }}</td>
                                                                <td>{{ $item->pivot->previous_issued ?? '-' }}</td>
                                                                <td>{{ $item->pivot->current_issued ?? '-' }}</td>
                                                                <td>{{ $item->pivot->progressive_total ?? '-' }}</td>
                                                                <td>{{ $item->pivot->balance_qty ?? '-' }}</td>
                                                                <td>
                                                                    @if (!empty($item->pivot->over_qty))
                                                                    {{ $item->pivot->over_qty }} <br>
                                                                    {{ $item->pivot->over_description }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if(empty($item->pivot->status) || $item->pivot->status != 'received')
                                                                    @if(!empty($item->pivot->current_issued))
                                                                    <form action="{{ route('demand.item.stockout', [$itemDemand->public_token, $item->id]) }}" method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to stock out this item?')">
                                                                            Receive
                                                                        </button>
                                                                    </form>
                                                                    @else
                                                                    <span class="badge bg-secondary">Not Issued</span>
                                                                    @endif
                                                                    @else
                                                                    <span class="badge bg-success">Received</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                &copy; 2025 - <a class='text-muted' target="_blank" href='#'>ADCC</a>
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
    <style>
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

    </style>

    </script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        setTimeout(function() {
            let alertBox = document.getElementById('alertMessage');
            if (alertBox) {
                alertBox.style.transition = 'opacity 0.5s ease';
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 4000);

    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('days_request_error'))
    <script>
        Swal.fire({
            icon: 'error'
            , title: 'Oops!'
            , html: `{!! session('days_request_error') !!}`
            , confirmButtonText: 'Okay'
            , confirmButtonColor: '#153d77'
        });

    </script>
    @endif


</body>

</html>
