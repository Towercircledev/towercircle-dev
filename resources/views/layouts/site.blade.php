@php
    $siteName = site('name');
    $title = trim($__env->yieldContent('title')) ?: $siteName;
    $fullTitle = str_contains($title, $siteName) ? $title : $title.' | '.$siteName;
    $description = trim($__env->yieldContent('description')) ?: site('footer_about');
    $image = trim($__env->yieldContent('image')) ?: media(site('share_image'), 'img/hero.webp');
    $canonical = trim($__env->yieldContent('canonical')) ?: url()->current();
    $ga4 = \App\Models\Setting::get('tracking.ga4');
    $adsId = \App\Models\Setting::get('tracking.ads_id');
    $pixel = \App\Models\Setting::get('tracking.meta_pixel');
    $landing = trim($__env->yieldContent('landing')) === '1';
@endphp
<!doctype html>
<html lang="en-IN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($description), 160) }}">
    <link rel="canonical" href="{{ $canonical }}">
    @if (trim($__env->yieldContent('noindex')) || \App\Models\Setting::get('site.noindex'))<meta name="robots" content="noindex, nofollow">@endif
    @if ($sc = \App\Models\Setting::get('tracking.search_console'))<meta name="google-site-verification" content="{{ $sc }}">@endif
    <meta name="theme-color" content="#2b1b8a">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($description), 200) }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $image }}">
    <meta property="og:locale" content="en_IN">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    @yield('preload')
    @vite(['resources/css/site.css', 'resources/js/site.js'])
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        'name' => $siteName,
        'url' => url('/'),
        'logo' => asset('img/logo.png'),
        'image' => asset('img/icon-512.png'),
        'email' => site('email'),
        'telephone' => '+91'.site('phone'),
        'address' => array_filter(['@type' => 'PostalAddress', 'streetAddress' => site('address'), 'addressLocality' => site('city'), 'addressCountry' => 'IN']),
        'areaServed' => 'IN',
        'sameAs' => array_values(array_filter([site('instagram'), site('facebook'), site('linkedin'), site('youtube')])),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @stack('schema')
    @if ($ga4 || $adsId)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4 ?: $adsId }}"></script>
        <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());@if($ga4)gtag('config','{{ $ga4 }}');@endif @if($adsId)gtag('config','{{ $adsId }}');@endif</script>
    @endif
    @if ($pixel)
        <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','{{ $pixel }}');fbq('track','PageView');</script>
    @endif
    @stack('head')
