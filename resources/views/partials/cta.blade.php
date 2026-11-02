<section class="section" style="padding-top:{{ $pt ?? '0' }}">
    <div class="container">
        <div class="cta-box">
            <div>
                <h2>{{ $heading ?? 'Ready to Grow Your Business Online?' }}</h2>
                <p>{{ $text ?? 'Tell us what you need. We\'ll reply within a few hours with ideas, a timeline and a clear price.' }}</p>
                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px">
                    <a href="{{ wa_link('Hi Towercircle, I want a quote for a website.') }}" class="btn btn-wa" target="_blank" rel="noopener"><svg class="icon"><use href="#i-wa"/></svg> WhatsApp us</a>
                    <a href="tel:+91{{ site('phone') }}" class="btn btn-light"><svg class="icon"><use href="#i-phone"/></svg> +91 {{ site('phone') }}</a>
                </div>
            </div>
            <div class="form">
                @include('partials.lead-form', ['form' => 'quote', 'compact' => true, 'fixedService' => $fixedService ?? null])
            </div>
        </div>
    </div>
</section>
