<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Renewal extends Model
{
    public const TYPES = [
        'domain' => 'Domain', 'hosting' => 'Hosting', 'email' => 'Email', 'ssl' => 'SSL certificate',
        'maintenance' => 'Maintenance plan', 'marketing' => 'Marketing retainer', 'app' => 'App / plugin licence', 'other' => 'Other',
    ];

    public const CYCLES = ['monthly' => 'Monthly', 'quarterly' => 'Every 3 months', 'half-yearly' => 'Every 6 months', 'yearly' => 'Yearly', '2-years' => 'Every 2 years', 'one-time' => 'One time'];

    protected $guarded = ['id'];

    protected $casts = [
        'due_on' => 'date',
        'is_active' => 'boolean',
        'reminded_30_on' => 'date',
        'reminded_7_on' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(ClientSite::class, 'client_site_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeDueWithin(Builder $q, int $days): Builder
    {
        return $q->where('is_active', true)->whereDate('due_on', '<=', today()->addDays($days))->orderBy('due_on');
    }

    public function label(): string
    {
        return (self::TYPES[$this->type] ?? $this->type).($this->site ? ' – '.$this->site->domain : '').($this->description ? ' ('.$this->description.')' : '');
    }

    /** Moves the due date forward one cycle (after the client pays) and resets the reminders. */
    public function advance(): void
    {
        if ($this->cycle === 'one-time') {
            $this->update(['is_active' => false]);

            return;
        }
        $months = ['monthly' => 1, 'quarterly' => 3, 'half-yearly' => 6, 'yearly' => 12, '2-years' => 24][$this->cycle] ?? 12;
        $this->update(['due_on' => $this->due_on->copy()->addMonthsNoOverflow($months), 'reminded_30_on' => null, 'reminded_7_on' => null]);
    }
}
