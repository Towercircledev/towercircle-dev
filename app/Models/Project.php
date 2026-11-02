<?php

namespace App\Models;

use App\Models\Concerns\Visible;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Visible;

    protected $guarded = ['id'];

    protected $casts = [
        'gallery' => 'array',
        'highlights' => 'array',
        'completed_on' => 'date',
        'is_featured' => 'boolean',
        'is_visible' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
