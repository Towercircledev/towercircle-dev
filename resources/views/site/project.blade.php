@extends('layouts.site')

@section('title', $project->seo_title ?: $project->title.' – '.($project->category ?: 'Project'))
@section('description', $project->seo_description ?: $project->summary)
@section('image', media($project->cover))

@section('content')
    @include('partials.page-title', ['title' => $project->title, 'parent' => ['Work', route('work')], 'lead' => $project->summary])

    <section class="section">
        <div class="container">
            <div class="cover-img"><img src="{{ media($project->cover) }}" alt="{{ $project->title }} website screenshot" fetchpriority="high"></div>
            <div class="layout-side">
                <div>
                    <div class="prose">{!! $project->body !!}</div>
                    @if (! empty($project->highlights))
                        <ul class="ticks" style="margin-top:24px">
                            @foreach ($project->highlights as $h)
                                <li><svg class="icon"><use href="#i-check"/></svg>{{ is_array($h) ? ($h['item'] ?? '') : $h }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if (! empty($project->gallery))
                        <div class="grid g-2 gallery">
                            @foreach ($project->gallery as $g)
                                <img src="{{ media($g) }}" alt="{{ $project->title }} screenshot" loading="lazy">
                            @endforeach
                        </div>
                    @endif
                </div>
                <aside>
                    <div class="side-box">
                        <ul class="info-list">
                            @if ($project->client)<li><svg class="icon"><use href="#i-user"/></svg><div><span>Client</span><b>{{ $project->client }}</b></div></li>@endif
                            @if ($project->category)<li><svg class="icon"><use href="#i-tag"/></svg><div><span>Type</span><b>{{ $project->category }}</b></div></li>@endif
                            @if ($project->completed_on)<li><svg class="icon"><use href="#i-cal"/></svg><div><span>Completed</span><b>{{ $project->completed_on->format('F Y') }}</b></div></li>@endif
                            @if ($project->location)<li><svg class="icon"><use href="#i-pin"/></svg><div><span>Location</span><b>{{ $project->location }}</b></div></li>@endif
                        </ul>
                        @if ($project->live_url)
                            <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="btn btn-primary btn-block" style="margin-top:16px">Visit live website <svg class="icon"><use href="#i-link"/></svg></a>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if ($more->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <div class="section-head"><h2>More Projects</h2></div>
                <div class="grid g-2">
                    @foreach ($more as $item)
                        @include('partials.cards', ['type' => 'project'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('partials.cta', ['pt' => '96px'])
@endsection
