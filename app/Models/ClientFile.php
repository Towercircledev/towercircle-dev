<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/** Client documents are stored on the private "local" disk, never on the public website. */
class ClientFile extends Model
{
    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::deleted(fn (ClientFile $f) => Storage::disk('local')->delete($f->path));
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
