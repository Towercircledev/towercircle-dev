@extends('layouts.site')

@section('title', 'Page not found')
@section('noindex', '1')

@section('content')
    <section class="page-title" style="padding:110px 0">
        <div class="container">
            <h1 class="grad-text" style="font-size:5rem;margin:0">404</h1>
            <h2>This page doesn't exist</h2>
            <p>It may have moved. Try one of these instead.</p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:24px">
                <a href="{{ route('home') }}" class="btn btn-primary">Go to homepage</a>
                <a href="{{ route('services') }}" class="btn btn-light">Our services</a>
                <a href="{{ route('contact') }}" class="btn btn-light">Contact us</a>
            </div>
        </div>
    </section>
@endsection
