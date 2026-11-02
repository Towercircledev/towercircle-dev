@extends('layouts.site')

@section('title', $title)
@section('description', $title.' of '.site('name').'.')

@section('content')
    @include('partials.page-title', ['title' => $title])

    <section class="section">
        <div class="container" style="max-width:860px">
            <p style="color:var(--muted);font-size:14px">Last updated: {{ \Illuminate\Support\Carbon::parse(\App\Models\Setting::get('legal.updated', '2026-11-02'))->format('d F Y') }}</p>
            <div class="prose">{!! $body !!}</div>
        </div>
    </section>
@endsection
