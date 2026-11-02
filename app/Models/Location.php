<?php

namespace App\Models;

use App\Models\Concerns\Visible;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use Visible;

    protected $guarded = ['id'];

    protected $casts = ['faqs' => 'array', 'is_visible' => 'boolean'];

    protected static function booted(): void
    {
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget('nav.locations'));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('nav.locations'));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
