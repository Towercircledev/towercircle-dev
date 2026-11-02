<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Client extends Model
{
    public const STATUSES = ['active' => 'Active', 'paused' => 'Paused', 'former' => 'Former'];

    protected $guarded = ['id'];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function sites(): HasMany
    {
        return $this->hasMany(ClientSite::class);
    }

    public function credentials(): HasMany
    {
        return $this->hasMany(Credential::class);
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(Renewal::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ClientFile::class);
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject')->latest('happened_at');
    }

    public function outstanding(): int
    {
        return (int) $this->payments()->where('status', 'pending')->sum('total');
    }

    public function whatsapp(?string $text = null): ?string
    {
        $n = substr(preg_replace('/\D/', '', (string) $this->phone), -10);

        return strlen($n) === 10 ? 'https://wa.me/91'.$n.($text ? '?text='.rawurlencode($text) : '') : null;
    }
}
