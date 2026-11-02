@extends('layouts.site')

@section('title', 'Services – Websites, Shopify Stores, Domains & Hosting')
@section('description', 'Shopify store development, custom business websites, domains, hosting and website speed & SEO from Towercircle. Clear prices, fast delivery.')

@section('content')
    @include('partials.page-title', ['title' => 'Our Services', 'crumb' => 'Services', 'lead' => 'Pick what you need — or tell us your goal and we\'ll suggest the right mix.'])

    <section class="section">
        <div class="container">
            @if ($services->isEmpty())
                <p class="empty-note">Services will appear here once they are added in the admin.</p>
            @else
                <div class="grid g-3">
                    @foreach ($services as $item)
                        @include('partials.cards', ['type' => 'service'])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @include('partials.cta')
@endsection
