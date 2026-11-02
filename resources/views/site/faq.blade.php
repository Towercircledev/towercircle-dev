@extends('layouts.site')

@section('title', 'Frequently Asked Questions')
@section('description', 'Answers to common questions about website and Shopify store pricing, timelines, hosting and support at Towercircle.')

@if ($faqs->isNotEmpty())
@push('schema')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'FAQPage',
    'mainEntity' => $faqs->map(fn ($f) => ['@type' => 'Question', 'name' => $f->question, 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer]])->values(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
@endif

@section('content')
    @include('partials.page-title', ['title' => 'Frequently Asked Questions', 'crumb' => 'FAQ'])

    <section class="section">
        <div class="container" style="max-width:860px">
            @forelse ($faqs as $f)
                <div class="faq"><details @if ($loop->first) open @endif><summary>{{ $f->question }}</summary><div>{!! nl2br(e($f->answer)) !!}</div></details></div>
            @empty
                <p class="empty-note">Questions will appear here once they are added in the admin.</p>
            @endforelse
        </div>
    </section>

    @include('partials.cta', ['heading' => 'Still Have a Question?'])
@endsection
