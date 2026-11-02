@extends('layouts.site')

@section('title', 'Contact Us – Get a Free Website Quote')
@section('description', 'Call, WhatsApp or send us your requirement. Towercircle replies within a few hours with a clear quote for your website or Shopify store.')

@section('content')
    @include('partials.page-title', ['title' => 'Let\'s Talk About Your Website', 'crumb' => 'Contact', 'lead' => 'Tell us what you need. We reply within a few hours with ideas and a clear price.'])

    <section class="section">
        <div class="container">
            <div class="grid g-3" style="margin-bottom:60px">
                <div class="card contact-box">
                    <div class="icon-box grad"><svg class="icon"><use href="#i-phone"/></svg></div>
                    <div><h3>Call us</h3><p><a href="tel:+91{{ site('phone') }}">+91 {{ site('phone') }}</a></p>@if (site('hours'))<p style="font-size:13.5px;color:var(--muted)">{{ site('hours') }}</p>@endif</div>
                </div>
                <div class="card contact-box">
                    <div class="icon-box" style="background:#1fa855;color:#fff;box-shadow:none"><svg class="icon" style="width:26px;height:26px"><use href="#i-wa"/></svg></div>
                    <div><h3>WhatsApp</h3><p><a href="{{ wa_link('Hi Towercircle, I need help with a website.') }}" target="_blank" rel="noopener">Chat with us now</a></p><p style="font-size:13.5px;color:var(--muted)">Fastest way to reach us</p></div>
                </div>
                <div class="card contact-box">
                    <div class="icon-box grad"><svg class="icon"><use href="#i-mail"/></svg></div>
                    <div><h3>Email</h3><p><a href="mailto:{{ site('email') }}">{{ site('email') }}</a></p>@if (site('address'))<p style="font-size:13.5px;color:var(--muted)">{{ site('address') }}</p>@endif</div>
                </div>
            </div>

            <div class="split" style="align-items:start">
                <div>
                    <span class="eyebrow">Get a free quote</span>
                    <h2>Tell Us About Your Project</h2>
                    <p>Share a few details and we'll come back with a plan, timeline and price — usually the same day.</p>
                    <ul class="ticks" style="margin-top:20px">
                        <li><svg class="icon"><use href="#i-check"/></svg>Free consultation, no obligation</li>
                        <li><svg class="icon"><use href="#i-check"/></svg>Fixed price before we start</li>
                        <li><svg class="icon"><use href="#i-check"/></svg>100+ websites delivered</li>
                    </ul>
                </div>
                <div class="form-card">
                    @include('partials.lead-form', ['form' => 'contact', 'services' => $services, 'selectedService' => request('service')])
                </div>
            </div>

            @if (site('map_embed'))
                <div class="map"><iframe src="{{ site('map_embed') }}" loading="lazy" title="Our location on Google Maps" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
            @endif
        </div>
    </section>
@endsection
