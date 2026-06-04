<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comparative Statement Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 10px; }
        h3 { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 6px; text-align: center; font-size: 11px; }
        th { background: #f2f2f2; }
        .total-row td { font-weight: bold; }
        .grand-total td { font-weight: bold; }
        .signature { margin-top: 60px; clear: both; width: 100%; }
        .signature .left { float: left; width: 50%; text-align: left; }
        .signature .right { float: right; width: 50%; text-align: right; }
        .sig-block { margin-top: 60px; }
    </style>
</head>
<body>
    <div class="header">
        <!-- <h3>REQUIREMENT OF OCTAGONAL POLE HOT DIPPED GALVANIZED AT ANCHORAGE</h3> -->
         <h3>Comparative Statement For {{$item_demand_name}}</h3>
    </div>

   @php
    // Unique suppliers list nikal lo
    $suppliers = $details->pluck('supplier_name')->unique();
@endphp

<table>
    <thead>
        <tr>
            <th rowspan="2">S#</th>
            <th rowspan="2" style="width: 25%;">Description</th>
            <th rowspan="2">Deno</th>
            <th rowspan="2">TOTAL QTY REQUIRED</th>
            @foreach($suppliers as $supplier)
                <th colspan="2">{{ $supplier }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($suppliers as $supplier)
                <th>Rate</th>
                <th>Amount</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($details->groupBy('description') as $index => $items)
            @php $item = $items->first(); @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="text-align: left;">{{ $item->description }}</td>
                <td>{{ $item->deno }}</td>
                <td>{{ $item->qty }}</td>

                @foreach($suppliers as $supplier)
                    @php
                        $supplierDetail = $items->firstWhere('supplier_name', $supplier);
                    @endphp
                    <td>{{ $supplierDetail ? number_format($supplierDetail->rate, 0) : '-' }}</td>
                    <td>{{ $supplierDetail ? number_format($supplierDetail->amount, 2) : '-' }}</td>
                @endforeach
            </tr>
        @endforeach

        <tr class="total-row">
            <td colspan="4">Total Amount</td>
            @foreach($suppliers as $supplier)
                <td>{{ number_format($details->where('supplier_name', $supplier)->sum('rate'), 2) }}</td>
                <td>{{ number_format($details->where('supplier_name', $supplier)->sum('amount'), 2) }}</td>
            @endforeach
        </tr>

        <tr class="grand-total">
            <td colspan="4">Grand Total</td>
            @foreach($suppliers as $supplier)
                <td>-</td>
                <td>{{ number_format($details->where('supplier_name', $supplier)->sum('amount'), 2) }}</td>
            @endforeach
        </tr>
    </tbody>
</table>


    <div class="signature">
        <div class="left">
            <!-- <p><b>M/S A. Samad & Sons</b> is recommended as the lowest bidder</p> -->
            <div class="sig-block">
                <p>__________________________</p>
                <p><b>Humayoon Sial</b></p>
                <p>Manager (Procurement & Planning)</p>
                <p>ADCC (South)</p>
            </div>
        </div>
        <div class="right">
            <p>APPROVED / NOT APPROVED</p>
            <div class="sig-block">
                <p>__________________________</p>
                <p><b>Abdul Waheed Sohail</b></p>
                <p>Chief Executive Officer</p>
                <p>CEO ADCC</p>
            </div>
        </div>
    </div>
</body>
</html>
