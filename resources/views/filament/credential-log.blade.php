<div class="text-sm">
    @forelse ($views as $v)
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(0,0,0,.06)">
            <span><strong>{{ $v->user?->name ?? 'Unknown' }}</strong> · {{ $v->action }}</span>
            <span style="opacity:.7">{{ $v->created_at->format('d M Y, h:i A') }} · {{ $v->ip }}</span>
        </div>
    @empty
        <p style="opacity:.7">Not viewed yet.</p>
    @endforelse
</div>
