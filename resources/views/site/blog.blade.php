@extends('layouts.site')

@section('title', 'Blog – Website, Shopify & Online Growth Tips')
@section('description', 'Practical guides on websites, Shopify, Google ranking and getting more customers online, from the Towercircle team.')

@section('content')
    @include('partials.page-title', ['title' => 'Blog', 'lead' => 'Practical tips to get more customers from your website.'])

    <section class="section">
        <div class="container">
            @if ($posts->isEmpty())
                <p class="empty-note">No posts yet.</p>
            @else
                <div class="grid g-3">
                    @foreach ($posts as $item)
                        @include('partials.cards', ['type' => 'post'])
                    @endforeach
                </div>
                @if ($posts->hasPages())
                    <nav class="pager" aria-label="Pages">
                        @if ($posts->previousPageUrl())<a href="{{ $posts->previousPageUrl() }}" rel="prev">←</a>@endif
                        @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $n => $u)
                            @if ($n === $posts->currentPage())<span class="on">{{ $n }}</span>@else<a href="{{ $u }}">{{ $n }}</a>@endif
                        @endforeach
                        @if ($posts->nextPageUrl())<a href="{{ $posts->nextPageUrl() }}" rel="next">→</a>@endif
                    </nav>
                @endif
            @endif
        </div>
    </section>
@endsection
