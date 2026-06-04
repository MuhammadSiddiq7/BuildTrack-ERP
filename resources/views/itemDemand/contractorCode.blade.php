<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contractor Demands</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/adcc.png') }}" />
    <link href="{{ asset('assets/css/modern.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="p-4">

    <div class="container mt-5">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/img/logo/adcc.png') }}" width="120">
            <h2>Contractor Demand Portal</h2>
            <p>Enter your contractor code below to view your demands.</p>
        </div>

        <form id="contractorForm" class="mt-4">
            <div class="input-group mx-auto" style="max-width:400px;">
                <input type="text" id="contractorCode" class="form-control" placeholder="Enter contractor code" required>
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('contractorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let code = document.getElementById('contractorCode').value.trim();
        if (!code) return;

        fetch("{{ url('contractor/receive') }}/" + code, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                // valid code → redirect to actual page
                window.location.href = "{{ url('contractor/receive') }}/" + code;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Code',
                    text: 'No contractor found with this code.',
                    confirmButtonColor: '#3085d6',
                });
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Something went wrong while checking contractor code.',
            });
        });
    });
    </script>

</body>
</html>
