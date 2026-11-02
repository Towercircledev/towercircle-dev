<?php

namespace App\Filament\Widgets;

use App\Models\Renewal;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingRenewals extends TableWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Renewals due in the next 30 days';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Renewal::query()->with(['client', 'site'])->dueWithin(30))
            ->paginated([5, 10])
            ->emptyStateHeading('Nothing due in the next 30 days')
            ->columns([
                TextColumn::make('due_on')->label('Due')->date('d M Y')
                    ->color(fn ($record) => $record->due_on->isPast() ? 'danger' : 'warning'),
                TextColumn::make('client.name')->label('Client')->weight('bold')
                    ->url(fn ($record) => \App\Filament\Resources\ClientResource::getUrl('edit', ['record' => $record->client_id])),
                TextColumn::make('type')->label('What')->formatStateUsing(fn ($state, $record) => $record->label()),
                TextColumn::make('amount')->formatStateUsing(fn ($state) => $state ? inr($state) : '—')
                    ->visible(fn () => auth()->user()?->isAdmin()),
            ]);
    }
}
