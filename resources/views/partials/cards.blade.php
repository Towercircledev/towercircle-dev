{{-- Usage: @include('partials.cards', ['type' => 'service|project|review|post|team', 'item' => $model]) --}}
@switch($type)
    @case('service')
        <article class="card service-card reveal">
            <div class="icon-box">
                @if ($item->icon)
                    <img src="{{ media($item->icon) }}" alt="" width="34" height="34" loading="lazy">
                @else
                    <img src="{{ asset('img/s'.(($loop->index ?? 0) % 6 + 1).'.webp') }}" alt="" width="34" height="34" loading="lazy">
                @endif
            </div>
            <h3><a href="{{ route('services.show', $item) }}">{{ $item->title }}</a></h3>
            <p>{{ $item->short_description }}</p>
            @if ($item->price_from)<div class="price">Starting from <strong>{{ inr($item->price_from) }}</strong></div>@endif
            <a href="{{ route('services.show', $item) }}" class="link-arrow">Learn more <svg class="icon"><use href="#i-arrow"/></svg></a>
        </article>
        @break

    @case('project')
        <a href="{{ route('work.show', $item) }}" class="project reveal" data-cat="{{ $item->category }}">
            <div class="shot"><img src="{{ media($item->cover) }}" alt="{{ $item->title }} website" loading="lazy" width="640" height="400"></div>
            <div class="meta">
                <div><small>{{ $item->category }}</small><h3>{{ $item->title }}</h3></div>
                <span class="go"><svg class="icon"><use href="#i-arrow"/></svg></span>
            </div>
        </a>
        @break

    @case('review')
        <figure class="review" style="margin:0">
            <div class="who">
                <div class="avatar">
                    @if ($item->photo)<img src="{{ media($item->photo) }}" alt="" loading="lazy">@else{{ mb_substr($item->name, 0, 1) }}@endif
                </div>
                <div><h3>{{ $item->name }}</h3>@if ($item->company)<small>{{ $item->company }}</small>@endif</div>
            </div>
            <blockquote>“{{ $item->quote }}”</blockquote>
            <div class="stars" aria-label="{{ $item->rating }} out of 5 stars">{{ str_repeat('★', $item->rating) }}</div>
        </figure>
        @break

    @case('post')
        <article class="post-card reveal">
            @if ($item->cover)<a href="{{ route('blog.show', $item) }}" class="cover"><img src="{{ media($item->cover) }}" alt="" loading="lazy"></a>@endif
            <div class="body">
                <span class="date"><svg class="icon"><use href="#i-cal"/></svg>{{ $item->published_at->format('d M Y') }}</span>
                <h3><a href="{{ route('blog.show', $item) }}">{{ $item->title }}</a></h3>
                <p>{{ \Illuminate\Support\Str::limit($item->excerpt, 120) }}</p>
                <a href="{{ route('blog.show', $item) }}" class="link-arrow" style="margin-top:auto">Read more <svg class="icon"><use href="#i-arrow"/></svg></a>
            </div>
        </article>
        @break

    @case('team')
        <div class="team-card reveal">
            <div class="photo">
                @if ($item->photo)<img src="{{ media($item->photo) }}" alt="{{ $item->name }}" loading="lazy" width="400" height="400">@endif
            </div>
            <h3>{{ $item->name }}</h3>
            <span>{{ $item->role }}</span>
        </div>
        @break
@endswitch
