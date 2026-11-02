<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\ChartWidget;

class LeadsBySourceChart extends ChartWidget
{
    protected ?string $heading = 'Leads per week by source';

    protected ?string $description = 'Last 12 weeks. Spam excluded.';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    public const COLORS = [
        'Google Ads' => '#4285f4',
        'Meta Ads' => '#7a1a6e',
        'Organic search' => '#1fa855',
        'Direct' => '#f5b301',
        'Other' => '#9ca3af',
    ];

    protected function getData(): array
    {
        $start = now()->startOfWeek()->subWeeks(11);
        $weeks = collect(range(0, 11))->map(fn ($i) => $start->copy()->addWeeks($i));
        $leads = Lead::where('status', '!=', 'spam')->where('created_at', '>=', $start)->get();
        $grouped = $leads->groupBy(fn ($l) => $l->sourceGroup());

        $datasets = [];
        foreach (self::COLORS as $source => $color) {
            $rows = $grouped->get($source, collect());
            $datasets[] = [
                'label' => $source,
                'data' => $weeks->map(fn ($w) => $rows->filter(fn ($l) => $l->created_at->between($w, $w->copy()->endOfWeek()))->count())->all(),
                'backgroundColor' => $color,
                'borderRadius' => 4,
                'stack' => 'leads',
            ];
        }

        return ['datasets' => $datasets, 'labels' => $weeks->map(fn ($w) => $w->format('d M'))->all()];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => ['x' => ['stacked' => true, 'grid' => ['display' => false]], 'y' => ['stacked' => true, 'beginAtZero' => true, 'ticks' => ['precision' => 0]]],
            'plugins' => ['legend' => ['position' => 'bottom']],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
