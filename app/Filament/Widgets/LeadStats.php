<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeadStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $month = Lead::where('status', '!=', 'spam')->where('created_at', '>=', now()->startOfMonth());
        $total = (clone $month)->count();
        $won = (clone $month)->where('status', 'won')->count();
        $ads = (clone $month)->whereNotNull('gclid')->count();
        $trend = collect(range(13, 0))->map(fn ($d) => Lead::where('status', '!=', 'spam')->whereDate('created_at', today()->subDays($d))->count())->all();

        return [
            Stat::make('New leads waiting', Lead::where('status', 'new')->count())
                ->description('Reply within an hour to win more')->color('warning'),
            Stat::make('Leads this month', $total)->chart($trend)->color('primary')
                ->description($ads.' from Google Ads'),
            Stat::make('Won this month', $won)->color('success')
                ->description($total ? round($won / $total * 100).'% of leads' : '—'),
            Stat::make('Follow-ups due', Lead::whereDate('follow_up_on', '<=', today())->whereNotIn('status', ['won', 'lost', 'spam'])->count())
                ->color('danger'),
        ];
    }
}
