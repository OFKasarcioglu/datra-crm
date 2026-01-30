<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">

    <style>
        @page {
            size: A4;
            margin: 14mm 12mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: middle;
        }

        .no-border td {
            border: none;
        }

        .center { text-align: center; }
        .right  { text-align: right; }
        .bold   { font-weight: bold; }
        .small  { font-size: 9.5px; }

        .title {
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            padding: 6px 0;
            border: 1px solid #000;
            margin: 6px 0;
        }

        .signature {
            height: 65px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>

{{-- ================= TARİH ================= --}}
<table class="no-border">
    <tr>
        <td></td>
        <td class="right bold">
            TARİH : {{ \Carbon\Carbon::parse($quote->quote_date)->format('d.m.Y') }}
        </td>
    </tr>
</table>

{{-- ================= BAŞLIK ================= --}}
<div class="title">PROFORMA INVOICE</div>

{{-- ================= LOGO + FİRMA ================= --}}
<table>
    <tr>
        <td width="25%" class="center">
            @if(!empty($setting?->logo))
                <img src="{{ public_path('storage/'.$setting->logo) }}" width="130">
            @endif
        </td>
        <td width="75%" class="center">
            <div class="bold">{{ $setting->company_name ?? '-' }}</div>
            <div>{{ $setting->address ?? '-' }}</div>
            <div>Tel: {{ $setting->phone ?? '-' }}</div>
            <div>E-mail: {{ $setting->email ?? '-' }}</div>
            <div>Web: {{ $setting->website ?? '-' }}</div>
        </td>
    </tr>
</table>

{{-- ================= MÜŞTERİ ================= --}}
<table style="margin-top:6px;">
    <tr>
        <td width="15%" class="bold">SAYIN</td>
        <td width="55%">{{ $quote->customer_name }}</td>
        <td width="15%" class="bold">TEKLİF NO</td>
        <td width="15%">{{ $quote->quote_no }}</td>
    </tr>
    <tr>
        <td class="bold">İLGİLİ KİŞİ</td>
        <td>{{ $quote->contact_person ?: '-' }}</td>
        <td class="bold">TEL</td>
        <td>{{ $quote->contact_phone ?: '-' }}</td>
    </tr>
    <tr>
        <td class="bold">E-MAIL</td>
        <td colspan="3">{{ $quote->contact_email ?: '-' }}</td>
    </tr>
</table>

{{-- ================= KALEM TABLOSU ================= --}}
@php
    $maxRows   = 12;
    $subTotal = 0;
@endphp

<table style="margin-top:8px;">
    <thead>
        <tr class="center bold">
            <th>DESCRIPTION</th>
            <th>DRAWING NO</th>
            <th>DELIVERY DATE</th>
            <th>QTY</th>
            <th>UNIT KG</th>
            <th>TOTAL KG</th>
            <th>€/KG</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
        @foreach($quote->items as $item)
            @php
                $totalKg  = $item->quantity * $item->unit_weight;
                $rowTotal = $totalKg * $item->unit_price;
                $subTotal += $rowTotal;
            @endphp
            <tr class="center">
                <td>{{ $item->description }}</td>
                <td>{{ $item->drawing_number }}</td>
                <td>
                    {{ $item->delivery_date
                        ? \Carbon\Carbon::parse($item->delivery_date)->format('d.m.Y')
                        : '-' }}
                </td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_weight, 0) }}</td>
                <td>{{ number_format($totalKg, 0) }}</td>
                <td>{{ number_format($item->unit_price, 2) }} €</td>
                <td class="right">{{ number_format($rowTotal, 2) }} €</td>
            </tr>
        @endforeach

        {{-- BOŞ SATIRLAR --}}
        @for($i = $quote->items->count(); $i < $maxRows; $i++)
            <tr>
                <td>&nbsp;</td>
                <td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td>
            </tr>
        @endfor
    </tbody>
</table>

{{-- ================= TOPLAM ================= --}}
@php
    $vat        = $subTotal * ($quote->vat_rate / 100);
    $grandTotal = $subTotal + $vat;
@endphp

<table style="width:40%; margin-left:auto; margin-top:6px;">
    <tr>
        <td class="bold">TOTAL</td>
        <td class="right">{{ number_format($subTotal, 2) }} €</td>
    </tr>
    <tr>
        <td class="bold">VAT %{{ $quote->vat_rate }}</td>
        <td class="right">{{ number_format($vat, 2) }} €</td>
    </tr>
    <tr>
        <td class="bold">GRAND TOTAL</td>
        <td class="right bold">{{ number_format($grandTotal, 2) }} €</td>
    </tr>
</table>

{{-- ================= NOTLAR ================= --}}
<table style="margin-top:6px;">
    <tr><td class="bold">NOTES</td></tr>
    <tr><td class="small">1. This document does not replace an invoice.</td></tr>
    <tr><td class="small">2. Prices are determined in kg.</td></tr>
    <tr><td class="small">3. Materials declared above were not used in the products.</td></tr>
    <tr><td class="small">4. Final value based on weighbridge.</td></tr>
</table>

{{-- ================= İMZA ================= --}}
<table style="margin-top:8px;">
    <tr>
        <td class="signature">SIGNATURE / STAMP</td>
    </tr>
</table>

</body>
</html>
