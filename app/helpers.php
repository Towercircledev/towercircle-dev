<?php

use App\Models\Setting;
use App\Support\Defaults;
use Illuminate\Support\Facades\Storage;

if (! function_exists('site')) {
    /** Business/contact setting, falling back to built-in defaults. */
    function site(string $key, mixed $default = null): mixed
    {
        return Setting::get("site.$key", Defaults::site()[$key] ?? $default);
    }
}

if (! function_exists('home')) {
    function home(string $key, mixed $default = null): mixed
    {
        return Setting::get("home.$key", data_get(Defaults::home(), $key, $default));
    }
}

if (! function_exists('page_on')) {
    /** Whether an optional page (team, blog, faq) is switched on in the admin. */
    function page_on(string $page): bool
    {
        return (bool) Setting::get("pages.$page", true);
    }
}

if (! function_exists('wa_link')) {
    function wa_link(?string $text = null): string
    {
        $n = preg_replace('/\D/', '', (string) site('whatsapp', site('phone')));

        return 'https://wa.me/91'.substr($n, -10).($text ? '?text='.rawurlencode($text) : '');
    }
}

if (! function_exists('media')) {
    /** URL for an uploaded file, or a bundled image path. */
    function media(?string $path, ?string $fallback = null): ?string
    {
        if (! $path) {
            return $fallback ? asset($fallback) : null;
        }

        return str_starts_with($path, 'http') ? $path : Storage::disk('public')->url($path);
    }
}

if (! function_exists('inr')) {
    function inr(int|float|null $n): string
    {
        if ($n === null) {
            return '';
        }
        $s = (string) (int) $n;
        $last3 = substr($s, -3);
        $rest = substr($s, 0, -3);
        $rest = $rest !== '' ? preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest).',' : '';

        return '₹'.$rest.$last3;
    }
}
