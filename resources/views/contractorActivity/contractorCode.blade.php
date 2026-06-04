<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contractor Billing Portal</title>
    <link href="{{ asset('assets/css/modern.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="p-4">

<div class="container mt-5">
    <div class="text-center mb-4">
        <img src="{{ asset('assets/img/logo/adcc.png') }}" width="120">
        <h2>Contractor Billing Portal</h2>
        <p>Enter your contractor code to view activities and request billing.</p>
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

    fetch("{{ url('contractor/billing') }}/" + code, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            window.location.href = "{{ url('contractor/billing') }}/" + code;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Code',
                text: 'No contractor found with this code.',
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
