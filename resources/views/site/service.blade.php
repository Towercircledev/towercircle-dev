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

    <section class="section">
        <div class="container layout-side">
            <div>
                <div class="prose">{!! $service->body !!}</div>

                @if (! empty($service->features))
                    <h2 style="margin-top:40px">What's included</h2>
                    <ul class="ticks grid g-2" style="gap:4px 26px">
                        @foreach ($service->features as $f)
                            <li><svg class="icon"><use href="#i-check"/></svg>{{ is_array($f) ? ($f['item'] ?? '') : $f }}</li>
                        @endforeach
                    </ul>
                @endif

                @if (! empty($service->faqs))
                    <h2 style="margin-top:50px">Common questions</h2>
                    <div class="faq">
                        @foreach ($service->faqs as $f)
                            <details><summary>{{ $f['q'] }}</summary><div>{{ $f['a'] }}</div></details>
                        @endforeach
                    </div>
                @endif

                @if ($projects->isNotEmpty())
                    <h2 style="margin-top:50px">Recent work</h2>
                    <div class="grid g-2">
                        @foreach ($projects as $item)
                            @include('partials.cards', ['type' => 'project'])
                        @endforeach
                    </div>
                @endif
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
                @if ($others->isNotEmpty())
                    <div class="side-box">
                        <h3>Other services</h3>
                        <ul>
                            @foreach ($others as $o)
                                <li style="margin-bottom:8px"><a href="{{ route('services.show', $o) }}" class="link-arrow">{{ $o->title }} <svg class="icon"><use href="#i-arrow"/></svg></a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </section>
@endsection
