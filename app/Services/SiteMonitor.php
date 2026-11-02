<?php

namespace App\Services;

use App\Models\ClientSite;
use Illuminate\Support\Facades\Http;

/**
 * Checks client websites. A site is only marked DOWN after 2 failed checks in a row
 * (so one slow response doesn't wake you up), and you get one alert when it goes down
 * and one when it is back.
 */
class SiteMonitor
{
    public static function check(ClientSite $site, bool $alert = true): void
    {
        $url = $site->live_url ?: 'https://'.$site->domain;
        $start = microtime(true);
        $code = null;
        $ok = false;
        try {
            $res = Http::timeout(15)->connectTimeout(10)->withUserAgent('TowercircleMonitor/1.0 (+'.url('/').')')
                ->withOptions(['allow_redirects' => ['max' => 5]])->get($url);
            $code = $res->status();
            $ok = $code < 500; // 4xx still means the server is up (e.g. a password-protected site)
        } catch (\Throwable) {
            $ok = false;
        }
        $ms = (int) round((microtime(true) - $start) * 1000);
        $wasDown = $site->uptime_status === 'down';
        $downSince = $site->down_since;

        if ($ok) {
            $site->forceFill([
                'uptime_status' => 'up', 'fail_count' => 0, 'last_status_code' => $code,
                'last_response_ms' => $ms, 'last_checked_at' => now(), 'down_since' => null,
            ])->save();
            if ($wasDown && $alert) {
                Notify::team('✅ Back online: '.$site->domain, $site->domain.' ('.$site->client->name.') is reachable again. It was down for '.($downSince ? $downSince->diffForHumans(now(), true) : 'a while').'.');
            }

            return;
        }

        $fails = $site->fail_count + 1;
        $site->forceFill([
            'fail_count' => $fails, 'last_status_code' => $code, 'last_response_ms' => $ms, 'last_checked_at' => now(),
        ]);
        if ($fails >= 2 && ! $wasDown) {
            $site->uptime_status = 'down';
            $site->down_since = now();
            if ($alert) {
                Notify::team('🔴 Website DOWN: '.$site->domain,
                    $site->domain.' ('.$site->client->name.') is not responding'.($code ? " (error $code)" : ' (no response)').".\n"
                    .'Checked: '.$url."\nClient phone: ".($site->client->phone ?: '—'));
            }
        } elseif (! $alert && $fails >= 1) {
            $site->uptime_status = 'down';
            $site->down_since ??= now();
        }
        $site->save();
    }

    /** Reads the SSL certificate's expiry date directly from the server. */
    public static function checkSsl(ClientSite $site): void
    {
        $host = $site->domain;
        $ctx = stream_context_create(['ssl' => ['capture_peer_cert' => true, 'verify_peer' => false, 'verify_peer_name' => false, 'SNI_enabled' => true, 'peer_name' => $host]]);
        $client = @stream_socket_client("ssl://{$host}:443", $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $ctx);
        if (! $client) {
            return;
        }
        $cert = stream_context_get_params($client)['options']['ssl']['peer_certificate'] ?? null;
        fclose($client);
        if (! $cert) {
            return;
        }
        $info = openssl_x509_parse($cert);
        if (! empty($info['validTo_time_t'])) {
            $site->forceFill(['ssl_expires_on' => date('Y-m-d', $info['validTo_time_t'])])->save();
        }
    }
}
