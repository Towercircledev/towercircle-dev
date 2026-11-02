@extends('layouts.site')

@section('landing', '1')
@section('title', $lp->seo_title ?: $lp->c('hero.heading'))
@section('description', $lp->seo_description ?: $lp->c('hero.text'))
@if ($lp->noindex)@section('noindex', '1')@endif

@php
    $t = fn ($x) => is_array($x) ? ($x['p'] ?? '') : (string) $x;
    $on = fn (string $k) => (bool) $lp->c("$k.show", true);
    $heading = e($lp->c('hero.heading'));
    if ($hl = $lp->c('hero.highlight')) {
        $heading = preg_replace('/'.preg_quote(e($hl), '/').'/', '<span class="mark">'.e($hl).'</span>', $heading, 1);
    }
    $wa = wa_link($lp->c('final.whatsapp_text'));
    $button = $lp->cta_label ?: $lp->c('form.button');
    $img = function (array $step) {
        if (! empty($step['image'])) {
            return media(is_array($step['image']) ? reset($step['image']) : $step['image']);
        }

        return ! empty($step['default_image']) ? asset($step['default_image']) : null;
    };
@endphp

@if ($faqs = $lp->c('faq.items'))
@push('schema')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'FAQPage',
    'mainEntity' => collect($faqs)->map(fn ($f) => ['@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']]])->values(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
@endif

@section('content')
<div class="ec">
    {{-- HERO --}}
    <section class="hero ec-hero">
        <div class="container split" style="align-items:start">
            <div>
                @if ($lp->c('hero.badge'))<span class="badge">{{ $lp->c('hero.badge') }}</span>@endif
                <h1>{!! $heading !!}</h1>
                <p class="lead">{{ $lp->c('hero.text') }}</p>
                <div class="platforms">@foreach ($lp->c('hero.platforms', []) as $x)<span>{{ $t($x) }}</span>@endforeach</div>
                <ul class="ticks hero-points">
                    @foreach ($lp->c('hero.points', []) as $x)<li><svg class="icon"><use href="#i-check"/></svg>{{ $t($x) }}</li>@endforeach
                </ul>
                <div class="trust">@foreach ($lp->c('hero.trust', []) as $x)<span><svg class="icon"><use href="#i-check"/></svg>{{ $t($x) }}</span>@endforeach</div>
            </div>
            <div class="form-card" id="quote">
                <h2>{{ $lp->form_heading ?: $lp->c('form.heading') }}</h2>
                <p style="font-size:14px">{{ $lp->c('form.text') }}</p>
                @include('partials.lead-form-ecom', ['fid' => 'ec1', 'button' => $button])
            </div>
        </div>
    </section>

    {{-- PAIN POINTS --}}
    @if ($on('pain'))
        <section class="section pain">
            <div class="container">
                <div class="section-head"><span class="eyebrow">{{ $lp->c('pain.eyebrow') }}</span><h2>{{ $lp->c('pain.heading') }}</h2></div>
                <div class="grid g-4 pain-grid">
                    @foreach ($lp->c('pain.items', []) as $it)
                        <div class="card reveal"><div class="emoji">{{ $it['emoji'] ?? '' }}</div><h3>{{ $it['title'] }}</h3><p>{{ $it['text'] ?? '' }}</p></div>
                    @endforeach
                </div>
                @if ($lp->c('pain.answer'))<p class="pain-answer">{{ $lp->c('pain.answer') }}</p>@endif
            </div>
        </section>
    @endif

    {{-- JOURNEY --}}
    @if ($on('journey'))
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head"><span class="eyebrow">{{ $lp->c('journey.eyebrow') }}</span><h2>{{ $lp->c('journey.heading') }}</h2><p>{{ $lp->c('journey.text') }}</p></div>
                @foreach ($lp->c('journey.steps', []) as $i => $st)
                    <div class="feature-row {{ $i % 2 ? 'flip' : '' }} reveal">
                        <div class="feature-media">@if ($src = $img($st))<img src="{{ $src }}" alt="{{ $st['heading'] }}" loading="lazy">@endif<span class="feature-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span></div>
                        <div class="feature-text">
                            @if (! empty($st['eyebrow']))<span class="eyebrow">{{ $st['eyebrow'] }}</span>@endif
                            <h2>{{ $st['heading'] }}</h2>
                            <div class="prose">{!! $st['body'] ?? '' !!}</div>
                            @if (! empty($st['points']))
                                <ul class="ticks feature-points">@foreach ($st['points'] as $x)<li><svg class="icon"><use href="#i-check"/></svg>{{ $t($x) }}</li>@endforeach</ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- PLATFORM COMPARISON --}}
    @if ($on('compare'))
        <section class="section">
            <div class="container">
                <div class="section-head"><span class="eyebrow">{{ $lp->c('compare.eyebrow') }}</span><h2>{{ $lp->c('compare.heading') }}</h2><p>{{ $lp->c('compare.text') }}</p></div>
                <div class="compare-wrap">
                    <table class="compare">
                        <thead><tr><th></th><th>{{ $lp->c('compare.col1') }}</th><th>{{ $lp->c('compare.col2') }}</th><th>{{ $lp->c('compare.col3') }}</th></tr></thead>
                        <tbody>
                            @foreach ($lp->c('compare.rows', []) as $r)
                                <tr><th>{{ $r['label'] }}</th><td>{{ $r['a'] ?? '' }}</td><td>{{ $r['b'] ?? '' }}</td><td>{{ $r['c'] ?? '' }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($lp->c('compare.button'))<div class="center" style="margin-top:28px"><a href="#quote" class="btn btn-light">{{ $lp->c('compare.button') }}</a></div>@endif
            </div>
        </section>
    @endif

    {{-- PACKAGES --}}
    @if ($on('packages'))
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head"><span class="eyebrow">{{ $lp->c('packages.eyebrow') }}</span><h2>{{ $lp->c('packages.heading') }}</h2><p>{{ $lp->c('packages.text') }}</p></div>
                <div class="grid g-3 packages">
                    @foreach ($lp->c('packages.items', []) as $pk)
                        <div class="pkg {{ ! empty($pk['popular']) ? 'pop' : '' }} reveal">
                            @if (! empty($pk['popular']))<span class="tag">Most popular</span>@endif
                            <h3>{{ $pk['name'] }}</h3>
                            <p class="for">{{ $pk['for'] ?? '' }}</p>
                            <div class="price">{{ $pk['price'] ?? 'Get quote' }}@if (! empty($pk['price_note']))<small>{{ $pk['price_note'] }}</small>@endif</div>
                            <ul>@foreach ($pk['features'] ?? [] as $x)<li><svg class="icon"><use href="#i-check"/></svg>{{ $t($x) }}</li>@endforeach</ul>
                            <a href="#quote" class="btn {{ ! empty($pk['popular']) ? 'btn-primary' : 'btn-light' }} btn-block">{{ $pk['button'] ?? 'Get a quote' }}</a>
                        </div>
                    @endforeach
                </div>
                @if ($lp->c('packages.note'))<p class="center" style="margin-top:26px;font-size:14.5px;color:var(--muted)">{{ $lp->c('packages.note') }} <a href="#quote" style="color:var(--plum);font-weight:600">Get a quote →</a></p>@endif
            </div>
        </section>
    @endif

    {{-- WHAT'S INCLUDED --}}
    @if ($on('included'))
        <section class="section">
            <div class="container">
                <div class="section-head"><span class="eyebrow">{{ $lp->c('included.eyebrow') }}</span><h2>{{ $lp->c('included.heading') }}</h2></div>
                <div class="grid g-4">
                    @foreach ($lp->c('included.items', []) as $it)
                        <div class="inc-card reveal"><div class="icon-box grad"><svg class="icon"><use href="#i-{{ $it['icon'] ?? 'check' }}"/></svg></div><h3>{{ $it['title'] }}</h3><p>{{ $it['text'] ?? '' }}</p></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- TIMELINE --}}
    @if ($on('timeline'))
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head"><span class="eyebrow">{{ $lp->c('timeline.eyebrow') }}</span><h2>{{ $lp->c('timeline.heading') }}</h2><p>{{ $lp->c('timeline.text') }}</p></div>
                <div class="timeline">
                    @foreach ($lp->c('timeline.items', []) as $it)
                        <div class="tl reveal"><div class="dot">{{ $it['day'] }}</div><h3>{{ $it['title'] }}</h3><p>{{ $it['text'] ?? '' }}</p></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- PROOF: only shown when real projects / reviews exist --}}
    @if ($projects->isNotEmpty() || $reviews->isNotEmpty())
        <section class="section">
            <div class="container">
                @if ($projects->isNotEmpty())
                    <div class="section-head"><span class="eyebrow">{{ $lp->c('proof.eyebrow') }}</span><h2>{{ $lp->c('proof.heading') }}</h2></div>
                    <div class="grid g-{{ min(max($projects->count(), 2), 3) }}">
                        @foreach ($projects as $item)
                            @include('partials.cards', ['type' => 'project'])
                        @endforeach
                    </div>
                @endif
                @if ($reviews->isNotEmpty())
                    <div class="section-head" style="margin-top:{{ $projects->isNotEmpty() ? '70px' : '0' }}"><h2>{{ $lp->c('proof.reviews_heading') }}</h2></div>
                    <div class="reviews">
                        @foreach ($reviews as $item)
                            @include('partials.cards', ['type' => 'review'])
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if ($stats = $lp->c('proof.stats'))
        <section class="stats">
            <div class="container grid g-4">
                @foreach ($stats as $s)<div class="stat"><strong>{{ $s['number'] }}</strong><span class="lbl">{{ $s['label'] }}</span></div>@endforeach
            </div>
        </section>
    @endif

    {{-- GUARANTEES --}}
    @if ($on('guarantees'))
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head"><span class="eyebrow">{{ $lp->c('guarantees.eyebrow') }}</span><h2>{{ $lp->c('guarantees.heading') }}</h2></div>
                <div class="grid g-4 guarantees">
                    @foreach ($lp->c('guarantees.items', []) as $it)
                        <div class="card reveal"><div class="icon-box grad"><svg class="icon"><use href="#i-{{ $it['icon'] ?? 'check' }}"/></svg></div><h3>{{ $it['title'] }}</h3><p>{{ $it['text'] ?? '' }}</p></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQ --}}
    @if ($faqs)
        <section class="section">
            <div class="container faq" style="max-width:860px">
                <div class="section-head"><span class="eyebrow">{{ $lp->c('faq.eyebrow') }}</span><h2>{{ $lp->c('faq.heading') }}</h2></div>
                @foreach ($faqs as $f)
                    <details @if ($loop->first) open @endif><summary>{{ $f['q'] }}</summary><div>{{ $f['a'] }}</div></details>
                @endforeach
            </div>
        </section>
    @endif

    {{-- FINAL CTA --}}
    <section class="section final-cta">
        <div class="container split" style="align-items:center">
            <div>
                <h2>{{ $lp->c('final.heading') }}</h2>
                <p style="font-size:17px">{{ $lp->c('final.text') }}</p>
                <ul class="ticks" style="margin-top:16px">
                    @foreach ($lp->c('final.points', []) as $x)<li style="color:#fff"><svg class="icon" style="color:#fff"><use href="#i-check"/></svg>{{ $t($x) }}</li>@endforeach
                </ul>
                <a href="{{ $wa }}" class="btn btn-wa" style="margin-top:18px" target="_blank" rel="noopener"><svg class="icon"><use href="#i-wa"/></svg> WhatsApp us now</a>
            </div>
            <div class="form-card">@include('partials.lead-form-ecom', ['fid' => 'ec2', 'button' => $button])</div>
        </div>
    </section>
</div>

<footer class="copyright center" style="margin:0;background:#150f2e;color:#b8b3d1"><div class="container" style="justify-content:center">© {{ date('Y') }} {{ site('name') }} · <a href="{{ route('home') }}">towercircle.in</a> · <a href="{{ route('privacy') }}">Privacy Policy</a> · <a href="{{ route('terms') }}">Terms</a></div></footer>
@endsection
