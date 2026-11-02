<?php

namespace App\Models;

use App\Models\Concerns\Visible;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Visible;

    protected $guarded = ['id'];

    protected $casts = [
        'features' => 'array',
        'sections' => 'array',
        'faqs' => 'array',
        'is_featured' => 'boolean',
        'is_visible' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget('nav.services'));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('nav.services'));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function url(): string
    {
        return route('services.show', $this);
    }
}
