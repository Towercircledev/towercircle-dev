<div class="socials">
    @foreach (['instagram' => 'Instagram', 'facebook' => 'Facebook', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube'] as $k => $label)
        @if ($url = site($k))
            <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $label }}"><svg class="icon"><use href="#i-{{ $k }}"/></svg></a>
        @endif
    @endforeach
</div>
