<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean', 'last_hit_at' => 'datetime'];

    /** Stores paths as "/old-page" (no domain, no trailing slash) so lookups are exact. */
    public static function normalise(string $path): string
    {
        $path = parse_url(trim($path), PHP_URL_PATH) ?: '/';

        return '/'.trim(strtolower(rawurldecode($path)), '/');
    }

    protected static function booted(): void
    {
        static::saving(fn (Redirect $r) => $r->from = static::normalise($r->from));
    }
}
