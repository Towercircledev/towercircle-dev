@php
    $form = $form ?? 'contact';
    $compact = $compact ?? false;
    $services = $services ?? \App\Models\Service::visible()->pluck('title');
    $button = $button ?? 'Get My Free Quote';
@endphp
<form method="POST" action="{{ route('lead.store') }}" class="form lead-form" novalidate>
    @csrf
    <input type="hidden" name="form" value="{{ $form }}">
    <input type="hidden" name="_t" value="">
    <div class="hp" aria-hidden="true"><label>Leave empty<input type="text" name="company_url" tabindex="-1" autocomplete="off"></label></div>
    @if ($errors->any())
        <div class="alert" role="alert">Please check the highlighted fields.</div>
    @endif

    <div class="row">
        <div class="field">
            <label for="{{ $form }}-name">Your name *</label>
            <input id="{{ $form }}-name" name="name" value="{{ old('name') }}" required autocomplete="name">
            @error('name')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="{{ $form }}-phone">Mobile / WhatsApp *</label>
            <input id="{{ $form }}-phone" name="phone" type="tel" inputmode="tel" value="{{ old('phone') }}" required autocomplete="tel" placeholder="10-digit number">
            @error('phone')<span class="err">{{ $message }}</span>@enderror
        </div>
    </div>

    @if ($form === 'audit')
        <div class="field">
            <label for="audit-website">Your website *</label>
            <input id="audit-website" name="website" value="{{ old('website') }}" required placeholder="www.yourbusiness.com">
            <input type="hidden" name="service" value="Free website audit">
        </div>
    @else
        <div class="row">
            <div class="field">
                <label for="{{ $form }}-email">Email</label>
                <input id="{{ $form }}-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email">
                @error('email')<span class="err">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="{{ $form }}-service">I need help with</label>
                @if (! empty($fixedService))
                    <input id="{{ $form }}-service" name="service" value="{{ $fixedService }}" readonly>
                @else
                    <select id="{{ $form }}-service" name="service">
                        <option value="">Choose a service</option>
                        @foreach ($services as $s)
                            <option @selected(old('service', $selectedService ?? null) === $s)>{{ $s }}</option>
                        @endforeach
                        <option @selected(old('service') === 'Something else')>Something else</option>
                    </select>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="field">
                <label for="{{ $form }}-budget">Budget</label>
                <select id="{{ $form }}-budget" name="budget">
                    <option value="">Select a range</option>
                    @foreach (\App\Models\Lead::BUDGETS as $k => $v)
                        <option value="{{ $k }}" @selected(old('budget') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="{{ $form }}-timeline">When do you want to start?</label>
                <select id="{{ $form }}-timeline" name="timeline">
                    <option value="">Select</option>
                    @foreach (\App\Models\Lead::TIMELINES as $k => $v)
                        <option value="{{ $k }}" @selected(old('timeline') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @unless ($compact)
            <div class="field">
                <label for="{{ $form }}-message">Tell us about your project</label>
                <textarea id="{{ $form }}-message" name="message" rows="4" placeholder="Your business, what you need, any website you like…">{{ old('message') }}</textarea>
            </div>
        @endunless
    @endif

    <button type="submit" class="btn btn-primary btn-block">{{ $button }} <svg class="icon"><use href="#i-arrow"/></svg></button>
    <p class="form-note">We reply within a few hours on WhatsApp. No spam. <a href="{{ route('privacy') }}" style="text-decoration:underline">Privacy policy</a></p>
</form>
