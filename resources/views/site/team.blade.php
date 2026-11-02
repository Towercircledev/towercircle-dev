@extends('layouts.site')

@section('title', 'Our Team')
@section('description', 'Meet the Towercircle team of designers and developers building websites and Shopify stores.')

@section('content')
    @include('partials.page-title', ['title' => 'Our Team', 'crumb' => 'Team', 'lead' => 'The people who design, build and support your website.'])

    <section class="section">
        <div class="container">
            @if ($team->isEmpty())
                <p class="empty-note">Team members will appear here once they are added in the admin.</p>
            @else
                <div class="grid g-4">
                    @foreach ($team as $item)
                        @include('partials.cards', ['type' => 'team'])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @include('partials.cta')
@endsection
