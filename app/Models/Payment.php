<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    public const PURPOSES = ['project' => 'Website / project', 'renewal' => 'Renewal', 'maintenance' => 'Maintenance', 'marketing' => 'Marketing / ads', 'other' => 'Other'];

    public const METHODS = ['upi' => 'UPI', 'bank' => 'Bank transfer', 'cash' => 'Cash', 'card' => 'Card', 'cheque' => 'Cheque', 'razorpay' => 'Razorpay', 'other' => 'Other'];

    public const STATUSES = ['pending' => 'Pending', 'paid' => 'Paid', 'cancelled' => 'Cancelled'];

    protected $guarded = ['id'];

    protected $casts = [
        'invoice_date' => 'date',
        'due_on' => 'date',
        'paid_on' => 'date',
        'gst_rate' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (Payment $p) {
            $p->gst_amount = (int) round($p->amount * ((float) $p->gst_rate) / 100);
            $p->total = $p->amount + $p->gst_amount;
            if ($p->status === 'paid' && ! $p->paid_on) {
                $p->paid_on = today();
            }
        });
        static::creating(function (Payment $p) {
            $p->number ??= static::nextNumber($p->invoice_date ?? today());
        });
    }

    /** Invoice numbers like TC/2026-27/0001, restarting every financial year (April–March). */
    public static function nextNumber($date): string
    {
        $prefix = strtoupper(Setting::get('invoice.prefix', 'TC'));
        $y = $date->month >= 4 ? $date->year : $date->year - 1;
        $fy = $y.'-'.substr((string) ($y + 1), -2);
        $base = "$prefix/$fy/";

        return DB::transaction(function () use ($base) {
            $last = static::where('number', 'like', $base.'%')->lockForUpdate()->orderByDesc('number')->value('number');
            $n = $last ? ((int) substr($last, strlen($base))) + 1 : 1;

            return $base.str_pad((string) $n, 4, '0', STR_PAD_LEFT);
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function renewal(): BelongsTo
    {
        return $this->belongsTo(Renewal::class);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'pending' && $this->due_on && $this->due_on->isPast();
    }
}
