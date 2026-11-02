@extends('layouts.site')

@section('landing', '1')
@section('title', $lp->seo_title ?: $lp->heading)
@section('description', $lp->seo_description ?: $lp->subheading)
@if ($lp->noindex)@section('noindex', '1')@endif

@section('content')
    <section class="hero lp-hero">
        <div class="container split">
            <div>
                @if ($lp->offer)<span class="badge">🎁 {{ $lp->offer }}</span>@endif
                @if ($lp->eyebrow)<span class="eyebrow-text">{{ $lp->eyebrow }}</span>@endif
                <h1>{{ $lp->heading }}</h1>
                @if ($lp->subheading)<p class="lead">{{ $lp->subheading }}</p>@endif
                @if ($lp->price_from)<p class="price-tag" style="margin-bottom:24px">Starting from <strong>{{ inr($lp->price_from) }}</strong></p>@endif
                <div class="trust" style="margin-top:0">
                    <span><svg class="icon"><use href="#i-check"/></svg>100+ websites built</span>
                    <span><svg class="icon"><use href="#i-check"/></svg>Fixed price</span>
                    <span><svg class="icon"><use href="#i-check"/></svg>Reply within hours</span>
                </div>
                @if ($lp->hero_image)
                    <img src="{{ media($lp->hero_image) }}" alt="" style="margin-top:30px;border-radius:16px" loading="lazy">
                @endif
            </div>
            <div class="form-card" id="quote">
                <h2>{{ $lp->form_heading ?: 'Get a free quote' }}</h2>
                <p style="font-size:14px">Fill this in and we'll WhatsApp you within a few hours.</p>
                @include('partials.lead-form', ['form' => 'landing', 'compact' => true, 'fixedService' => $lp->service?->title, 'button' => $lp->cta_label ?: 'Get My Free Quote'])
            </div>
        </div>
    </section>

    @if (! empty($lp->benefits))
        <section class="section">
            <div class="container">
                <div class="section-head"><h2>Why Businesses Choose Towercircle</h2></div>
                <div class="grid g-3">
                    @foreach ($lp->benefits as $b)
                        <div class="card reveal">
                            <div class="icon-box grad"><svg class="icon"><use href="#i-check"/></svg></div>
                            <h3>{{ $b['title'] }}</h3>
                            <p>{{ $b['text'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($projects->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head"><h2>Recent Work</h2></div>
                <div class="grid g-2">
                    @foreach ($projects as $item)
                        @include('partials.cards', ['type' => 'project'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if (! empty($lp->steps))
        <section class="section">
            <div class="container">
                <div class="section-head"><h2>How It Works</h2></div>
                <div class="grid g-{{ min(count($lp->steps), 4) }} steps">
                    @foreach ($lp->steps as $s)
                        <div class="step reveal"><h3>{{ $s['title'] }}</h3><p>{{ $s['text'] ?? '' }}</p></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($reviews->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head"><h2>What Our Clients Say</h2></div>
                <div class="reviews">
                    @foreach ($reviews as $item)
                        @include('partials.cards', ['type' => 'review'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if (! empty($lp->faqs))
        <section class="section">
            <div class="container faq" style="max-width:860px">
                <div class="section-head"><h2>Questions</h2></div>
                @foreach ($lp->faqs as $f)
                    <details><summary>{{ $f['q'] }}</summary><div>{{ $f['a'] }}</div></details>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section center" style="padding-top:0">
        <div class="container">
            <a href="#quote" class="btn btn-primary">{{ $lp->cta_label ?: 'Get My Free Quote' }} <svg class="icon"><use href="#i-arrow"/></svg></a>
        </div>
    </section>
    <footer class="copyright center" style="margin:0"><div class="container" style="justify-content:center">© {{ date('Y') }} {{ site('name') }} · <a href="{{ route('home') }}">towercircle.in</a></div></footer>
@endsection
