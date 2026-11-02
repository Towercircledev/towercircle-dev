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
        'show_projects' => 'boolean',
        'show_testimonials' => 'boolean',
        'is_visible' => 'boolean',
        'noindex' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
