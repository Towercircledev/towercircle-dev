<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Remembers where a visitor came from (Google Ads click ID, UTM tags, first page, referrer)
 * so it can be saved with their enquiry later. A new ad click replaces the earlier one.
 */
class CaptureAttribution
{
    public const KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid'];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && ! $request->is(trim(config('app.admin_path'), '/').'*', 'livewire*')) {
            $incoming = array_filter(array_map(
                fn ($v) => is_string($v) ? mb_substr($v, 0, 190) : null,
                $request->only(self::KEYS)
            ));
            if ($request->filled('gbraid') && ! isset($incoming['gclid'])) {
                $incoming['gclid'] = mb_substr((string) $request->query('gbraid'), 0, 190);
            }
            $attr = $request->session()->get('attr');

            if (! $attr || $incoming) {
                $ref = (string) $request->headers->get('referer');
                $external = $ref && parse_url($ref, PHP_URL_HOST) !== $request->getHost();
                $request->session()->put('attr', $incoming + [
                    'landing_url' => mb_substr($request->fullUrl(), 0, 250),
                    'referrer' => $external ? mb_substr($ref, 0, 250) : ($attr['referrer'] ?? null),
                ]);
            }
        }

        return $next($request);
    }
}
