<?php

namespace App\Models;

use App\Models\Concerns\Visible;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    use Visible;

    public const GROUPS = ['client' => 'Client', 'platform' => 'Platform / tool'];

    protected $guarded = ['id'];

    protected $casts = ['is_visible' => 'boolean'];
}
