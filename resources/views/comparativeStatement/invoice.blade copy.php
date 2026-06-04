<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comparative Statement Invoice</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 6px; text-align: center; }
        .total { text-align: right; margin-top: 20px; }
        .signature { margin-top: 40px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REQUIREMENT OF OCTAGONAL POLE HOT DIPPED GALVANIZED AT ANCHORAGE</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>S#</th>
                <th>Supplier</th>
                <th>Description</th>
                <th>Deno.</th>
                <th>TOTAL QTY REQUIRED</th>
                <th>RATE / Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->supplier_name }}</td>
                <td>{{ $detail->description }}</td>
                <td>{{ $detail->deno }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ number_format($detail->rate, 0) }}<br>{{ number_format($detail->amount, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td>Total Amount</td>
                <td></td>
                <td></td>
                <td>{{ $details->sum('rate') }}</td>
                <td>{{ number_format($details->sum('amount'), 2) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Grand Total</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ number_format($details->sum('amount'), 2) }}</td>
            </tr>
        </tbody>
    </table>
    <div class="signature">
        {{-- <p>{{ $quotation->supplier_name }} is recommended as the lowest bidder</p> --}}
        <p>__________________________</p>
        <p>Humayoon Sial</p>
        <p>Manager (Procurement & Planning)</p>
        <p>ADCC </p>
        <p>APPROVED / NOT APPROVED</p>
        <p>__________________________</p>
        <p>Abdul Waheed Sohail</p>
        <p>Senior Executive Officer</p>
        <p>CEO ADCC</p>
    </div>
</body>
</html>
