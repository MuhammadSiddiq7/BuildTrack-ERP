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
    <title>ADCC - Contractor List</title>
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
                            Contractor List
                        </h1>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div>
                                            @if (session('success'))
                                                <div class="alert alert-success alert-top" id="alertMessage">
                                                    {{ session('success') }}
                                                </div>
                                            @endif
                                            @if (session('error'))
                                                <div class="alert alert-danger alert-top" id="alertMessage">
                                                    {{ session('error') }}
                                                </div>
                                            @endif
                                            <div class="row">

                                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="code"><b>Contractor Code</b></label>
                                                        <input type="text" name="code" id="code"
                                                            value="{{ old('code') }}"
                                                            class="form-control @error('code') is-invalid @enderror"
                                                            placeholder="Enter Contractor Code">
                                                        @error('code')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="mt-5">
                                                <h4>Contractor Demand List</h4>
                                                <div id="demand-list"></div>
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
                                    <a class="text-muted" style="color: #2fa09c !important" target="_blank"
                                        href="https://synergyintegratedsolutions.pk/">Synergy Integrated Solutions</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-4 text-end">
                            <p class="mb-0">
                                &copy; 2025 - <a class='text-muted' target="_blank" href="">ADCC</a>
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
                <path
                    d="M448 273.001c-21.27 0-39.296 13.999-45.596 32.999h-38.857l-28.361-85.417a15.999 15.999 0 0 0-15.183-10.956c-.112 0-.224 0-.335.004a15.997 15.997 0 0 0-15.049 11.588l-44.484 155.262-52.353-314.108C206.535 54.893 200.333 48 192 48s-13.693 5.776-15.525 13.135L115.496 306H16v31.999h112c7.348 0 13.75-5.003 15.525-12.134l45.368-182.177 51.324 307.94c1.229 7.377 7.397 11.92 14.864 12.344.308.018.614.028.919.028 7.097 0 13.406-3.701 15.381-10.594l49.744-173.617 15.689 47.252A16.001 16.001 0 0 0 352 337.999h51.108C409.973 355.999 427.477 369 448 369c26.511 0 48-22.492 48-49 0-26.509-21.489-46.999-48-46.999z">
                </path>
            </symbol>
        </defs>
    </svg>
    <style>
        .alert-top {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 250px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.3s ease-in-out;
        }

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
    <script>
        document.getElementById('code').addEventListener('blur', function() {
            const code = this.value.trim();
            if (!code) return;

            fetch(`/receive/demands/${code}`)
                .then(res => res.json())
                .then(res => {
                    const container = document.getElementById('demand-list');
                    if (res.status === 'error') {
                        container.innerHTML = `<div class="alert alert-danger">${res.message}</div>`;
                        return;
                    }

                    if (!res.data.length) {
                        container.innerHTML =
                            `<div class="alert alert-warning">No demands found for this contractor.</div>`;
                        return;
                    }

                    let html = `<table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Demand #</th>
                        <th>Date</th>
                        <th>Project</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>`;

                    res.data.forEach(demand => {
                        let itemsHtml = '<ul>';
                        demand.items.forEach(item => {
                            itemsHtml +=
                                `<li>${item.item} — Qty: ${item.qty}, Over: ${item.over_qty ?? 0}, Balance: ${item.balance}</li>`;
                        });
                        itemsHtml += '</ul>';

                        html += `
                    <tr>
                        <td>${demand.demand_no}</td>
                        <td>${demand.date}</td>
                        <td>${demand.project}</td>
                        <td>${itemsHtml}</td>
                        <td>${demand.status}</td>
                        <td>
                            <a href="/contractorDemand/receive/${demand.token}" class="btn btn-sm btn-primary">
                                View
                            </a>
                        </td>
                    </tr>
                `;
                    });

                    html += `</tbody></table>`;
                    container.innerHTML = html;
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('demand-list').innerHTML =
                        `<div class="alert alert-danger">Error fetching demand list.</div>`;
                });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('days_request_error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                html: `{!! session('days_request_error') !!}`,
                confirmButtonText: 'Okay',
                confirmButtonColor: '#153d77'
            });
        </script>
    @endif


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const defaultSection = document.getElementById('default-form-section');
            const guardforceSection = document.getElementById('guardforce-fields');

            const hiddenCompanyInput = document.getElementById('final_company_id');
            const hiddenDeptInput = document.getElementById('final_department_id');

            function setSelectedOption(selectElement, valueToSelect) {
                for (let i = 0; i < selectElement.options.length; i++) {
                    if (selectElement.options[i].value == valueToSelect) {
                        selectElement.selectedIndex = i;
                        break;
                    }
                }
            }

            function fillFormFields(data) {
                const name = document.getElementById('name');
                const father_name = document.getElementById('father_name');
                const cnic = document.getElementById('cnic');
                const company = document.getElementById('company_id');
                const dept = document.getElementById('employee_department_id');
                const employee_id = document.getElementById('employee_id');

                if (name) name.value = data.name || '';
                if (father_name) father_name.value = data.father_name || '';
                if (cnic) cnic.value = data.cnic || '';
                if (company) company.value = data.company_id || '';
                if (dept) dept.value = data.employee_department_id || '';
                if (employee_id && data.employee_id) employee_id.value = data.employee_id;
                if (hiddenDeptInput) hiddenDeptInput.value = data.employee_department_id || '';
            }

            function fetchAndFill(idNumber) {
                if (idNumber !== '') {
                    fetch(`{{ url('get-employee') }}/${idNumber}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Employee not found');
                            }
                            return response.json();
                        })
                        .then(data => fillFormFields(data))
                        .catch(error => {
                            alert('Employee not found');
                            console.error('Error:', error);
                        });
                }
            }

            function debounce(fn, delay) {
                let timer;
                return function(...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn.apply(this, args), delay);
                };
            }

            const idInput = document.getElementById('employee_id');
            const guardIdInput = document.getElementById('guard_employee_id');

            if (idInput) {
                idInput.addEventListener('input', debounce(function() {
                    fetchAndFill(this.value.trim());
                }, 700));
            }

            if (guardIdInput) {
                guardIdInput.addEventListener('input', debounce(function() {
                    fetchAndFill(this.value.trim());
                }, 700));
            }

            // Initial state
            if (guardforceSection && defaultSection) {
                guardforceSection.style.display = 'none';
                defaultSection.style.display = 'flex';
            }

            if (mainSelect) mainSelect.selectedIndex = 0;
            if (guardforceSelect) guardforceSelect.selectedIndex = 0;
        });
    </script>


</body>

</html>
