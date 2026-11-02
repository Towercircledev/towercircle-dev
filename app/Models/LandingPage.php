<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingPage extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'benefits' => 'array',
        'steps' => 'array',
        'faqs' => 'array',
        'content' => 'array',
        'show_projects' => 'boolean',
        'show_testimonials' => 'boolean',
        'is_visible' => 'boolean',
        'noindex' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public const TEMPLATES = ['standard' => 'Standard (short ad page)', 'ecommerce' => 'E-commerce store (long page)'];

    /** Content for long templates, merged over the built-in defaults so new fields always have a value. */
    public function c(string $key, mixed $default = null): mixed
    {
        $defaults = $this->template === 'ecommerce' ? \App\Support\EcomLanding::defaults() : [];
        $value = data_get($this->content ?? [], $key);

        return ($value === null || $value === '' || $value === []) ? data_get($defaults, $key, $default) : $value;
    }

    protected static function booted(): void
    {
        static::saving(function (LandingPage $lp) {
            if (blank($lp->heading)) {
                $lp->heading = data_get($lp->content, 'hero.heading') ?: $lp->title;
            }
        });
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
