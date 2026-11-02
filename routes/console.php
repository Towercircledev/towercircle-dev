<?php

use App\Models\ClientSite;
use App\Models\Payment;
use App\Models\Renewal;
use App\Models\Task;
use App\Services\Notify;
use App\Services\SiteMonitor;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
 | These run automatically once the server cron is set up (see README):
 |   * * * * * cd /var/www/towercircle && php artisan schedule:run >> /dev/null 2>&1
 */

Artisan::command('tc:check-sites', function () {
    $sites = ClientSite::with('client')->where('monitor', true)->get();
    foreach ($sites as $site) {
        SiteMonitor::check($site);
    }
    $this->info("Checked {$sites->count()} sites.");
})->purpose('Check every monitored client website is online');

Artisan::command('tc:check-ssl', function () {
    $soon = collect();
    foreach (ClientSite::with('client')->where('monitor', true)->get() as $site) {
        SiteMonitor::checkSsl($site);
        if ($site->ssl_expires_on && $site->ssl_expires_on->lte(today()->addDays(10))) {
            $soon->push($site->domain.' ('.$site->client->name.') – SSL expires '.$site->ssl_expires_on->format('d M Y'));
        }
    }
    if ($soon->isNotEmpty()) {
        Notify::team('⚠️ SSL certificates expiring soon', $soon->implode("\n")."\n\nUsually this means auto-renewal failed on the hosting.");
    }
})->purpose('Read SSL expiry dates and warn about ones expiring within 10 days');

Artisan::command('tc:daily-digest', function () {
    $lines = [];

    foreach ([30 => 'reminded_30_on', 7 => 'reminded_7_on'] as $days => $col) {
        $due = Renewal::with(['client', 'site'])->where('is_active', true)->whereNull($col)
            ->whereDate('due_on', '<=', today()->addDays($days))->orderBy('due_on')->get();
        foreach ($due as $r) {
            $lines[] = ($r->due_on->isPast() ? '❗ OVERDUE ' : "⏰ {$days}-day: ").$r->client->name.' – '.$r->label()
                .' – due '.$r->due_on->format('d M Y').($r->amount ? ' – '.inr($r->amount).' + GST' : '');
            $r->forceFill([$col => today(), ...($days === 30 ? [] : ['reminded_30_on' => $r->reminded_30_on ?? today()])])->save();
        }
    }

    $overdue = Payment::with('client')->where('status', 'pending')->whereDate('due_on', '<', today())->get();
    if ($overdue->isNotEmpty()) {
        $lines[] = '';
        $lines[] = '💰 Overdue payments: '.inr($overdue->sum('total'));
        foreach ($overdue as $p) {
            $lines[] = '  • '.$p->client->name.' – '.$p->number.' – '.inr($p->total).' (due '.$p->due_on->format('d M').')';
        }
    }

    $tasks = Task::with('client')->where('status', '!=', 'done')->whereDate('due_on', '<=', today())->orderBy('due_on')->get();
    if ($tasks->isNotEmpty()) {
        $lines[] = '';
        $lines[] = '✅ Tasks due today or late: '.$tasks->count();
        foreach ($tasks->take(15) as $t) {
            $lines[] = '  • '.$t->title.($t->client ? ' ('.$t->client->name.')' : '').($t->due_on->lt(today()) ? ' – late' : '');
        }
    }

    $down = ClientSite::with('client')->where('monitor', true)->where('uptime_status', 'down')->get();
    if ($down->isNotEmpty()) {
        $lines[] = '';
        $lines[] = '🔴 Sites down: '.$down->map(fn ($s) => $s->domain)->implode(', ');
    }

    if ($lines) {
        Notify::team('Towercircle daily summary – '.today()->format('d M'), implode("\n", $lines)."\n\nOpen admin: ".url(admin_path()));
    }
    $this->info(count($lines).' lines sent.');
})->purpose('Morning summary: renewals due in 30/7 days, overdue payments, tasks and down sites');

Schedule::command('tc:check-sites')->everyFiveMinutes()->withoutOverlapping();
Schedule::command('tc:check-ssl')->dailyAt('06:30');
Schedule::command('tc:daily-digest')->dailyAt('09:00');
