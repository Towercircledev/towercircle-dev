<?php

namespace App\Filament\RelationManagers;

use App\Models\Activity;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/** Dated timeline of calls, WhatsApps, meetings, proposals and notes — used on leads and clients. */
class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Timeline';

    protected static string|\BackedEnum|null $icon = 'heroicon-o-clock';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Select::make('type')->options(collect(Activity::TYPES)->except('system'))->default('call')->required(),
            DateTimePicker::make('happened_at')->label('When')->default(now())->required()->native(false)->seconds(false),
            Textarea::make('body')->label('What happened')->required()->rows(3)->columnSpanFull()
                ->placeholder('e.g. Called – wants Shopify store, budget ₹40k, send proposal by Friday'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('happened_at', 'desc')
            ->paginated([10, 25, 50])
            ->columns([
                IconColumn::make('type')->label('')->icon(fn ($state) => Activity::ICONS[$state] ?? 'heroicon-o-pencil-square')
                    ->color(fn ($state) => match ($state) { 'payment' => 'success', 'proposal' => 'primary', 'system' => 'gray', default => 'info' }),
                TextColumn::make('body')->label('Entry')->wrap()
                    ->description(fn ($record) => (Activity::TYPES[$record->type] ?? $record->type).' · '.$record->happened_at->format('d M Y, h:i A').($record->user ? ' · '.$record->user->name : '')),
            ])
            ->headerActions([
                CreateAction::make()->label('Add entry')->icon('heroicon-o-plus')
                    ->mutateDataUsing(fn (array $data) => $data + ['user_id' => auth()->id()]),
            ])
            ->recordActions([
                EditAction::make()->iconButton()->hidden(fn ($record) => $record->type === 'system'),
                DeleteAction::make()->iconButton(),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
