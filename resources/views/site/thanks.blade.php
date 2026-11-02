@extends('layouts.site')

@section('title', 'Thank You')
@section('noindex', '1')

@php
    $adsId = \App\Models\Setting::get('tracking.ads_id');
    $label = \App\Models\Setting::get('tracking.ads_lead_label');
    $fresh = session()->has('lead_id');
@endphp

@if ($fresh)
@push('scripts')
<script>
    if (typeof gtag === 'function') {
        gtag('event', 'generate_lead', { currency: 'INR' });
        @if ($adsId && $label)gtag('event', 'conversion', { send_to: '{{ $adsId }}/{{ $label }}', transaction_id: 'lead-{{ session('lead_id') }}' });@endif
    }
    if (typeof fbq === 'function') fbq('track', 'Lead', {}, { eventID: 'lead-{{ session('lead_id') }}' });
</script>
@endpush
@endif

@section('content')
    <section class="page-title" style="padding:110px 0">
        <div class="container">
            <div class="icon-box grad" style="margin:0 auto 24px;width:76px;height:76px"><svg class="icon" style="width:36px;height:36px"><use href="#i-check"/></svg></div>
            <h1>Thank you! We've got your message.</h1>
            <p>We'll reply on WhatsApp or call you within a few hours (Mon – Sat). Need us sooner?</p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:28px">
                <a href="{{ wa_link('Hi Towercircle, I just sent an enquiry on your website.') }}" class="btn btn-wa" target="_blank" rel="noopener"><svg class="icon"><use href="#i-wa"/></svg> WhatsApp us now</a>
                <a href="{{ route('work') }}" class="btn btn-light">See our work</a>
            </div>
        </div>
    </section>
@endsection
