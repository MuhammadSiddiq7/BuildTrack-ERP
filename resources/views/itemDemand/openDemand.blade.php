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
                                                        icon: 'success',
                                                        title: 'Success!',
                                                        text: '{{ session('swal_success') }}',
                                                        confirmButtonColor: '#3085d6'
                                                    });
                                                </script>
                                            @endif

                                            @if (session('swal_error'))
                                                <script>
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Error!',
                                                        text: '{{ session('swal_error') }}',
                                                        confirmButtonColor: '#d33'
                                                    });
                                                </script>
                                            @endif


                                            <form action="{{ route('demand.store') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf



                                                <div id="item-demand-wrapper">
                                                    <div class="row item-demand-row gx-3 gy-4">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Contractor Code</label>
                                                            <input type="text" name="contractor_code[]"
                                                                class="form-control contractor-code-field">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Date</label>
                                                            <input type="date" name="date" class="form-control"
                                                                required>
                                                            @error('date')
                                                                <div class="text-danger small">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <hr class="my-4">

                                                        {{-- Contractor --}}
                                                        <div class="col-md-4">
                                                            <label class="form-label">Contractor</label>
                                                            <select name="contractor_id[]"
                                                                class="form-select contractor-select" disabled required>
                                                                <option value="">-- Select Contractor --</option>
                                                            </select>
                                                        </div>

                                                        {{-- Project --}}
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold">Project</label>
                                                            <select name="project_id" class="form-select project-select"
                                                                disabled required>
                                                                <option value="">-- Select Project --</option>
                                                            </select>
                                                        </div>

                                                        {{-- <div class="col-md-6">
                                                            <label class="form-label fw-semibold">House Type</label>
                                                            <select name="house_type_id[]"
                                                                class="form-select house-type-select" disabled>
                                                                <option value="">-- Select House Type --</option>
                                                            </select>
                                                        </div> --}}

                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold">House Type</label>
                                                            <select name="house_project_id[]"
                                                                class="form-select house-type-select" disabled>
                                                                <option value="">-- Select House Type --</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">Item</label>
                                                            <select name="item_id[]" class="form-select item-select"
                                                                required>
                                                                <option value="">-- Select Item --</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">Size</label>
                                                            <input type="text" class="form-control size-field"
                                                                readonly>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label">Allocated Qty</label>
                                                            <input type="number"name="allocated_qty[]" step="0.01"
                                                                class="form-control allocated-field" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Remaining Qty</label>
                                                            <input type="number" name="remaining_qty[]" step="0.01"
                                                                class="form-control remaining-field" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Request QTY</label>
                                                            <input type="number" name="item_qty[]" min="0"
                                                                step="0.01" class="form-control qty-field"
                                                                required>
                                                        </div>

                                                        <!-- Over & Above Qty -->
                                                        <div class="col-12 over-above-section p-3 rounded bg-light shadow-sm"
                                                            style="display: none; border-left: 4px solid #0d6efd;">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">Over & Above
                                                                        Qty</label>
                                                                    <input type="number" name="over_qty[]"
                                                                        min="0" step="0.01"
                                                                        class="form-control over-qty-field"
                                                                        placeholder="Enter extra quantity">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">Over & Above
                                                                        Description</label>
                                                                    <input type="text" name="over_description[]"
                                                                        class="form-control over-desc-field"
                                                                        placeholder="Enter reason or note">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Buttons -->
                                                        <div class="col-12 mt-3 d-flex gap-2">
                                                            <button type="button"
                                                                class="btn btn-outline-primary btn-sm add-over-btn">+
                                                                Add Over &
                                                                Above</button>
                                                            <button type="button"
                                                                class="btn btn-outline-danger btn-sm remove-row d-none">Remove</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="my-4">
                                                    <button type="button" id="addMoreBtn"
                                                        class="btn btn-secondary btn-sm">➕ Add More</button>
                                                </div>

                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-success px-4">💾
                                                        Save</button>
                                                </div>
                                            </form>
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


    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const projectDropdown = document.getElementById('projectSelect');

            // ================= Load Contractors by Project =================
            function loadContractorsByProject(projectId, contractorSelectElement) {

                contractorSelectElement.innerHTML = '<option value="">Loading...</option>';
                fetch(`/contractorDemand/project/${projectId}/contractors`)
                    .then(res => res.json())
                    .then(data => {
                        let options = '<option value="">-- Select Contractor --</option>';
                        data.forEach(contractor => {
                            options += `<option value="${contractor.id}" data-code="${contractor.code}">
                                        ${contractor.name}
                                    </option>`;
                        });
                        contractorSelectElement.innerHTML = options;
                    })
                    .catch(() => {
                        contractorSelectElement.innerHTML =
                            '<option value="">-- No Contractors Found --</option>';
                    });
            }

            // ================= Load Items by Project + Contractor =================
            function loadItemsByContractor(projectId, contractorId, itemSelectElement) {
                itemSelectElement.innerHTML = '<option value="">Loading...</option>';

                fetch(`/contractorDemand/get-items/${projectId}/${contractorId}`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">-- Select Item --</option>';
                        data.forEach(item => {
                            options += `
                        <option value="${item.id}"
                            data-size="${item.size ?? ''}"
                            data-allocated="${item.allocated_qty}"
                            data-issued="${item.issued_qty}"
                            data-remaining="${item.remaining_qty}">
                            ${item.item_name}
                        </option>`;
                        });
                        itemSelectElement.innerHTML = options;
                    })
                    .catch(err => {
                        console.error('Error fetching items:', err);
                        itemSelectElement.innerHTML = '<option value="">-- No Items Found --</option>';
                    });
            }

            function setupItemRow(row) {
                const contractorSelect = row.querySelector('.contractor-select');
                const contractorCodeField = row.querySelector('.contractor-code-field');
                const itemSelect = row.querySelector('.item-select');
                const sizeField = row.querySelector('.size-field');
                const allocatedField = row.querySelector('.allocated-field');
                const remainingField = row.querySelector('.remaining-field');
                const qtyField = row.querySelector('.qty-field');
                const removeBtn = row.querySelector('.remove-row');

                // ================= Contractor Change → Reset Code + Load Items =================
                contractorSelect.addEventListener('change', function() {
                    contractorCodeField.value = '';
                    contractorCodeField.dataset.valid = "false"; // reset code state
                    contractorCodeField.classList.remove('is-valid', 'is-invalid');

                    const contractorId = this.value;
                    const projectId = projectDropdown.value;
                    if (contractorId && projectId) {
                        loadItemsByContractor(projectId, contractorId, itemSelect);
                    } else {
                        itemSelect.innerHTML = '<option value="">-- Select Item --</option>';
                        sizeField.value = '';
                        allocatedField.value = '';
                        remainingField.value = '';
                    }
                });

                // ================= Item Change → Fill fields =================
                itemSelect.addEventListener('change', function() {
                    const selected = itemSelect.selectedOptions[0];
                    if (!selected) return;

                    sizeField.value = selected.getAttribute('data-size') ?? '';
                    allocatedField.value = selected.getAttribute('data-allocated') ?? 0;
                    remainingField.value = selected.getAttribute('data-remaining') ?? 0;

                    qtyField.max = remainingField.value;
                    qtyField.value = '';
                });

                // ================= Qty Validation =================
                qtyField.addEventListener('input', function() {
                    const max = parseFloat(remainingField.value) || 0;
                    const entered = parseFloat(qtyField.value) || 0;
                    if (entered > max) {
                        alert(`⚠️ Demanded quantity cannot exceed remaining quantity (${max})`);
                        qtyField.value = max;
                    }
                });

                // ================= Remove Row =================
                removeBtn.addEventListener('click', function() {
                    const rows = document.querySelectorAll('.item-demand-row');
                    if (rows.length > 1) {
                        row.remove();
                    } else {
                        alert('At least one item row is required.');
                    }
                });
            }
            // Project Change → Load Contractors
            projectDropdown.addEventListener('change', function() {
                const projectId = this.value;
                document.querySelectorAll('.item-demand-row').forEach(row => {
                    const contractorSelect = row.querySelector('.contractor-select');
                    const itemSelect = row.querySelector('.item-select');
                    if (projectId) {
                        loadContractorsByProject(projectId, contractorSelect);
                    } else {
                        contractorSelect.innerHTML =
                            '<option value="">-- Select Contractor --</option>';
                        itemSelect.innerHTML = '<option value="">-- Select Item --</option>';
                    }
                });
            });

            // Setup Existing Rows
            document.querySelectorAll('.item-demand-row').forEach(row => setupItemRow(row));

            // Add New Row
            document.getElementById('addMoreBtn').addEventListener('click', function() {
                const wrapper = document.getElementById('item-demand-wrapper');
                const firstRow = wrapper.querySelector('.item-demand-row');
                const newRow = firstRow.cloneNode(true);

                newRow.querySelectorAll('select, input').forEach(input => {
                    if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    } else {
                        input.value = '';
                    }
                });

                wrapper.appendChild(newRow);
                setupItemRow(newRow);
            });
        });
    </script> --}}

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
                icon: 'error',
                title: 'Oops!',
                html: `{!! session('days_request_error') !!}`,
                confirmButtonText: 'Okay',
                confirmButtonColor: '#153d77'
            });
        </script>
    @endif
    <!-- jQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>


    {{-- withoout addmore <script>
$(document).ready(function() {
    console.log('jQuery ready');

    let typingTimer;

    // 🔹 Helper: get total requested qty for an item across all rows
    function getTotalRequestedForItem(itemId) {
        let total = 0;
        $('.item-demand-row').each(function() {
            const sel = $(this).find('.item-select');
            const q = parseFloat($(this).find('.qty-field').val() || 0);
            if (sel.val() && sel.val().toString() === itemId.toString()) {
                total += q;
            }
        });
        return total;
    }

    // 🔹 1. Contractor code input
    $(document).on('input', '.contractor-code-field', function() {
        clearTimeout(typingTimer);
        const input = $(this);
        typingTimer = setTimeout(function() {
            const code = input.val().trim();
            const row = input.closest('.item-demand-row');
            const contractorSelect = row.find('.contractor-select');
            const projectSelect = row.find('.project-select');
            const houseSelect = row.find('.house-type-select');
            const itemSelect = row.find('.item-select');
            const sizeField = row.find('.size-field');
            const allocatedField = row.find('.allocated-field');
            const remainingField = row.find('.remaining-field');
            const qtyField = row.find('.qty-field');

            // Reset
            contractorSelect.prop('disabled', true).html('<option>Loading...</option>');
            projectSelect.prop('disabled', true).html('<option value="">-- Select Project --</option>');
            houseSelect.prop('disabled', true).html('<option value="">-- Select House Type --</option>');
            itemSelect.html('<option value="">-- Select Item --</option>');
            sizeField.val(''); allocatedField.val(''); remainingField.val(''); qtyField.val('');
            row.removeData('projects');

            if (code.length > 0) {
                $.get("{{ url('itemDemand/contractor') }}/" + encodeURIComponent(code) + "/demands", function(res) {
                    if (res.status === 'success' && Array.isArray(res.contractors) && res.contractors.length > 0) {
                        contractorSelect.prop('disabled', false).empty().append('<option value="">-- Select Contractor --</option>');
                        res.contractors.forEach(c => contractorSelect.append('<option value="'+c.id+'">'+c.name+'</option>'));
                        row.data('projects', res.projects);
                    } else {
                        contractorSelect.html('<option value="">Invalid Contractor Code</option>');
                        projectSelect.html('<option value="">-- Select Project --</option>');
                        houseSelect.html('<option value="">-- Select House Type --</option>');
                    }
                });
            } else {
                contractorSelect.prop('disabled', true).html('<option value="">-- Select Contractor --</option>');
                projectSelect.prop('disabled', true).html('<option value="">-- Select Project --</option>');
                houseSelect.prop('disabled', true).html('<option value="">-- Select House Type --</option>');
            }
        }, 500);
    });

    // 🔹 2. Contractor select -> load projects
    $(document).on('change', '.contractor-select', function() {
        const contractorId = $(this).val();
        const row = $(this).closest('.item-demand-row');
        const projectSelect = row.find('.project-select');
        const houseSelect = row.find('.house-type-select');
        const itemSelect = row.find('.item-select');
        const sizeField = row.find('.size-field');
        const allocatedField = row.find('.allocated-field');
        const remainingField = row.find('.remaining-field');
        const qtyField = row.find('.qty-field');
        const projects = row.data('projects') || [];

        projectSelect.prop('disabled', true).empty().append('<option value="">-- Select Project --</option>');
        houseSelect.prop('disabled', true).html('<option value="">-- Select House Type --</option>');
        itemSelect.html('<option value="">-- Select Item --</option>');
        sizeField.val(''); allocatedField.val(''); remainingField.val(''); qtyField.val('');

        if (contractorId && projects.length > 0) {
            projects.forEach(p => projectSelect.append('<option value="'+p.id+'">'+(p.project_name ?? 'Unnamed Project')+'</option>'));
            projectSelect.prop('disabled', false);
        }
    });

    // 🔹 3. Project select -> load house types + items
    $(document).on('change', '.project-select', function() {
        const row = $(this).closest('.item-demand-row');
        const projectId = $(this).val();
        const contractorId = row.find('.contractor-select').val();
        const houseSelect = row.find('.house-type-select');
        const itemSelect = row.find('.item-select');
        const sizeField = row.find('.size-field');
        const allocatedField = row.find('.allocated-field');
        const remainingField = row.find('.remaining-field');
        const qtyField = row.find('.qty-field');

        houseSelect.prop('disabled', true).empty().append('<option value="">-- Select House Type --</option>');
        itemSelect.html('<option value="">-- Select Item --</option>');
        sizeField.val(''); allocatedField.val(''); remainingField.val(''); qtyField.val('');

        if (projectId && contractorId) {
            // Load house types
            $.get("{{ url('/plan/get-house-types-by-contractor') }}/" + projectId + "/" + contractorId, function(data) {
                if (Array.isArray(data) && data.length > 0) {
                    houseSelect.prop('disabled', false).empty().append('<option value="">-- Select House Type --</option>');
                    data.forEach(h => houseSelect.append('<option value="'+h.id+'">'+h.house_type_id+'</option>'));
                } else {
                    houseSelect.html('<option value="">No House Types Found</option>');
                }
            });

            // Load items
            $.get("{{ url('/itemDemand/get-items') }}/" + projectId + "/" + contractorId, function(data) {
                if (Array.isArray(data) && data.length > 0) {
                    itemSelect.prop('disabled', false).empty().append('<option value="">-- Select Item --</option>');
                    data.forEach(item => {
                        const name = item.item_name ?? item.name ?? item.item ?? '';
                        const size = item.size ?? '';
                        const allocated = item.allocated_qty ?? 0;
                        const remaining = item.remaining_qty ?? 0;
                        itemSelect.append('<option value="'+item.id+'" data-size="'+size+'" data-allocated="'+allocated+'" data-remaining="'+remaining+'">'+name+' (Remaining: '+remaining+')</option>');
                    });
                } else {
                    itemSelect.html('<option value="">No Items Found</option>');
                }
            });
        }
    });

    // 🔹 4. Item select -> populate size/allocated/remaining
    $(document).on('change', '.item-select', function() {
        const row = $(this).closest('.item-demand-row');
        const sel = $(this).find(':selected');
        const sizeField = row.find('.size-field');
        const allocatedField = row.find('.allocated-field');
        const remainingField = row.find('.remaining-field');
        const qtyField = row.find('.qty-field');

        if (sel.length > 0 && sel.val()) {
            sizeField.val(sel.data('size') ?? '');
            allocatedField.val(sel.data('allocated') ?? 0);
            remainingField.val(sel.data('remaining') ?? 0);
            qtyField.val('').attr('max', sel.data('remaining'));
        } else {
            sizeField.val(''); allocatedField.val(''); remainingField.val(''); qtyField.val('');
        }
    });

    // 🔹 5. Validate qty input so it does not exceed remaining across rows
    $(document).on('input', '.qty-field', function() {
        const row = $(this).closest('.item-demand-row');
        const itemSelect = row.find('.item-select');
        const itemId = itemSelect.val();
        if (!itemId) return;

        const remaining = parseFloat(itemSelect.find(':selected').data('remaining') || 0);
        const totalRequested = getTotalRequestedForItem(itemId);

        if (totalRequested > remaining) {
            const currentVal = parseFloat($(this).val() || 0);
            const otherTotals = totalRequested - currentVal;
            const allowed = Math.max(0, remaining - otherTotals);
            $(this).val(allowed);
            alert('Requested quantity exceeds remaining available (' + remaining + '). It has been adjusted.');
        }
    });

    // Initialize select2
    $(".select2").select2({
        placeholder: "Select value",
        allowClear: true,
        width: "100%"
    });
});
</script> --}}

    <script>
        $(document).ready(function() {
            console.log('✅ jQuery ready for Add More + AJAX logic');

            let typingTimer;

            // 🔹 Function: calculate total requested for same item
            function getTotalRequestedForItem(itemId) {
                let total = 0;
                $('.item-demand-row').each(function() {
                    const sel = $(this).find('.item-select');
                    const qty = parseFloat($(this).find('.qty-field').val() || 0);
                    if (sel.val() && sel.val().toString() === itemId.toString()) {
                        total += qty;
                    }
                });
                return total;
            }

            // 🔹 Contractor code input (fetch contractor + projects)
            $(document).on('input', '.contractor-code-field', function() {
                clearTimeout(typingTimer);
                const input = $(this);
                const row = input.closest('.item-demand-row');

                typingTimer = setTimeout(function() {
                    const code = input.val().trim();
                    const contractorSelect = row.find('.contractor-select');
                    const projectSelect = row.find('.project-select');
                    const houseSelect = row.find('.house-type-select');
                    const itemSelect = row.find('.item-select');
                    const sizeField = row.find('.size-field');
                    const allocatedField = row.find('.allocated-field');
                    const remainingField = row.find('.remaining-field');
                    const qtyField = row.find('.qty-field');

                    // Reset all
                    contractorSelect.prop('disabled', true).html('<option>Loading...</option>');
                    projectSelect.prop('disabled', true).html(
                        '<option value="">-- Select Project --</option>');
                    houseSelect.prop('disabled', true).html(
                        '<option value="">-- Select House Type --</option>');
                    itemSelect.html('<option value="">-- Select Item --</option>');
                    sizeField.val('');
                    allocatedField.val('');
                    remainingField.val('');
                    qtyField.val('');
                    row.removeData('projects');

                    if (code.length > 0) {
                        $.get("{{ url('itemDemand/contractor') }}/" + encodeURIComponent(code) +
                            "/demands",
                            function(res) {
                                if (res.status === 'success' && Array.isArray(res
                                        .contractors) && res.contractors.length > 0) {
                                    contractorSelect.prop('disabled', false)
                                        .empty()
                                        .append(
                                            '<option value="">-- Select Contractor --</option>'
                                        );
                                    res.contractors.forEach(c => contractorSelect.append(
                                        '<option value="' + c.id + '">' + c.name +
                                        '</option>'));
                                    row.data('projects', res.projects);
                                } else {
                                    contractorSelect.html(
                                        '<option value="">Invalid Contractor Code</option>');
                                }
                            });
                    } else {
                        contractorSelect.prop('disabled', true).html(
                            '<option value="">-- Select Contractor --</option>');
                    }
                }, 500);
            });

            // 🔹 Contractor select → load projects
            $(document).on('change', '.contractor-select', function() {
                const contractorId = $(this).val();
                const row = $(this).closest('.item-demand-row');
                const projectSelect = row.find('.project-select');
                const houseSelect = row.find('.house-type-select');
                const itemSelect = row.find('.item-select');

                projectSelect.prop('disabled', true).empty().append(
                    '<option value="">-- Select Project --</option>');
                houseSelect.prop('disabled', true).html(
                    '<option value="">-- Select House Type --</option>');
                itemSelect.html('<option value="">-- Select Item --</option>');

                const projects = row.data('projects') || [];
                if (contractorId && projects.length > 0) {
                    projects.forEach(p => projectSelect.append('<option value="' + p.id + '">' + p
                        .project_name + '</option>'));
                    projectSelect.prop('disabled', false);
                }
            });

            // 🔹 Project select → load house types + items
            $(document).on('change', '.project-select', function() {
                const row = $(this).closest('.item-demand-row');
                const projectId = $(this).val();
                const contractorId = row.find('.contractor-select').val();
                const houseSelect = row.find('.house-type-select');
                const itemSelect = row.find('.item-select');

                houseSelect.prop('disabled', true).empty().append(
                    '<option value="">-- Select House Type --</option>');
                itemSelect.html('<option value="">-- Select Item --</option>');

                if (projectId && contractorId) {
                    // Load House Types (filtered by contractor)
                    $.get("{{ url('/plan/get-house-types-by-contractor') }}/" + projectId + "/" +
                        contractorId,
                        function(data) {
                            if (Array.isArray(data) && data.length > 0) {
                                houseSelect.prop('disabled', false);
                                data.forEach(h => houseSelect.append('<option value="' + h.id + '">' + h
                                    .house_type_id + '</option>'));
                            } else {
                                houseSelect.html('<option value="">No House Types Found</option>');
                            }
                        });

                    // Load Items
                    $.get("{{ url('/itemDemand/get-items') }}/" + projectId + "/" + contractorId, function(
                        data) {
                        if (Array.isArray(data) && data.length > 0) {
                            itemSelect.prop('disabled', false);
                            itemSelect.empty().append(
                                '<option value="">-- Select Item --</option>');
                            data.forEach(item => {
                                itemSelect.append(
                                    `<option value="${item.id}" 
                                data-size="${item.size ?? ''}" 
                                data-allocated="${item.allocated_qty ?? 0}" 
                                data-remaining="${item.remaining_qty ?? 0}">
                                ${item.item_name ?? item.name ?? ''} (Remaining: ${item.remaining_qty ?? 0})
                            </option>`
                                );
                            });
                        } else {
                            itemSelect.html('<option value="">No Items Found</option>');
                        }
                    });
                }
            });

            // 🔹 Item select → populate details
            $(document).on('change', '.item-select', function() {
                const row = $(this).closest('.item-demand-row');
                const sel = $(this).find(':selected');
                const sizeField = row.find('.size-field');
                const allocatedField = row.find('.allocated-field');
                const remainingField = row.find('.remaining-field');
                const qtyField = row.find('.qty-field');

                if (sel.val()) {
                    sizeField.val(sel.data('size') ?? '');
                    allocatedField.val(sel.data('allocated') ?? 0);
                    remainingField.val(sel.data('remaining') ?? 0);
                    qtyField.val('').attr('max', sel.data('remaining'));
                } else {
                    sizeField.val('');
                    allocatedField.val('');
                    remainingField.val('');
                    qtyField.val('');
                }
            });

            // 🔹 Validate qty
            $(document).on('input', '.qty-field', function() {
                const row = $(this).closest('.item-demand-row');
                const itemSelect = row.find('.item-select');
                const itemId = itemSelect.val();
                if (!itemId) return;

                const remaining = parseFloat(itemSelect.find(':selected').data('remaining') || 0);
                const totalRequested = getTotalRequestedForItem(itemId);

                if (totalRequested > remaining) {
                    const currentVal = parseFloat($(this).val() || 0);
                    const otherTotals = totalRequested - currentVal;
                    const allowed = Math.max(0, remaining - otherTotals);
                    $(this).val(allowed);
                    alert('Requested quantity exceeds remaining available (' + remaining +
                        '). It has been adjusted.');
                }
            });

            // 🔹 Add Over & Above toggle
            $(document).on('click', '.add-over-btn', function() {
                const section = $(this).closest('.item-demand-row').find('.over-above-section');
                section.toggle();
            });

            // 🔹 Add More button
            $('#addMoreBtn').on('click', function() {
                const newRow = $('.item-demand-row:first').clone(true, true);
                newRow.find('input, select').val('');
                newRow.find('.contractor-select, .project-select, .house-type-select').prop('disabled',
                    true);
                newRow.find('.remove-row').removeClass('d-none');
                newRow.find('.over-above-section').hide();
                $('#item-demand-wrapper').append(newRow);
            });

            // 🔹 Remove row
            $(document).on('click', '.remove-row', function() {
                if ($('.item-demand-row').length > 1) {
                    $(this).closest('.item-demand-row').remove();
                } else {
                    alert('At least one row is required.');
                }
            });

        });
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
                icon: 'error',
                title: 'Oops!',
                html: `{!! session('days_request_error') !!}`,
                confirmButtonText: 'Okay',
                confirmButtonColor: '#153d77'
            });
        </script>
    @endif

</body>

</html>
