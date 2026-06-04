<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Supply Order - ADCC</title>
<style>
  body {
    font-family: "Times New Roman", Times, serif;
    font-size: 14px;
    color: #111;
    margin: 0;
    padding: 28px 36px;
    background: #fff;
  }

  .company-title {
    font-weight: 700;
    font-size: 18px;
  }
  .company-sub {
    margin-top: 2px;
    font-size: 13px;
  }

  .header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 6px;
  width: 100%;
}

.hdr-left {
  text-align: center;
}

.hdr-right {
  text-align: right;
margin-top: -120px;
}

.logo {
  width: 60px;                 /* thoda bada */
  height: 60px;                /* ratio maintain */
  object-fit: contain;
  display: inline-block;
}


  .meta {
    margin-top: 6px;
    display:flex;
    gap:18px;
    align-items:center;
  }
  .meta .left { flex:1; }
  .meta .right {
    text-align:left;
    min-width:220px;
    font-weight:600;
  }

  .doc-title {
    margin-top: 12px;
    font-weight:700;
    font-size:15px;
    text-decoration: underline;
  }

  .refs { margin-top: 10px; }
  .refs p, .content p { margin:6px 0; line-height:1.45; text-align:justify; }
  .refs .ref-list { margin-left: 18px; }

  .items {
    width: 100%;
    border-collapse: collapse;
    margin: 12px 0 18px 0;
  }
  .items th, .items td {
    border: 1px solid #333;
    padding: 8px 10px;
    vertical-align: top;
  }
  .items th {
    background:#f7f7f7;
    font-weight:700;
    text-align:left;
  }
  .items .center { text-align:center; }
  .items .right { text-align:right; }

  .signature {
    margin-top: 22px;
    display:flex;
    justify-content:end;
    align-items:center;
    gap:18px;
  }
  .sign-line {
    text-align:right;
    min-width:260px;
  }
  .sign-line .name { font-weight:700; letter-spacing:0.4px; }
  .sign-line .title { font-size:13px; margin-top:2px;margin-right: 57px; }

  .info { margin-top: 18px; font-size:13px; }

  .page-footer {
    margin-top: 30px;
    border-top: 1px solid #e0e0e0;
    padding-top: 8px;
    font-size:13px;
    text-align:center;
    color:#222;
  }

  .small { font-size:13px; }
  .underline { text-decoration: underline; }

  /* PRINT STYLING */
  @page {
    size: A4;
    margin: 10mm;
  }

  @media print {
    body {
      width: 210mm;
      height: 297mm;
      margin: 0 auto;
      padding: 20mm;
      box-sizing: border-box;
      font-size: 14px;
    }

    .header, .meta, .doc-title, .refs, .content, .signature, .info, .page-footer {
      page-break-inside: avoid;
    }

    .items {
      page-break-inside: avoid;
    }
    thead {
      display: table-header-group; /* repeat headers on new page */
    }

    .page-footer {
      position: fixed;
      bottom: 10mm;
      left: 0;
      right: 0;
      border-top: 1px solid #e0e0e0;
      padding-top: 6px;
      font-size: 12px;
      background: #fff;
    }

    .company-title,
    .company-sub,
    .doc-title,
    .page-footer {
      white-space: nowrap;
    }
  }
</style>
</head>
<body>

<div class="header">
  <div class="hdr-left">
    <div class="company-title">Anchor Development &amp; Construction Company</div>
    <div class="company-sub">ADCC (South), Karachi Ph # 021-34684792</div>
  </div>
  <div class="hdr-right">
    <img src="{{ public_path('assets/img/logo/adcc.png') }}" class="logo">
  </div>
</div>



<div class="meta">
  <div class="left small">
    <strong>ADCC (South)/25/ {{ $poItem->po_number }}</strong>
    {{-- <span style="display:inline-block; width:Auto; border-bottom:1px solid #000; margin-left:6px; padding-left:6px;"> --}}
    {{-- </span> --}}
    <div style="margin-top:6px;">
      <strong>{{ $poItem->supplier->name ?? '-' }}</strong><br />
      Karachi
    </div>
  </div>
  <div class="right small" >
    <div style="text-align:right;margin-top:-30px !important;" >
      {{ $poItem->po_date ? \Carbon\Carbon::parse($poItem->po_date)->format('d F Y') : '-' }}
    </div>
  </div>
</div>

<div class="doc-title">
  SUPPLY ORDER FOR PROVISION OF {{ $poItem->items->first()->item->item ?? '-' }} - CONSTRUCTION OF 70 X HOUSES PROJECT AT NHS MAURIPUR KARACHI
</div>

<div class="refs">
  <p><strong>References</strong></p>
  <div class="ref-list">
      {{-- <p>B. Addendum A to MOU No. {{ $poItem->mou_no }} / {{ $poItem->po_number }} {{ $poItem->po_date ? \Carbon\Carbon::parse($poItem->po_date)->format('d F Y') : '-' }}.</p> --}}
    </div>
</div>