</head>
<body>
    @include('partials.icons')
    <a class="skip" href="#main">Skip to content</a>

    @unless ($landing)
        <div class="topbar">
            <div class="container">
                <div class="info">
                    <a href="mailto:{{ site('email') }}"><svg class="icon"><use href="#i-mail"/></svg>{{ site('email') }}</a>
                    <a href="tel:+91{{ site('phone') }}"><svg class="icon"><use href="#i-phone"/></svg>+91 {{ substr(site('phone'), 0, 5) }} {{ substr(site('phone'), 5) }}</a>
                    @if (site('hours'))<span class="hide-sm" style="display:inline-flex;gap:6px;align-items:center"><svg class="icon"><use href="#i-clock"/></svg>{{ site('hours') }}</span>@endif
                </div>
                @include('partials.socials')
            </div>
        </div>
    @endunless

    <header class="header {{ $landing ? 'lp-header' : '' }}">
        <div class="container">
            <a href="{{ route('home') }}" class="logo" aria-label="{{ $siteName }} home">
                <img src="{{ asset('img/logo.webp') }}" alt="{{ $siteName }}" width="135" height="38">
            </a>
            @unless ($landing)
                @php($navServices = \Illuminate\Support\Facades\Cache::remember('nav.services', 600, fn () => \App\Models\Service::visible()->get(['title', 'slug'])->toArray()))
                @php($navServices = collect($navServices)->map(fn ($s) => (object) $s))
                <nav class="nav" id="nav" aria-label="Main">
                    <ul>
                        <li><a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a></li>
                        <li class="has-sub">
                            <a href="{{ route('services') }}" @class(['active' => request()->routeIs('services*')])>Services <svg class="icon" style="width:14px"><use href="#i-down"/></svg></a>
                            @if ($navServices->isNotEmpty())
                                <ul class="sub">
                                    @foreach ($navServices as $s)
                                        <li><a href="{{ route('services.show', $s->slug) }}">{{ $s->title }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                        <li><a href="{{ route('work') }}" @class(['active' => request()->routeIs('work*')])>Work</a></li>
                        <li><a href="{{ route('about') }}" @class(['active' => request()->routeIs('about', 'team')])>About</a></li>
                        @if (page_on('blog'))<li><a href="{{ route('blog') }}" @class(['active' => request()->routeIs('blog*')])>Blog</a></li>@endif
                        <li><a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact')])>Contact</a></li>
                        <li class="mobile-cta"><a href="{{ route('contact') }}" class="btn btn-primary btn-block" style="border:0">Get a Free Quote</a></li>
                    </ul>
                </nav>
                <a href="{{ route('contact') }}" class="btn btn-primary btn-sm cta">Get a Free Quote</a>
                <button class="menu-btn" aria-label="Open menu" aria-expanded="false" aria-controls="nav"><svg class="icon"><use href="#i-menu"/></svg></button>
            @else
                <a href="tel:+91{{ site('phone') }}" class="btn btn-light btn-sm lp-phone" style="margin-left:auto"><svg class="icon"><use href="#i-phone"/></svg> <span>+91 {{ site('phone') }}</span></a>
                <a href="#quote" class="btn btn-primary btn-sm lp-quote">Get a Free Quote</a>
            @endunless
        </div>
    </header>

    <main id="main">
        @yield('content')
    </main>

    @unless ($landing)
        <footer class="footer">
            <div class="container grid g-4">
                <div>
                    <a href="{{ route('home') }}" class="logo"><img src="{{ asset('img/logo.webp') }}" alt="{{ $siteName }}" width="142" height="40" loading="lazy"></a>
                    <p>{{ site('footer_about') }}</p>
                    @include('partials.socials')
                </div>
                <div>
                    <h3>Services</h3>
                    <ul>
                        @forelse ($navServices->take(6) as $s)
                            <li><a href="{{ route('services.show', $s->slug) }}">{{ $s->title }}</a></li>
                        @empty
                            <li><a href="{{ route('services') }}">All services</a></li>
                        @endforelse
                    </ul>
                </div>
                <div>
                    <h3>Company</h3>
                    <ul>
                        <li><a href="{{ route('about') }}">About us</a></li>
                        <li><a href="{{ route('work') }}">Our work</a></li>
                        @if (page_on('team'))<li><a href="{{ route('team') }}">Team</a></li>@endif
                        @if (page_on('blog'))<li><a href="{{ route('blog') }}">Blog</a></li>@endif
                        @if (page_on('faq'))<li><a href="{{ route('faq') }}">FAQ</a></li>@endif
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                    @php($footerLocations = \Illuminate\Support\Facades\Cache::remember('nav.locations', 600, fn () => \App\Models\Location::visible()->get(['city', 'slug'])->toArray()))
                    @if ($footerLocations)
                        <h3 style="margin-top:26px">Areas we serve</h3>
                        <ul>
                            @foreach ($footerLocations as $l)
                                <li><a href="{{ route('location', $l['slug']) }}">{{ $l['city'] }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div>
                    <h3>Get in touch</h3>
                    <ul class="contact">
                        @if (site('address'))<li><svg class="icon"><use href="#i-pin"/></svg><span>{{ site('address') }}</span></li>@endif
                        <li><svg class="icon"><use href="#i-phone"/></svg><a href="tel:+91{{ site('phone') }}">+91 {{ site('phone') }}</a></li>
                        <li><svg class="icon"><use href="#i-mail"/></svg><a href="mailto:{{ site('email') }}">{{ site('email') }}</a></li>
                        <li><svg class="icon"><use href="#i-wa"/></svg><a href="{{ wa_link('Hi Towercircle, I need help with a website.') }}" target="_blank" rel="noopener">Chat on WhatsApp</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <div class="container">
                    <span>© {{ date('Y') }} {{ $siteName }}. All rights reserved. · <a href="{{ route('privacy') }}">Privacy Policy</a> · <a href="{{ route('terms') }}">Terms</a></span>
                    <span>{{ site('tagline') }}</span>
                </div>
            </div>
        </footer>
    @endunless

    <a href="{{ wa_link('Hi Towercircle, I need help with a website.') }}" class="wa-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"><svg class="icon"><use href="#i-wa"/></svg></a>
    <div class="mobile-bar">
        <a href="tel:+91{{ site('phone') }}"><svg class="icon"><use href="#i-phone"/></svg> Call</a>
        <a href="{{ wa_link('Hi Towercircle, I need help with a website.') }}" target="_blank" rel="noopener"><svg class="icon"><use href="#i-wa"/></svg> WhatsApp</a>
    </div>
    @stack('scripts')
</body>
</html>
