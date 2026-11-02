<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait Visible
{
    public function scopeVisible(Builder $q): Builder
    {
        return $q->where('is_visible', true)->orderBy('sort')->orderBy('id');
    }
}
