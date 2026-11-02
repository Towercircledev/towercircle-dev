@php($fid = $fid ?? 'ec')
<form method="POST" action="{{ route('lead.store') }}" class="form lead-form" novalidate>
    @csrf
    <input type="hidden" name="form" value="ecommerce">
    <input type="hidden" name="_t" value="">
    <div class="hp" aria-hidden="true"><label>Leave empty<input type="text" name="company_url" tabindex="-1" autocomplete="off"></label></div>
    @if ($errors->any())<div class="alert" role="alert">Please check the highlighted fields.</div>@endif
    <div class="row">
        <div class="field">
            <label for="{{ $fid }}-name">Your name *</label>
            <input id="{{ $fid }}-name" name="name" value="{{ old('name') }}" required autocomplete="name">
            @error('name')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="{{ $fid }}-phone">Mobile / WhatsApp *</label>
            <input id="{{ $fid }}-phone" name="phone" type="tel" inputmode="tel" value="{{ old('phone') }}" required autocomplete="tel" placeholder="10-digit number">
            @error('phone')<span class="err">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="field">
        <label for="{{ $fid }}-sell">What do you sell?</label>
        <input id="{{ $fid }}-sell" name="extra[Sells]" value="{{ old('extra.Sells') }}" placeholder="e.g. handmade jewellery, organic snacks">
    </div>
    <div class="row">
        <div class="field">
            <label for="{{ $fid }}-platform">Platform</label>
            <select id="{{ $fid }}-platform" name="service">
                @foreach (['E-commerce store – not sure' => 'Not sure — recommend', 'E-commerce store – Shopify' => 'Shopify', 'E-commerce store – WooCommerce' => 'WooCommerce', 'E-commerce store – Custom' => 'Custom e-commerce'] as $v => $l)
                    <option value="{{ $v }}" @selected(old('service') === $v)>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="{{ $fid }}-brand">Do you have a brand?</label>
            <select id="{{ $fid }}-brand" name="extra[Brand]">
                @foreach (['No, I need branding', 'Yes, logo ready', 'Partly'] as $v)
                    <option @selected(old('extra.Brand') === $v)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="field">
            <label for="{{ $fid }}-budget">Budget</label>
            <select id="{{ $fid }}-budget" name="budget">
                <option value="">Select a range</option>
                @foreach (\App\Models\Lead::BUDGETS as $k => $v)
                    <option value="{{ $k }}" @selected(old('budget') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="{{ $fid }}-timeline">When do you want to launch?</label>
            <select id="{{ $fid }}-timeline" name="timeline">
                @foreach (\App\Models\Lead::TIMELINES as $k => $v)
                    <option value="{{ $k }}" @selected(old('timeline') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-block">{{ $button }} <svg class="icon"><use href="#i-arrow"/></svg></button>
    <p class="form-note">We reply within a few hours on WhatsApp. No spam. <a href="{{ route('privacy') }}" style="text-decoration:underline">Privacy policy</a></p>
</form>
