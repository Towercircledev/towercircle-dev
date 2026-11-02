@extends('layouts.site')

@section('title', $service->seo_title ?: $service->title)
@section('description', $service->seo_description ?: $service->short_description)

@push('schema')
<script type="application/ld+json">{!! json_encode(array_filter([
    '@context' => 'https://schema.org', '@type' => 'Service',
    'name' => $service->title, 'description' => $service->short_description,
    'provider' => ['@type' => 'Organization', 'name' => site('name'), 'url' => url('/')],
    'areaServed' => 'IN',
    'offers' => $service->price_from ? ['@type' => 'Offer', 'priceCurrency' => 'INR', 'price' => $service->price_from] : null,
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@if (! empty($service->faqs))
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'FAQPage',
    'mainEntity' => collect($service->faqs)->map(fn ($f) => ['@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']]])->values(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
@endpush

@section('content')
    @include('partials.page-title', ['title' => $service->title, 'parent' => ['Services', route('services')], 'lead' => $service->short_description])

    {{-- Intro + quote form --}}
    <section class="section">
        <div class="container layout-side">
            <div>
                <div class="prose">{!! $service->body !!}</div>
                <div class="trust" style="margin-top:26px">
                    <span><svg class="icon"><use href="#i-check"/></svg>100+ websites delivered</span>
                    <span><svg class="icon"><use href="#i-check"/></svg>Fixed price before we start</span>
                    <span><svg class="icon"><use href="#i-check"/></svg>WhatsApp support</span>
                </div>
            </div>
            <aside class="sticky">
                <div class="side-box">
                    @if ($service->price_from)
                        <div class="price-tag">Starting from <strong>{{ inr($service->price_from) }}</strong>@if ($service->price_note)<small>{{ $service->price_note }}</small>@endif</div>
                        <hr style="border:0;border-top:1px solid var(--line);margin:18px 0">
                    @endif
                    <h3>Get a free quote</h3>
                    @include('partials.lead-form', ['form' => 'quote', 'compact' => true, 'fixedService' => $service->title, 'button' => 'Get My Quote'])
                </div>
            </aside>
        </div>
    </section>

    {{-- Image + text sections, alternating --}}
    @if (! empty($service->sections))
        <section class="section bg-soft" style="padding-top:80px">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">How we work</span>
                    <h2>How We Deliver Your {{ \Illuminate\Support\Str::of($service->title)->before('(')->trim() }}</h2>
                    <p>A clear, step-by-step process — so you always know what's happening and what comes next.</p>
                </div>
                @foreach ($service->sections as $i => $sec)
                    @php($img = ! empty($sec['image']) ? media(is_array($sec['image']) ? reset($sec['image']) : $sec['image']) : (! empty($sec['default_image']) ? asset($sec['default_image']) : null))
                    <div class="feature-row {{ $i % 2 ? 'flip' : '' }} reveal">
                        <div class="feature-media">
                            @if ($img)<img src="{{ $img }}" alt="{{ $sec['heading'] }}" loading="lazy">@endif
                            <span class="feature-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="feature-text">
                            @if (! empty($sec['eyebrow']))<span class="eyebrow">{{ $sec['eyebrow'] }}</span>@endif
                            <h2>{{ $sec['heading'] }}</h2>
                            <div class="prose">{!! $sec['body'] !!}</div>
                            @if (! empty($sec['points']))
                                <ul class="ticks feature-points">
                                    @foreach ($sec['points'] as $p)
                                        <li><svg class="icon"><use href="#i-check"/></svg>{{ is_array($p) ? ($p['p'] ?? '') : $p }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- What's included --}}
    @if (! empty($service->features))
        <section class="section">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">What's included</span>
                    <h2>Everything You Get</h2>
                </div>
                <div class="grid g-3 include-grid">
                    @foreach ($service->features as $f)
                        <div class="include-item reveal"><span class="icon-box grad"><svg class="icon"><use href="#i-check"/></svg></span>{{ is_array($f) ? ($f['item'] ?? '') : $f }}</div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Projects --}}
    @if ($projects->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head"><span class="eyebrow">Recent work</span><h2>Projects We've Delivered</h2></div>
                <div class="grid g-2">
                    @foreach ($projects as $item)
                        @include('partials.cards', ['type' => 'project'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQs --}}
    @if (! empty($service->faqs))
        <section class="section">
            <div class="container" style="max-width:860px">
                <div class="section-head"><span class="eyebrow">FAQ</span><h2>Common Questions</h2></div>
                <div class="faq">
                    @foreach ($service->faqs as $f)
                        <details @if ($loop->first) open @endif><summary>{{ $f['q'] }}</summary><div>{{ $f['a'] }}</div></details>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Other services --}}
    @if ($others->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head"><h2>Other Services</h2></div>
                <div class="grid g-3">
                    @foreach ($others as $item)
                        @include('partials.cards', ['type' => 'service'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('partials.cta', ['heading' => 'Let\'s Talk About Your Project', 'fixedService' => $service->title, 'pt' => '96px'])
@endsection
