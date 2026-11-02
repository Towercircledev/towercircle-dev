<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-check-circle';

    protected static string|\UnitEnum|null $navigationGroup = 'Clients';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema, bool $withOwner = true): Schema
    {
        return $schema->columns(2)->components(array_filter([
            TextInput::make('title')->required()->maxLength(150)->columnSpanFull(),
            $withOwner ? Select::make('client_id')->label('Client')->relationship('client', 'name')->searchable()->preload() : null,
            Select::make('assigned_to')->label('Assigned to')->options(fn () => User::pluck('name', 'id'))->default(fn () => auth()->id()),
            DatePicker::make('due_on')->label('Due date')->native(false),
            Select::make('priority')->options(Task::PRIORITIES)->default('normal')->required(),
            Select::make('status')->options(Task::STATUSES)->default('todo')->required(),
            Textarea::make('details')->rows(3)->columnSpanFull(),
        ]));
    }

    public static function table(Table $table, bool $withOwner = true): Table
    {
        return $table
            ->defaultSort(fn (Builder $query) => $query->orderByRaw("status = 'done'")->orderByRaw('due_on is null')->orderBy('due_on'))
            ->columns(array_filter([
                TextColumn::make('title')->searchable()->weight('bold')->wrap()
                    ->description(fn ($record) => $record->details ? \Illuminate\Support\Str::limit($record->details, 70) : null),
                $withOwner ? TextColumn::make('client.name')->label('Client')->placeholder('—')->searchable() : null,
                TextColumn::make('due_on')->label('Due')->date('d M')->sortable()->placeholder('—')
                    ->color(fn ($record) => $record->status !== 'done' && $record->due_on?->isPast() ? 'danger' : ($record->due_on?->isToday() ? 'warning' : null)),
                TextColumn::make('priority')->badge()->color(fn ($state) => ['high' => 'danger', 'normal' => 'gray', 'low' => 'gray'][$state] ?? 'gray'),
                TextColumn::make('assignee.name')->label('Who')->placeholder('—')->toggleable(),
                SelectColumn::make('status')->options(Task::STATUSES)->selectablePlaceholder(false),
            ]))
            ->filters([
                Filter::make('mine')->label('My tasks')->query(fn (Builder $query) => $query->where('assigned_to', auth()->id())),
                Filter::make('open')->label('Not done')->default()->query(fn (Builder $query) => $query->where('status', '!=', 'done')),
                SelectFilter::make('priority')->options(Task::PRIORITIES),
            ])
            ->recordActions([
                Action::make('done')->label('Done')->icon('heroicon-o-check')->color('success')->iconButton()
                    ->visible(fn ($record) => $record->status !== 'done')
                    ->action(fn ($record) => $record->update(['status' => 'done'])),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        $n = Task::where('status', '!=', 'done')->whereDate('due_on', '<=', today())->count();

        return $n ? (string) $n : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageTasks::route('/')];
    }
}
