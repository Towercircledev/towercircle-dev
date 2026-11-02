<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ClientSiteResource;
use App\Filament\Resources\PaymentResource;
use App\Filament\Resources\RenewalResource;
use App\Filament\Resources\TaskResource;
use App\Models\ClientSite;
use App\Models\Payment;
use App\Models\Renewal;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BusinessStats extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $stats = [];
        if (auth()->user()?->isAdmin()) {
            $owed = (int) Payment::where('status', 'pending')->sum('total');
            $overdue = (int) Payment::where('status', 'pending')->whereDate('due_on', '<', today())->sum('total');
            $collected = (int) Payment::where('status', 'paid')->whereBetween('paid_on', [now()->startOfMonth(), now()])->sum('total');
            $stats[] = Stat::make('Money owed to you', inr($owed))->description($overdue ? inr($overdue).' overdue' : 'Nothing overdue')
                ->color($overdue ? 'danger' : 'success')->url(PaymentResource::getUrl());
            $stats[] = Stat::make('Collected this month', inr($collected))->color('success');
        }
        $renewals = Renewal::dueWithin(30)->count();
        $stats[] = Stat::make('Renewals in next 30 days', $renewals)
            ->description(Renewal::dueWithin(0)->count().' overdue')->color($renewals ? 'warning' : 'gray')->url(RenewalResource::getUrl());
        $down = ClientSite::where('monitor', true)->where('uptime_status', 'down')->count();
        $stats[] = Stat::make('Websites down', $down)->description(ClientSite::where('monitor', true)->count().' monitored')
            ->color($down ? 'danger' : 'success')->url(ClientSiteResource::getUrl());
        $tasks = Task::where('status', '!=', 'done')->whereDate('due_on', '<=', today())->count();
        $stats[] = Stat::make('Tasks due today', $tasks)->color($tasks ? 'warning' : 'gray')->url(TaskResource::getUrl());

        return $stats;
    }
}
