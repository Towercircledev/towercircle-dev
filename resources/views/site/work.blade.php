@extends('layouts.site')

@section('title', 'Our Work – Websites & Shopify Stores We\'ve Built')
@section('description', 'See websites, Shopify stores and web apps Towercircle has designed and built for businesses across India.')

@section('content')
    @include('partials.page-title', ['title' => 'Our Work', 'crumb' => 'Work', 'lead' => 'A selection of the 100+ websites and stores we\'ve launched.'])

    <section class="section">
        <div class="container">
            @if ($projects->isEmpty())
                <p class="empty-note">Projects will appear here once they are added in the admin.</p>
            @else
                @if ($categories->count() > 1)
                    <div class="filters" role="group" aria-label="Filter projects">
                        <button class="on" data-filter="*">All</button>
                        @foreach ($categories as $c)
                            <button data-filter="{{ $c }}">{{ $c }}</button>
                        @endforeach
                    </div>
                @endif
                <div class="grid g-2">
                    @foreach ($projects as $item)
                        @include('partials.cards', ['type' => 'project'])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @include('partials.cta', ['heading' => 'Want a Website Like These?'])
@endsection
