<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    public const STATUSES = ['todo' => 'To do', 'doing' => 'In progress', 'waiting' => 'Waiting on client', 'done' => 'Done'];

    public const PRIORITIES = ['low' => 'Low', 'normal' => 'Normal', 'high' => 'High'];

    protected $guarded = ['id'];

    protected $casts = ['due_on' => 'date', 'completed_at' => 'datetime'];

    protected static function booted(): void
    {
        static::saving(function (Task $t) {
            if ($t->isDirty('status')) {
                $t->completed_at = $t->status === 'done' ? now() : null;
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
