@extends('layouts.site')

@section('title', 'About Us – Website & Shopify Experts')
@section('description', home('about.text'))

@section('content')
    @include('partials.page-title', ['title' => 'About Towercircle', 'crumb' => 'About', 'lead' => 'A small, focused team that builds websites and online stores which bring real customers.'])

    <section class="section">
        <div class="container split">
            <div class="about-img reveal"><img src="{{ asset('img/about.webp') }}" alt="" width="700" height="507" loading="lazy"></div>
            <div>
                <span class="eyebrow">{{ home('about.eyebrow') }}</span>
                <h2>{{ home('about.heading') }}</h2>
                <p>{{ home('about.text') }}</p>
                <ul class="ticks">
                    @foreach (home('about.points', []) as $p)
                        <li><svg class="icon"><use href="#i-check"/></svg>{{ $p }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="container grid g-4">
            @foreach (home('stats', []) as $s)
                <div class="stat"><strong><span data-count="{{ (int) $s['number'] }}">{{ (int) $s['number'] }}</span>{{ $s['suffix'] ?? '' }}</strong><span class="lbl">{{ $s['label'] }}</span></div>
            @endforeach
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">How we work</span>
                <h2>{{ home('process.heading') }}</h2>
                <p>{{ home('process.text') }}</p>
            </div>
            <div class="grid g-4 steps">
                @foreach (home('process.steps', []) as $st)
                    <div class="step reveal"><h3>{{ $st['title'] }}</h3><p>{{ $st['text'] }}</p></div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($team->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head"><span class="eyebrow">Our team</span><h2>Meet the Team</h2></div>
                <div class="grid g-4">
                    @foreach ($team as $item)
                        @include('partials.cards', ['type' => 'team'])
                    @endforeach
                </div>
                <div class="center" style="margin-top:36px"><a href="{{ route('team') }}" class="btn btn-light">See the full team</a></div>
            </div>
        </section>
    @endif

    @if ($reviews->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="section-head"><span class="eyebrow">Client reviews</span><h2>What Our Clients Say</h2></div>
                <div class="reviews">
                    @foreach ($reviews as $item)
                        @include('partials.cards', ['type' => 'review'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('partials.cta')
@endsection
