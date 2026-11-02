<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientSite extends Model
{
    public const PLATFORMS = [
        'wordpress' => 'WordPress', 'shopify' => 'Shopify', 'woocommerce' => 'WooCommerce', 'laravel' => 'Laravel',
        'static' => 'Static / HTML', 'wix' => 'Wix', 'other' => 'Other',
    ];

    public const EMAIL_PROVIDERS = [
        'google' => 'Google Workspace', 'zoho' => 'Zoho Mail', 'microsoft' => 'Microsoft 365',
        'hostinger' => 'Hostinger Mail', 'cpanel' => 'cPanel / hosting mail', 'none' => 'No email', 'other' => 'Other',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'monitor' => 'boolean',
        'last_checked_at' => 'datetime',
        'down_since' => 'datetime',
        'ssl_expires_on' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (ClientSite $s) {
            $s->domain = strtolower(preg_replace('#^https?://(www\.)?|/.*$#', '', trim($s->domain)));
            if (! $s->live_url && $s->domain) {
                $s->live_url = 'https://'.$s->domain;
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function credentials(): HasMany
    {
        return $this->hasMany(Credential::class);
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(Renewal::class);
    }
}
