<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use App\Models\Setting;
use App\Support\Defaults;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HomepageContent extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Homepage & About';

    protected string $view = 'filament.pages.settings-form';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(array_replace_recursive(Defaults::home(), Setting::get('home', [])));
    }

    private static function toggle(string $name, string $label = 'Show this section'): Toggle
    {
        return Toggle::make("show.$name")->label($label)->default(true)->columnSpanFull();
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Section::make('Top banner')->columns(2)->schema([
                TextInput::make('hero.eyebrow')->label('Small line above heading')->maxLength(80),
                TextInput::make('hero.highlight')->label('Underlined words in heading')->maxLength(40)
                    ->helperText('Must appear in the heading exactly'),
                TextInput::make('hero.heading')->required()->maxLength(90)->columnSpanFull(),
                Textarea::make('hero.text')->rows(2)->maxLength(250)->columnSpanFull(),
                TextInput::make('hero.cta')->label('Main button')->maxLength(30),
                TextInput::make('hero.cta2')->label('Second button (WhatsApp)')->maxLength(30),
                Fields::image('hero.image', 'home', 'Banner picture (optional)')->columnSpanFull()
                    ->helperText('Leave empty to keep the built-in illustration'),
            ]),
            Section::make('4 highlight boxes')->collapsed()->schema([
                Repeater::make('highlights')->hiddenLabel()->schema([
                    TextInput::make('title')->required()->maxLength(40),
                    TextInput::make('text')->maxLength(120),
                ])->columns(2)->minItems(4)->maxItems(4)->reorderable(false)->addable(false)->deletable(false),
            ]),
            Section::make('Logo strips')->collapsed()->columns(2)
                ->description('Logos are added under Website → Logos. A strip only appears when it has logos.')->schema([
                self::toggle('logos'),
                TextInput::make('logos.client_heading')->label('Client logos heading')->maxLength(80),
                TextInput::make('logos.platform_heading')->label('Platform logos heading')->maxLength(80),
            ]),
            Section::make('About section')->collapsed()->columns(2)->schema([
                self::toggle('about'),
                TextInput::make('about.eyebrow')->maxLength(40),
                TextInput::make('about.heading')->maxLength(100),
                Textarea::make('about.text')->rows(3)->columnSpanFull(),
                Repeater::make('about.points')->label('Tick points')->simple(TextInput::make('p')->required()->maxLength(80))
                    ->maxItems(5)->columnSpanFull(),
            ]),
            Section::make('Custom software section')->collapsed()->columns(2)->schema([
                self::toggle('software'),
                TextInput::make('software.eyebrow')->maxLength(40),
                TextInput::make('software.heading')->maxLength(100),
                Textarea::make('software.text')->rows(2)->columnSpanFull(),
                Repeater::make('software.items')->label('Software types')->schema([
                    TextInput::make('title')->required()->maxLength(40),
                    TextInput::make('text')->maxLength(140),
                ])->columns(2)->maxItems(9)->columnSpanFull(),
            ]),
            Section::make('Numbers bar')->collapsed()->schema([
                self::toggle('stats'),
                Repeater::make('stats')->hiddenLabel()->schema([
                    TextInput::make('number')->numeric()->required(),
                    TextInput::make('suffix')->maxLength(3)->default('+'),
                    TextInput::make('label')->required()->maxLength(40),
                ])->columns(3)->maxItems(4),
            ]),
            Section::make('How we work')->collapsed()->columns(2)->schema([
                self::toggle('process'),
                TextInput::make('process.heading')->maxLength(100)->columnSpanFull(),
                Textarea::make('process.text')->rows(2)->columnSpanFull(),
                Repeater::make('process.steps')->schema([
                    TextInput::make('title')->required()->maxLength(40),
                    TextInput::make('text')->maxLength(140),
                ])->columns(2)->maxItems(4)->columnSpanFull(),
                Fields::image('process.image', 'home', 'Picture on the right (optional)')->columnSpanFull(),
            ]),
            Section::make('Other homepage sections')->collapsed()->columns(2)->schema([
                Toggle::make('show.services')->label('Services')->default(true),
                Toggle::make('show.projects')->label('Recent projects')->default(true),
                Toggle::make('show.team')->label('Team')->default(true),
                Toggle::make('show.reviews')->label('Client reviews')->default(true),
                Toggle::make('show.blog')->label('Latest blog posts')->default(true),
                Toggle::make('show.audit')->label('Free website audit form')->default(true),
            ]),
            Section::make('Free audit box')->collapsed()->columns(2)->schema([
                TextInput::make('audit.heading')->maxLength(80)->columnSpanFull(),
                Textarea::make('audit.text')->rows(2)->columnSpanFull(),
            ]),
            Section::make('Homepage SEO')->collapsed()->schema([
                TextInput::make('seo.title')->maxLength(70),
                Textarea::make('seo.description')->rows(2)->maxLength(300),
            ]),
        ]);
    }

    public function save(): void
    {
        Setting::put('home', $this->form->getState());
        Notification::make()->title('Homepage saved')->success()->send();
    }
}
