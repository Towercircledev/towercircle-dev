<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** A saved login. Username and password are encrypted in the database with the app key. */
class Credential extends Model
{
    public const TYPES = [
        'website_admin' => 'Website admin (WordPress / Shopify / Laravel)',
        'hosting' => 'Hosting / cPanel',
        'registrar' => 'Domain registrar',
        'email' => 'Email account',
        'ftp' => 'FTP / SFTP / SSH',
        'database' => 'Database',
        'google' => 'Google (Analytics / Ads / Search Console)',
        'meta' => 'Meta / Facebook',
        'payment' => 'Payment gateway',
        'other' => 'Other',
    ];

    protected $guarded = ['id'];

    protected $hidden = ['password'];

    protected $casts = [
        'username' => 'encrypted',
        'password' => 'encrypted',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(ClientSite::class, 'client_site_id');
    }

    public function views(): HasMany
    {
        return $this->hasMany(CredentialView::class)->latest('created_at');
    }

    public function logView(string $action): void
    {
        $this->views()->create(['user_id' => auth()->id(), 'action' => $action, 'ip' => request()->ip()]);
    }
}
