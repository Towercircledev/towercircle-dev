<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { margin: 36px 40px; }
    body { font-family: "DejaVu Sans", sans-serif; font-size: 10.5px; color: #1f1d2b; }
    .top { width: 100%; border-bottom: 3px solid #4a1a8c; padding-bottom: 14px; }
    .top td { vertical-align: top; }
    h1 { font-size: 22px; margin: 0; color: #4a1a8c; letter-spacing: 1px; }
    .muted { color: #6b6880; }
    table.items { width: 100%; border-collapse: collapse; margin-top: 22px; }
    table.items th { background: #f3f0fb; text-align: left; padding: 8px; font-size: 10px; text-transform: uppercase; letter-spacing: .5px; }
    table.items td { padding: 9px 8px; border-bottom: 1px solid #eceaf4; }
    .r { text-align: right; }
    .totals { width: 45%; margin-left: 55%; margin-top: 10px; border-collapse: collapse; }
    .totals td { padding: 5px 8px; }
    .grand td { font-size: 13px; font-weight: bold; border-top: 2px solid #4a1a8c; color: #4a1a8c; }
    .box { margin-top: 26px; padding: 12px 14px; background: #faf9fe; border: 1px solid #eceaf4; }
    .stamp { display: inline-block; padding: 4px 12px; border: 2px solid; font-weight: bold; font-size: 12px; letter-spacing: 1px; }
</style>
</head>
<body>
@php
    $gstLabel = (float) $p->gst_rate > 0;
    $title = $gstLabel && ! empty($inv['gstin']) ? 'TAX INVOICE' : 'INVOICE';
    $half = intdiv($p->gst_amount, 2);
@endphp
<table class="top">
    <tr>
        <td style="width:55%">
            @if ($logo)<img src="{{ $logo }}" style="height:38px"><br><br>@endif
            <strong style="font-size:12px">{{ $inv['legal_name'] ?? site('name') }}</strong><br>
            @if (! empty($inv['address'])){!! nl2br(e($inv['address'])) !!}<br>@endif
            {{ site('email') }} · +91 {{ site('phone') }}<br>
            @if (! empty($inv['gstin']))GSTIN: {{ $inv['gstin'] }}@endif
        </td>
        <td class="r">
            <h1>{{ $title }}</h1>
            <p style="margin:8px 0 0">
                <span class="muted">Invoice no.</span> <strong>{{ $p->number }}</strong><br>
                <span class="muted">Date</span> {{ $p->invoice_date->format('d M Y') }}<br>
                @if ($p->due_on && $p->status !== 'paid')<span class="muted">Due</span> {{ $p->due_on->format('d M Y') }}<br>@endif
            </p>
            @if ($p->status === 'paid')
                <p><span class="stamp" style="color:#1a8f4a;border-color:#1a8f4a">PAID {{ $p->paid_on?->format('d M Y') }}</span></p>
            @endif
        </td>
    </tr>
</table>

<p style="margin-top:18px">
    <span class="muted">BILL TO</span><br>
    <strong style="font-size:12px">{{ $p->client->name }}</strong><br>
    @if ($p->client->contact_name){{ $p->client->contact_name }}<br>@endif
    @if ($p->client->address){!! nl2br(e($p->client->address)) !!}<br>@endif
    @if ($p->client->phone)+91 {{ $p->client->phone }}@endif @if ($p->client->email) · {{ $p->client->email }}@endif<br>
    @if ($p->client->gstin)GSTIN: {{ $p->client->gstin }}@endif
</p>

<table class="items">
    <thead><tr><th style="width:6%">#</th><th>Description</th><th class="r" style="width:22%">Amount</th></tr></thead>
    <tbody><tr><td>1</td><td>{{ $p->description }}</td><td class="r">{{ inr($p->amount) }}</td></tr></tbody>
</table>

<table class="totals">
    <tr><td class="muted">Subtotal</td><td class="r">{{ inr($p->amount) }}</td></tr>
    @if ($gstLabel)
        @if ($sameState)
            <tr><td class="muted">CGST {{ rtrim(rtrim(number_format($p->gst_rate / 2, 2), '0'), '.') }}%</td><td class="r">{{ inr($half) }}</td></tr>
            <tr><td class="muted">SGST {{ rtrim(rtrim(number_format($p->gst_rate / 2, 2), '0'), '.') }}%</td><td class="r">{{ inr($p->gst_amount - $half) }}</td></tr>
        @else
            <tr><td class="muted">IGST {{ rtrim(rtrim(number_format($p->gst_rate, 2), '0'), '.') }}%</td><td class="r">{{ inr($p->gst_amount) }}</td></tr>
        @endif
    @endif
    <tr class="grand"><td>Total</td><td class="r">{{ inr($p->total) }}</td></tr>
</table>

@if ($p->status !== 'paid' && (! empty($inv['upi']) || ! empty($inv['bank'])))
    <div class="box">
        <strong>How to pay</strong><br>
        @if (! empty($inv['upi']))UPI: {{ $inv['upi'] }}<br>@endif
        @if (! empty($inv['bank'])){!! nl2br(e($inv['bank'])) !!}@endif
    </div>
@endif

@if (! empty($inv['footer']))
    <p class="muted" style="margin-top:22px">{!! nl2br(e($inv['footer'])) !!}</p>
@endif
<p class="muted" style="margin-top:30px;text-align:center">Thank you for your business · {{ url('/') }}</p>
</body>
</html>
