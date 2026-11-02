@extends('layouts.site')

@section('title', $location->seo_title ?: $location->heading)
@section('description', $location->seo_description ?: $location->intro)

@push('schema')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'ProfessionalService',
    'name' => site('name').' – '.$location->city, 'url' => url()->current(),
    'telephone' => '+91'.site('phone'), 'email' => site('email'),
    'areaServed' => ['@type' => 'City', 'name' => $location->city],
    'parentOrganization' => ['@type' => 'Organization', 'name' => site('name'), 'url' => url('/')],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@if (! empty($location->faqs))
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'FAQPage',
    'mainEntity' => collect($location->faqs)->map(fn ($f) => ['@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']]])->values(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
@endpush

@section('content')
    @include('partials.page-title', ['title' => $location->heading, 'crumb' => $location->city, 'lead' => $location->intro])

    <section class="section">
        <div class="container layout-side">
            <div>
                <div class="prose">{!! $location->body !!}</div>

                @if ($services->isNotEmpty())
                    <h2 style="margin-top:40px">Our services in {{ $location->city }}</h2>
                    <div class="grid g-2">
                        @foreach ($services as $item)
                            @include('partials.cards', ['type' => 'service'])
                        @endforeach
                    </div>
                @endif

                @if (! empty($location->faqs))
                    <h2 style="margin-top:50px">Questions</h2>
                    <div class="faq">
                        @foreach ($location->faqs as $f)
                            <details><summary>{{ $f['q'] }}</summary><div>{{ $f['a'] }}</div></details>
                        @endforeach
                    </div>
                @endif
            </div>
            <aside class="sticky">
                <div class="side-box">
                    <h3>Get a free quote in {{ $location->city }}</h3>
                    @include('partials.lead-form', ['form' => 'quote', 'compact' => true])
                </div>
            </aside>
        </div>
    </section>

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
@endsection
