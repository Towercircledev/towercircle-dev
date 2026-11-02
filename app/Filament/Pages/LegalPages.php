<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\LegalDefaults;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LegalPages extends Page implements HasForms
{
    use \App\Filament\Concerns\AdminOnly, InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Privacy & terms';

    protected string $view = 'filament.pages.settings-form';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'privacy' => Setting::get('legal.privacy', LegalDefaults::privacy()),
            'terms' => Setting::get('legal.terms', LegalDefaults::terms()),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Section::make('Privacy policy')->description('Shown at /privacy-policy. Google and Meta ads need this page. Have it checked by your CA or lawyer.')
                ->collapsible()->schema([RichEditor::make('privacy')->hiddenLabel()->required()]),
            Section::make('Terms & conditions')->description('Shown at /terms-and-conditions.')
                ->collapsible()->schema([RichEditor::make('terms')->hiddenLabel()->required()]),
        ]);
    }

    public function save(): void
    {
        Setting::put('legal', $this->form->getState() + ['updated' => now()->toDateString()]);
        Notification::make()->title('Saved')->success()->send();
    }
}
