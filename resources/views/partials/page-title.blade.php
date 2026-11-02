<section class="page-title">
    <div class="container">
        <h1>{{ $title }}</h1>
        @isset($lead)<p>{{ $lead }}</p>@endisset
        <ol class="crumbs" aria-label="Breadcrumb">
            <li><a href="{{ route('home') }}">Home</a></li>
            @isset($parent)<li><a href="{{ $parent[1] }}">{{ $parent[0] }}</a></li>@endisset
            <li aria-current="page">{{ $crumb ?? $title }}</li>
        </ol>
    </div>
</section>
@push('schema')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
    'itemListElement' => array_values(array_filter([
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
        isset($parent) ? ['@type' => 'ListItem', 'position' => 2, 'name' => $parent[0], 'item' => $parent[1]] : null,
        ['@type' => 'ListItem', 'position' => isset($parent) ? 3 : 2, 'name' => $crumb ?? $title, 'item' => url()->current()],
    ])),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
