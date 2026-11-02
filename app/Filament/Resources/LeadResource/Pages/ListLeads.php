<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use App\Models\Lead;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ads_export')
                ->label('Google Ads conversions')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->modalDescription('Downloads a CSV of Google Ads leads you marked as Qualified or Won. Upload it in Google Ads → Goals → Conversions → Uploads, so Google learns which clicks became real clients.')
                ->schema([
                    Select::make('status')->options(['won' => 'Won', 'qualified' => 'Qualified (and won)'])->default('won')->required(),
                    DatePicker::make('from')->default(now()->subDays(30))->required(),
                ])
                ->action(function (array $data) {
                    $statuses = $data['status'] === 'won' ? ['won'] : ['qualified', 'proposal', 'won'];
                    $name = Setting::get('tracking.ads_conversion_name', $data['status'] === 'won' ? 'Client won' : 'Qualified lead');
                    $leads = Lead::whereNotNull('gclid')->whereIn('status', $statuses)
                        ->where('updated_at', '>=', $data['from'])->get();

                    return response()->streamDownload(function () use ($leads, $name) {
                        $out = fopen('php://output', 'w');
                        fputcsv($out, ['Parameters:TimeZone=Asia/Kolkata']);
                        fputcsv($out, ['Google Click ID', 'Conversion Name', 'Conversion Time', 'Conversion Value', 'Conversion Currency']);
                        foreach ($leads as $l) {
                            fputcsv($out, [$l->gclid, $name, ($l->converted_at ?? $l->updated_at)->format('Y-m-d H:i:s'), $l->deal_value ?: '', 'INR']);
                        }
                        fclose($out);
                    }, 'google-ads-conversions-'.now()->format('Y-m-d').'.csv');
                }),
            CreateAction::make()->label('Add lead'),
        ];
    }

    public function getTabs(): array
    {
        $tab = fn (array $s) => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', $s))
            ->badge(Lead::whereIn('status', $s)->count() ?: null);

        return [
            'open' => $tab(['new', 'contacted', 'qualified', 'proposal'])->label('Open'),
            'new' => $tab(['new'])->label('New'),
            'won' => $tab(['won'])->label('Won'),
            'closed' => $tab(['lost', 'spam'])->label('Lost / spam'),
            'all' => Tab::make('All'),
        ];
    }
}
