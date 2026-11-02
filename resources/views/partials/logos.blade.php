@foreach (['client' => 'client_heading', 'platform' => 'platform_heading'] as $group => $headingKey)
    @if (! empty($logos[$group]) && $logos[$group]->isNotEmpty())
        <section class="logo-strip" aria-label="{{ home('logos.'.$headingKey) }}">
            <div class="container">
                <h2>{{ home('logos.'.$headingKey) }}</h2>
                <div class="logo-row {{ $group }}s">
                    @foreach ($logos[$group] as $logo)
                        @if ($logo->url)
                            <a href="{{ $logo->url }}" target="_blank" rel="noopener nofollow" title="{{ $logo->name }}"><img src="{{ media($logo->image) }}" alt="{{ $logo->name }}" loading="lazy" height="46"></a>
                        @else
                            <span title="{{ $logo->name }}"><img src="{{ media($logo->image) }}" alt="{{ $logo->name }}" loading="lazy" height="46"></span>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endforeach
