@extends('layouts.site')

@section('title', home('seo.title'))
@section('description', home('seo.description'))
@section('preload')
    <link rel="preload" as="image" href="{{ media(home('hero.image'), 'img/hero.webp') }}" fetchpriority="high">
@endsection

@php
    $show = fn ($k) => (bool) home("show.$k", true);
    $heading = e(home('hero.heading'));
    if ($hl = home('hero.highlight')) {
        $heading = preg_replace('/'.preg_quote(e($hl), '/').'/', '<span class="mark">'.e($hl).'</span>', $heading, 1);
    }
    $icons = ['store', 'code', 'chart', 'bolt'];
@endphp

@section('content')
    {{-- Hero --}}
    <section class="hero">
        <div class="container split">
            <div>
                <span class="eyebrow-text">{{ home('hero.eyebrow') }}</span>
                <h1>{!! $heading !!}</h1>
                <p class="lead">{{ home('hero.text') }}</p>
                <div class="actions">
                    <a href="{{ route('contact') }}" class="btn btn-primary">{{ home('hero.cta') }} <svg class="icon"><use href="#i-arrow"/></svg></a>
                    <a href="{{ wa_link('Hi Towercircle, I want a quote for a website.') }}" class="btn btn-light" target="_blank" rel="noopener"><svg class="icon" style="color:#1fa855"><use href="#i-wa"/></svg> {{ home('hero.cta2') }}</a>
                </div>
                <div class="trust">
                    <span><svg class="icon"><use href="#i-check"/></svg>100+ websites built</span>
                    <span><svg class="icon"><use href="#i-check"/></svg>Reply within hours</span>
                    <span><svg class="icon"><use href="#i-check"/></svg>You own everything</span>
                </div>
            </div>
            <div class="hero-img">
                <img src="{{ media(home('hero.image'), 'img/hero.webp') }}" alt="Towercircle team designing a website" width="900" height="577" fetchpriority="high">
            </div>
        </div>
    </section>

    {{-- 4 highlight boxes --}}
    <section class="container grid g-4 cards-4" aria-label="What we do">
        @foreach (home('highlights') as $i => $h)
            <div class="card reveal">
                <div class="icon-box grad"><svg class="icon"><use href="#i-{{ $icons[$i] ?? 'check' }}"/></svg></div>
                <h3>{{ $h['title'] }}</h3>
                <p>{{ $h['text'] }}</p>
            </div>
        @endforeach
    </section>

    @if ($show('logos'))
        @include('partials.logos')
    @endif

    {{-- About --}}
    @if ($show('about'))
        <section class="section">
            <div class="container split">
                <div class="about-img reveal"><img src="{{ asset('img/about.webp') }}" alt="" width="700" height="507" loading="lazy"></div>
                <div class="reveal">
                    <span class="eyebrow">{{ home('about.eyebrow') }}</span>
                    <h2>{{ home('about.heading') }}</h2>
                    <p>{{ home('about.text') }}</p>
                    <ul class="ticks">
                        @foreach (home('about.points', []) as $p)
                            <li><svg class="icon"><use href="#i-check"/></svg>{{ $p }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('about') }}" class="btn btn-primary" style="margin-top:14px">More about us</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Stats --}}
    @if ($show('stats'))
        <section class="stats">
            <div class="container grid g-4">
                @foreach (home('stats', []) as $s)
                    <div class="stat"><strong><span data-count="{{ (int) $s['number'] }}">{{ (int) $s['number'] }}</span>{{ $s['suffix'] ?? '' }}</strong><span class="lbl">{{ $s['label'] }}</span></div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Services --}}
    @if ($show('services') && $services->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">Our services</span>
                    <h2>Everything Your Business Needs to <span class="mark">Sell Online</span></h2>
                    <p>One team for your website, store, hosting and growth — with clear prices.</p>
                </div>
                <div class="grid g-3">
                    @foreach ($services as $item)
                        @include('partials.cards', ['type' => 'service'])
                    @endforeach
                </div>
                <div class="center" style="margin-top:40px"><a href="{{ route('services') }}" class="btn btn-light">View all services</a></div>
            </div>
        </section>
    @endif

    {{-- Process --}}
    @if ($show('process'))
        <section class="section">
            <div class="container split">
                <div class="reveal">
                    <span class="eyebrow">How we work</span>
                    <h2>{{ home('process.heading') }}</h2>
                    <p>{{ home('process.text') }}</p>
                    <div class="grid steps" style="gap:16px;margin-top:26px">
                        @foreach (home('process.steps', []) as $st)
                            <div class="step"><h3>{{ $st['title'] }}</h3><p>{{ $st['text'] }}</p></div>
                        @endforeach
                    </div>
                </div>
                <div class="reveal"><img src="{{ asset('img/features.webp') }}" alt="" width="700" height="602" loading="lazy"></div>
            </div>
        </section>
    @endif

    {{-- Projects --}}
    @if ($show('projects') && $projects->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">Recent work</span>
                    <h2>Websites We've <span class="mark">Launched</span></h2>
                </div>
                <div class="grid g-2">
                    @foreach ($projects as $item)
                        @include('partials.cards', ['type' => 'project'])
                    @endforeach
                </div>
                <div class="center" style="margin-top:40px"><a href="{{ route('work') }}" class="btn btn-light">See all projects</a></div>
            </div>
        </section>
    @endif

    {{-- Team --}}
    @if ($show('team') && $team->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">Our team</span>
                    <h2>The People Behind <span class="mark">Your Website</span></h2>
                </div>
                <div class="grid g-4">
                    @foreach ($team as $item)
                        @include('partials.cards', ['type' => 'team'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Reviews --}}
    @if ($show('reviews') && $reviews->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">Client reviews</span>
                    <h2>Businesses That <span class="mark">Trust Us</span></h2>
                </div>
                <div class="reviews">
                    @foreach ($reviews as $item)
                        @include('partials.cards', ['type' => 'review'])
                    @endforeach
                </div>
                @if (site('google_reviews'))<div class="center" style="margin-top:30px"><a href="{{ site('google_reviews') }}" class="link-arrow" target="_blank" rel="noopener">Read our Google reviews <svg class="icon"><use href="#i-arrow"/></svg></a></div>@endif
            </div>
        </section>
    @endif

    {{-- Blog --}}
    @if ($show('blog') && $posts->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">From the blog</span>
                    <h2>Tips to Grow Your Business <span class="mark">Online</span></h2>
                </div>
                <div class="grid g-3">
                    @foreach ($posts as $item)
                        @include('partials.cards', ['type' => 'post'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Free audit --}}
    @if ($show('audit'))
        <section class="section" id="audit" style="padding-top:{{ ($show('reviews') && $reviews->isNotEmpty() && !($show('blog') && $posts->isNotEmpty())) ? '96px' : '0' }}">
            <div class="container">
                <div class="cta-box">
                    <div>
                        <h2>{{ home('audit.heading') }}</h2>
                        <p>{{ home('audit.text') }}</p>
                        <ul class="ticks" style="color:#fff">
                            <li style="color:#fff"><svg class="icon" style="color:#fff"><use href="#i-check"/></svg>Speed & mobile check</li>
                            <li style="color:#fff"><svg class="icon" style="color:#fff"><use href="#i-check"/></svg>Google ranking issues</li>
                            <li style="color:#fff"><svg class="icon" style="color:#fff"><use href="#i-check"/></svg>Quick wins to get more enquiries</li>
                        </ul>
                    </div>
                    <div class="form">
                        @include('partials.lead-form', ['form' => 'audit', 'button' => 'Send My Free Audit'])
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
