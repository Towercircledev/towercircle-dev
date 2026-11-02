@extends('layouts.site')

@section('title', $post->seo_title ?: $post->title)
@section('description', $post->seo_description ?: $post->excerpt)
@if ($post->cover)@section('image', media($post->cover))@endif

@push('schema')
<script type="application/ld+json">{!! json_encode(array_filter([
    '@context' => 'https://schema.org', '@type' => 'BlogPosting',
    'headline' => $post->title, 'description' => $post->excerpt,
    'image' => $post->cover ? media($post->cover) : null,
    'datePublished' => $post->published_at?->toIso8601String(), 'dateModified' => $post->updated_at->toIso8601String(),
    'author' => ['@type' => 'Organization', 'name' => site('name')],
    'publisher' => ['@type' => 'Organization', 'name' => site('name'), 'logo' => ['@type' => 'ImageObject', 'url' => asset('img/logo.png')]],
    'mainEntityOfPage' => url()->current(),
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    @include('partials.page-title', ['title' => $post->title, 'parent' => ['Blog', route('blog')], 'crumb' => \Illuminate\Support\Str::limit($post->title, 40)])

    <section class="section">
        <div class="container layout-side">
            <article>
                @if ($post->cover)<div class="cover-img"><img src="{{ media($post->cover) }}" alt="" fetchpriority="high"></div>@endif
                <p style="color:var(--muted);font-size:14px">{{ $post->published_at->format('d M Y') }}@if ($post->author) · {{ $post->author }}@endif</p>
                <div class="prose">{!! $post->body !!}</div>
                @if (! empty($post->tags))
                    <p style="margin-top:30px;display:flex;gap:8px;flex-wrap:wrap">
                        @foreach ($post->tags as $t)<span class="eyebrow" style="margin:0">{{ $t }}</span>@endforeach
                    </p>
                @endif
            </article>
            <aside class="sticky">
                <div class="side-box">
                    <h3>Need help with your website?</h3>
                    @include('partials.lead-form', ['form' => 'quote', 'compact' => true, 'button' => 'Get a Free Quote'])
                </div>
                @if ($recent->isNotEmpty())
                    <div class="side-box">
                        <h3>Recent posts</h3>
                        <ul>
                            @foreach ($recent as $r)
                                <li style="margin-bottom:12px"><a href="{{ route('blog.show', $r) }}" style="color:var(--ink);font-weight:500">{{ $r->title }}</a><br><small style="color:var(--muted)">{{ $r->published_at->format('d M Y') }}</small></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </section>
@endsection
