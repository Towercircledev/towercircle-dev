<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/** A dated timeline entry on a lead or client: call, WhatsApp, meeting, proposal, note… */
class Activity extends Model
{
    public const TYPES = [
        'call' => 'Call', 'whatsapp' => 'WhatsApp', 'email' => 'Email', 'meeting' => 'Meeting',
        'proposal' => 'Proposal / quote sent', 'note' => 'Note', 'payment' => 'Payment', 'system' => 'System',
    ];

    public const ICONS = [
        'call' => 'heroicon-o-phone', 'whatsapp' => 'heroicon-o-chat-bubble-left-right', 'email' => 'heroicon-o-envelope',
        'meeting' => 'heroicon-o-users', 'proposal' => 'heroicon-o-document-text', 'note' => 'heroicon-o-pencil-square',
        'payment' => 'heroicon-o-banknotes', 'system' => 'heroicon-o-cog-6-tooth',
    ];

    protected $guarded = ['id'];

    protected $casts = ['happened_at' => 'datetime'];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(Model $subject, string $type, string $body): void
    {
        $subject->morphMany(self::class, 'subject')->create([
            'type' => $type, 'body' => $body, 'user_id' => auth()->id(), 'happened_at' => now(),
        ]);
    }
}
