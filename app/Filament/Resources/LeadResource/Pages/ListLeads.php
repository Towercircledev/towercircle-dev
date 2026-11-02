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
            Action::make('csv')
                ->label('Export')
                ->icon('heroicon-o-table-cells')
                ->color('gray')
                ->modalDescription('Downloads a CSV file that opens in Excel or Google Sheets.')
                ->schema([
                    DatePicker::make('from')->default(now()->subDays(90))->required()->native(false),
                    DatePicker::make('to')->default(now())->required()->native(false),
                    Select::make('status')->options(Lead::STATUSES)->multiple()->placeholder('All statuses'),
                    Select::make('source')->options(['Google Ads' => 'Google Ads', 'Meta Ads' => 'Meta Ads', 'Organic search' => 'Organic search', 'Direct' => 'Direct', 'Other' => 'Other'])->multiple()->placeholder('All sources'),
                ])
                ->action(function (array $data) {
                    $leads = Lead::whereBetween('created_at', [\Illuminate\Support\Carbon::parse($data['from'])->startOfDay(), \Illuminate\Support\Carbon::parse($data['to'])->endOfDay()])
                        ->when($data['status'] ?? null, fn ($q, $s) => $q->whereIn('status', $s))
                        ->orderBy('created_at')->get()
                        ->when($data['source'] ?? null, fn ($c, $src) => $c->filter(fn ($l) => in_array($l->sourceGroup(), $src, true)));

                    return response()->streamDownload(function () use ($leads) {
                        $out = fopen('php://output', 'w');
                        fwrite($out, "\xEF\xBB\xBF"); // so Excel shows ₹ and other symbols correctly
                        fputcsv($out, ['Received', 'Name', 'Phone', 'Email', 'Company', 'Website', 'Service', 'Budget', 'Timeline', 'Message', 'Status', 'Deal value', 'Source', 'Campaign', 'Keyword', 'Follow up', 'Notes']);
                        foreach ($leads as $l) {
                            fputcsv($out, [
                                $l->created_at->format('Y-m-d H:i'), $l->name, $l->phone, $l->email, $l->company, $l->website, $l->service,
                                Lead::BUDGETS[$l->budget] ?? $l->budget, Lead::TIMELINES[$l->timeline] ?? $l->timeline, $l->message,
                                Lead::STATUSES[$l->status] ?? $l->status, $l->deal_value, $l->sourceLabel(), $l->utm_campaign, $l->utm_term,
                                $l->follow_up_on?->format('Y-m-d'), $l->notes,
                            ]);
                        }
                        fclose($out);
                    }, 'leads-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
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
