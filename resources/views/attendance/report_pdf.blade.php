<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            margin: 10px;
             padding-top: 5px;    /* Top padding */
    padding-right: 10px;  /* Right padding */
    padding-bottom: 8px; /* Bottom padding */
    padding-left: 10px;   /* Left padding */
        }

        /* Header: center logo & info */
        .header {
            text-align: center;
            border-bottom: 2px solid #153d77;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .header img {
            width: 70px; /* compact logo */
            height: auto;
            margin-bottom: 5px;
        }

        .header h2 {
            margin: 2px 0;
            color: #153d77;
            font-size: 16px; /* compact */
        }

        .header p {
            margin: 1px 0;
            font-size: 12px; /* compact */
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table th, table td {
            border: 1px solid #153d77;
            padding: 4px; /* compact */
            text-align: center;
            font-size: 11px;
        }

        table th {
            background-color: #153d77;
            color: #fff;
        }

        /* Footer */
        .footer {
            margin-top: 8px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #153d77;
            padding-top: 3px;
        }
    </style>
</head>
<body>

    <!-- Header with logo & info center aligned -->
    <div class="header">
        <img src="{{ public_path('assets/img/logo/adcc.png') }}" alt="Logo">
        <h2>Attendance Report</h2>
        <p>{{ date('F Y', strtotime($monthYear)) }}</p>
        <p>Employee: {{ $employee->name }} ({{ $employee->employee_id }})</p>
    </div>

    <!-- Attendance Table -->
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Date</th>
                <th>Attendance Status</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach($dates as $date)
                @php
                    $dateStr = $date->format('Y-m-d');
                    $status = $attendances->has($dateStr) ? $attendances[$dateStr]->attendance : '-';
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $dateStr }}</td>
                    <td>{{ $status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        This is a system generated attendance report. © {{ date('Y') }} ADCC.
    </div>

</body>
</html>
