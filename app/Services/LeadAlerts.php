<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/** Everything that happens after a lead is saved: email + Telegram alerts and the Meta Conversions API. */
class LeadAlerts
{
    public static function dispatch(Lead $lead): void
    {
        foreach (['email', 'telegram', 'autoReply', 'meta'] as $step) {
            try {
                static::{$step}($lead);
            } catch (\Throwable $e) {
                Log::warning("Lead #{$lead->id} {$step} failed: ".$e->getMessage());
            }
        }
    }

    public static function summary(Lead $lead): string
    {
        return "Name: {$lead->name}\nPhone: {$lead->phone}\nEmail: ".($lead->email ?: '—')."\n"
            .'Service: '.($lead->service ?: '—')."\nBudget: ".(Lead::BUDGETS[$lead->budget] ?? '—')."\n"
            .'Website: '.($lead->website ?: '—')."\n"
            .($lead->message ? "\n".$lead->message."\n" : '')
            ."\nSource: ".$lead->sourceLabel();
    }

    public static function email(Lead $lead): void
    {
        $to = array_filter(array_map('trim', explode(',', (string) site('lead_emails', site('email')))));
        if (! $to) {
            return;
        }
        $body = "New enquiry from the website\n\n".static::summary($lead)."\n\n"
            .'WhatsApp: https://wa.me/91'.$lead->phone."\n"
            .'Open in admin: '.url(admin_path('leads/'.$lead->id.'/edit'));

        Mail::raw($body, fn ($m) => $m->to($to)->subject('New lead: '.$lead->name.($lead->service ? ' – '.$lead->service : '')));
    }

    /** Free instant alert: create a bot with @BotFather, then put the bot token and your chat ID in Site settings. */
    public static function telegram(Lead $lead): void
    {
        Notify::telegram("🔔 New lead\n\n".static::summary($lead)."\n\nWhatsApp: https://wa.me/91{$lead->phone}\nAdmin: ".url(admin_path('leads/'.$lead->id.'/edit')));
    }

    /** Thank-you email to the customer, so they know the enquiry arrived and how to reach you faster. */
    public static function autoReply(Lead $lead): void
    {
        if (! $lead->email || $lead->auto_reply_sent_at) {
            return;
        }
        $first = trim(strtok($lead->name, ' '));
        $intro = trim((string) site('auto_reply')) ?: "Thanks for contacting ".site('name')."! We've received your enquiry".($lead->service ? " about {$lead->service}" : '')." and will get back to you within a few hours (Mon – Sat).";
        $body = "Hi {$first},\n\n{$intro}\n\n"
            ."Want a faster reply? WhatsApp us: ".wa_link('Hi, I just sent an enquiry on your website ('.$lead->name.').')."\n"
            .'Or call: +91 '.site('phone')."\n\n"
            ."See our recent work: ".route('work')."\n\n"
            ."Regards,\nTeam ".site('name')."\n".url('/');

        Mail::raw($body, function ($m) use ($lead) {
            $m->to($lead->email, $lead->name)->subject('We got your enquiry – '.site('name'));
            if ($reply = site('email')) {
                $m->replyTo($reply, site('name'));
            }
        });
        $lead->forceFill(['auto_reply_sent_at' => now()])->saveQuietly();
    }

    /**
     * Sends the lead to Meta from the server, so it still counts when the browser pixel is blocked.
     * The event_id matches the pixel event on the thank-you page, so Meta counts it once.
     */
    public static function meta(Lead $lead, string $event = 'Lead'): void
    {
        $pixel = Setting::get('tracking.meta_pixel');
        $token = Setting::get('tracking.meta_capi_token');
        if (! $pixel || ! $token) {
            return;
        }
        $hash = fn (?string $v) => $v ? hash('sha256', strtolower(trim($v))) : null;
        $fbc = $lead->fbclid ? 'fb.1.'.($lead->created_at->getTimestampMs()).'.'.$lead->fbclid : null;

        $payload = [
            'data' => [[
                'event_name' => $event,
                'event_time' => now()->timestamp,
                'event_id' => $event === 'Lead' ? 'lead-'.$lead->id : strtolower($event).'-'.$lead->id,
                'action_source' => 'website',
                'event_source_url' => $lead->page_url ?: url('/'),
                'user_data' => array_filter([
                    'ph' => [$hash('91'.$lead->phone)],
                    'em' => $lead->email ? [$hash($lead->email)] : null,
                    'fn' => [$hash(strtok($lead->name, ' '))],
                    'country' => [$hash('in')],
                    'client_ip_address' => $lead->ip,
                    'client_user_agent' => $lead->user_agent,
                    'fbc' => $fbc,
                    'fbp' => $lead->fbp,
                ]),
                'custom_data' => array_filter([
                    'content_name' => $lead->service,
                    'currency' => 'INR',
                    'value' => $event === 'Purchase' ? (int) $lead->deal_value : null,
                ]),
            ]],
        ];
        if ($test = Setting::get('tracking.meta_test_code')) {
            $payload['test_event_code'] = $test;
        }

        Http::timeout(5)->post("https://graph.facebook.com/v21.0/{$pixel}/events?access_token=".urlencode($token), $payload)->throw();
    }
}
