<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'proposal' => 'Proposal sent',
        'won' => 'Won',
        'lost' => 'Lost',
        'spam' => 'Spam',
    ];

    public const BUDGETS = [
        'under-25k' => 'Under ₹25,000',
        '25k-75k' => '₹25,000 – ₹75,000',
        '75k-2l' => '₹75,000 – ₹2,00,000',
        'above-2l' => 'Above ₹2,00,000',
        'not-sure' => 'Not sure yet',
    ];

    public const TIMELINES = [
        'asap' => 'As soon as possible',
        '1-month' => 'Within a month',
        '1-3-months' => '1 – 3 months',
        'exploring' => 'Just exploring',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'follow_up_on' => 'date',
        'converted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (Lead $lead) {
            if ($lead->isDirty('status') && $lead->status === 'won' && ! $lead->converted_at) {
                $lead->converted_at = now();
            }
        });
    }

    public function sourceLabel(): string
    {
        if ($this->gclid) {
            return 'Google Ads';
        }
        if ($this->utm_source) {
            return trim($this->utm_source.' / '.$this->utm_medium, ' /');
        }
        if ($this->referrer) {
            return parse_url($this->referrer, PHP_URL_HOST) ?: 'Referral';
        }

        return 'Direct';
    }
}