<div class="content">
    <p>A. MOU No. {{ $poItem->supplier->mou_no ?? '-' }} {{$poItem->supplier->mou_date ? \Carbon\Carbon::parse($poItem->supplier->mou_date)->format('d F Y') : '-'}}</p>
  <!-- <p>1. Kindly supply following quantities of OP {{ $poItem->items->first()->item->item ?? '-' }} ({{ $poItem->items->first()->item->deno ?? '-' }}) conforming to BSS-12 standards respectively. (OPC in 03 x Ply Paper Bag / PP Bag of weight 50 Kg each) inclusive all applicable taxes, loading / unloading, delivery at site in all respect for Construction of 70 Houses Project at NHS Mauripur Site.</p> -->

 @if(optional($poItem->supplier)->addendum_no)
    <p>
        B. Addendum A to MOU No. {{ optional($poItem->supplier)->mou_no ?? '-' }}
        {{ optional($poItem->supplier)->addendum_no }}
        {{ optional($poItem->supplier)->addendum_date
            ? 'date: ' . \Carbon\Carbon::parse(optional($poItem->supplier)->addendum_date)->format('d F Y')
            : '' }}
    </p>
@endif
  <p>1. Kindly supply the following quantities of {{ $poItem->items->first()->item->item ?? '-' }} ({{ $poItem->items->first()->item->deno ?? '-' }}) as per the agreed standards, rates of {{ $poItem->items->first()->rate ?? '-' }} and terms and conditions, including all applicable taxes, loading/unloading, and delivery at site in all respects for Construction of 70 Houses Project at NHS Mauripur Site.</p>

   <table class="items" aria-label="items table">
    <thead>
        <tr style="text-align: center;">
            <th style="width:5%; text-align: center;">S#</th>
            <th style="width:22%; text-align: center;">Description</th>
            <th style="width:7%; text-align: center;">Deno</th>
            <th style="width:7%; text-align: center;">Size</th>
            <th style="width:33%; text-align: center;">Contractor's Site</th>
            <th style="width:7%; text-align: center;">Qty Required</th>
            <th style="width:26%; text-align: center;">Delivery Schedule</th>
        </tr>
    </thead>
    <tbody>
    @php $rowIndex = 1; @endphp

    @foreach ($poItem->items as $item)
        @php
            $contractors = collect();

            if ($item->itemDemand && $item->itemDemand->contractors->isNotEmpty()) {
                foreach ($item->itemDemand->contractors as $contractor) {
                    $contractors->push($contractor->name);
                }
            }

            $contractorNames = $contractors->unique()->join(', ');
        @endphp

        <tr>
            <td class="center">{{ $rowIndex++ }}</td>
            <td>{{ $item->item->item ?? '-' }}</td>
            <td class="center">{{ $item->item->deno ?? '-' }}</td>
            <td class="center">{{ $item->item->size ?? '-' }}</td>
            <td>{{ $contractorNames ?: 'N/A' }}</td>
            <td class="center">{{ number_format($item->qty, 2) }}</td>
            <td class="center">{{ \Carbon\Carbon::parse($poItem->delivery_date)->format('Y-m-d') }}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="5" class="right"><strong>Total</strong></td>
        <td class="center"><strong>{{ number_format($poItem->items->sum('qty'), 2) }}</strong></td>
        <td></td>
    </tr>
</tbody>

    {{-- <tbody>
        @php $grandTotal = 0; @endphp

        @foreach($poItem->items as $itemIndex => $item)
            <tr>
                <td class="center">{{ $itemIndex == 0 ? 'a.' : $itemIndex+1 }}</td>
                <td>{{ $item->item->item ?? '-' }}</td>
                <td class="center">{{ $item->item->deno ?? '-' }}</td>
                <td class="center">{{ $item->item->size ?? '-' }}</td>
                <td>
                    @if($poItem->demands->isNotEmpty())
                        @foreach($poItem->demands as $demand)
                            @foreach($demand->contractors as $contractor)
                                @if($contractor->pivot->item_id == $item->item_id)
                                    {{ $contractor->name }}
                                 @endif
                            @endforeach
                        @endforeach
                    @else
                        -
                    @endif

                </td>
                <td class="center">
                    {{ $item->qty ?? '-' }}
                </td>
                <td class="center">{{ $poItem->delivery_date }}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="5" class="right"><strong>Total</strong></td>
            <td class="center"><strong>{{ $poItem->items->sum('qty') }}</strong></td>
            <td></td>
        </tr>
    </tbody> --}}
 </table>

  <p>2. Date and time of delivery of material if any may be coordinated with the ADCC (Site Store Manager) Mr. Osama Yousuf (Contact Number 0314-6625167). You are requested to inform this office about the delivery timings to enable the nominated rep to check the delivery on arrival at site.</p>

  <p>3. It is further requested that delivery challan along with invoice against the supply duly signed by the rep of ADCC / contractor as recipient may please be provided to this office for payment.</p>
</div>

<div class="signature">
  <div class="sign-line">
    <div style="height:68px; display:flex; align-items:flex-end; justify-content:center;">
      <svg width="220" height="60" xmlns="http://www.w3.org/2000/svg">
        <line x1="0" y1="50" x2="220" y2="50" stroke="#000" stroke-width="1.5"/>
      </svg>
    </div>
    <div class="name">ABDUL WAHEED SOHAIL</div>
    <div class="title">Chief Executive Officer</div>
  </div>
</div>

<div class="info">
  <p><strong>Information:</strong></p>
  <p>Snr Project Manager (NHS Mauripur), ADCC</p>
  <p>Store Office (NHS Mauripur), ADCC</p>
</div>

<div class="page-footer">
  Street No. 8 Near Al Rehman Masjid Naval Housing Scheme, Phase - I, Karsaz, Karachi. &nbsp;&nbsp;
  Email: <span class="underline">adcc.south@gmail.com</span>
</div>

</body>
</html>
