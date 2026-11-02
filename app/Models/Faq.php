<?php

namespace App\Models;

use App\Models\Concerns\Visible;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use Visible;

    protected $guarded = ['id'];

    protected $casts = ['is_visible' => 'boolean'];
}
