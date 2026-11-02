<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/** Sends an internal alert to the team: Telegram (if set up) and email (to the lead-alert addresses). */
class Notify
{
    public static function team(string $subject, string $text, bool $email = true): void
    {
        try {
            static::telegram($subject."\n\n".$text);
        } catch (\Throwable $e) {
            Log::warning('Telegram alert failed: '.$e->getMessage());
        }
        if (! $email) {
            return;
        }
        $to = static::teamEmails();
        if ($to) {
            try {
                Mail::raw($text, fn ($m) => $m->to($to)->subject($subject));
            } catch (\Throwable $e) {
                Log::warning('Alert email failed: '.$e->getMessage());
            }
        }
    }

    public static function teamEmails(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) site('lead_emails', site('email'))))));
    }

    public static function telegram(string $text): void
    {
        $token = Setting::get('alerts.telegram_token');
        $chat = Setting::get('alerts.telegram_chat_id');
        if (! $token || ! $chat) {
            return;
        }
        Http::timeout(5)->post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chat,
            'text' => mb_substr($text, 0, 4000),
            'disable_web_page_preview' => true,
        ])->throw();
    }
}
