<?php

namespace App\Filament\Support;

use App\Support\EcomLanding;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

/** Admin editor for every section of the e-commerce landing page (stored in landing_pages.content). */
class EcomLandingForm
{
    private static function list(string $name, string $label, int $max = 12): Repeater
    {
        return Repeater::make($name)->label($label)->simple(TextInput::make('p')->required()->maxLength(160))
            ->defaultItems(0)->maxItems($max)->reorderable()->addActionLabel('Add');
    }

    private static function head(string $key, bool $withText = true, bool $withShow = true): array
    {
        return array_values(array_filter([
            $withShow ? Toggle::make("content.$key.show")->label('Show this section')->default(true)->columnSpanFull() : null,
            TextInput::make("content.$key.eyebrow")->label('Small label')->maxLength(60),
            TextInput::make("content.$key.heading")->label('Heading')->maxLength(140),
            $withText ? Textarea::make("content.$key.text")->label('Intro text')->rows(2)->columnSpanFull() : null,
        ]));
    }

    private static function icon(): Select
    {
        return Select::make('icon')->options(EcomLanding::ICONS)->default('check')->required();
    }

    public static function tabs(): Tabs
    {
        return Tabs::make('Page content')->columnSpanFull()->persistTabInQueryString()->tabs([
            Tab::make('Hero')->columns(2)->schema([
                TextInput::make('content.hero.badge')->label('Badge')->maxLength(80),
                TextInput::make('content.hero.highlight')->label('Underlined words in heading')->maxLength(60)->helperText('Must appear in the heading exactly'),
                TextInput::make('content.hero.heading')->label('Heading')->required()->maxLength(140)->columnSpanFull(),
                Textarea::make('content.hero.text')->label('Text under heading')->rows(3)->columnSpanFull(),
                self::list('content.hero.platforms', 'Platform tags', 5),
                self::list('content.hero.points', 'Tick points', 6),
                self::list('content.hero.trust', 'Trust line (small ticks)', 4)->columnSpanFull(),
                TextInput::make('content.form.heading')->label('Form heading')->maxLength(80),
                TextInput::make('content.form.button')->label('Form button')->maxLength(40),
                Textarea::make('content.form.text')->label('Form text')->rows(2)->columnSpanFull(),
            ]),
            Tab::make('Pain points')->columns(2)->schema([
                ...self::head('pain', false),
                Repeater::make('content.pain.items')->label('Boxes')->schema([
                    TextInput::make('emoji')->maxLength(8),
                    TextInput::make('title')->required()->maxLength(60),
                    Textarea::make('text')->rows(2)->columnSpanFull(),
                ])->columns(2)->maxItems(8)->collapsible()->itemLabel(fn (array $state) => $state['title'] ?? null)->columnSpanFull(),
                Textarea::make('content.pain.answer')->label('Answer line under the boxes')->rows(2)->columnSpanFull(),
            ]),
            Tab::make('Journey (steps)')->columns(2)->schema([
                ...self::head('journey'),
                Repeater::make('content.journey.steps')->label('Steps (picture alternates left / right)')->schema([
                    TextInput::make('eyebrow')->label('Small label')->maxLength(60),
                    TextInput::make('heading')->required()->maxLength(120),
                    RichEditor::make('body')->toolbarButtons(['bold', 'italic', 'link', 'bulletList'])->columnSpanFull(),
                    self::list('points', 'Tick points', 6)->columnSpanFull(),
                    FileUpload::make('image')->label('Picture (optional — replaces the illustration)')->image()->disk('public')->directory('landing')
                        ->saveUploadedFileUsing(fn ($file) => \App\Support\ImageOptimizer::store($file, 'landing'))->columnSpanFull(),
                    Hidden::make('default_image'),
                ])->columns(2)->maxItems(8)->collapsible()->collapsed()->itemLabel(fn (array $state) => $state['heading'] ?? null)->columnSpanFull(),
            ]),
            Tab::make('Platforms table')->columns(3)->schema([
                Toggle::make('content.compare.show')->label('Show this section')->default(true)->columnSpanFull(),
                TextInput::make('content.compare.eyebrow')->label('Small label'),
                TextInput::make('content.compare.heading')->label('Heading')->columnSpan(2),
                Textarea::make('content.compare.text')->label('Intro text')->rows(2)->columnSpanFull(),
                TextInput::make('content.compare.col1')->label('Column 1'),
                TextInput::make('content.compare.col2')->label('Column 2'),
                TextInput::make('content.compare.col3')->label('Column 3'),
                Repeater::make('content.compare.rows')->label('Rows')->schema([
                    TextInput::make('label')->required(),
                    TextInput::make('a')->label('Column 1'),
                    TextInput::make('b')->label('Column 2'),
                    TextInput::make('c')->label('Column 3'),
                ])->columns(4)->maxItems(12)->columnSpanFull(),
                TextInput::make('content.compare.button')->label('Button under table')->columnSpanFull(),
            ]),
            Tab::make('Packages')->columns(2)->schema([
                ...self::head('packages'),
                Repeater::make('content.packages.items')->label('Packages')->schema([
                    TextInput::make('name')->required()->maxLength(40),
                    TextInput::make('for')->label('Who it\'s for')->maxLength(120),
                    TextInput::make('price')->label('Price line')->placeholder('Starting at ₹14,999 or Get quote')->maxLength(40),
                    TextInput::make('price_note')->label('Small note under price')->maxLength(60),
                    TextInput::make('button')->label('Button text')->maxLength(30),
                    Toggle::make('popular')->label('Highlight as "Most popular"')->inline(false),
                    self::list('features', 'Features', 12)->columnSpanFull(),
                ])->columns(2)->maxItems(4)->collapsible()->itemLabel(fn (array $state) => $state['name'] ?? null)->columnSpanFull(),
                Textarea::make('content.packages.note')->label('Note under packages')->rows(2)->columnSpanFull(),
            ]),
            Tab::make('What\'s included')->columns(2)->schema([
                ...self::head('included', false),
                Repeater::make('content.included.items')->label('Cards')->schema([
                    self::icon(), TextInput::make('title')->required()->maxLength(40), TextInput::make('text')->maxLength(80)->columnSpanFull(),
                ])->columns(2)->maxItems(16)->collapsible()->collapsed()->itemLabel(fn (array $state) => $state['title'] ?? null)->columnSpanFull(),
            ]),
            Tab::make('Timeline')->columns(2)->schema([
                ...self::head('timeline'),
                Repeater::make('content.timeline.items')->label('Stages')->schema([
                    TextInput::make('day')->required()->maxLength(12)->placeholder('Day 1–2'),
                    TextInput::make('title')->required()->maxLength(40),
                    TextInput::make('text')->maxLength(120)->columnSpanFull(),
                ])->columns(2)->maxItems(6)->columnSpanFull(),
            ]),
            Tab::make('Proof & stats')->columns(2)->schema([
                TextInput::make('content.proof.eyebrow')->label('Projects small label'),
                TextInput::make('content.proof.heading')->label('Projects heading'),
                TextInput::make('content.proof.reviews_heading')->label('Reviews heading')->columnSpanFull()
                    ->helperText('Projects and reviews come from Website → Projects and Client reviews, and only show once you add some.'),
                Repeater::make('content.proof.stats')->label('Numbers bar')->schema([
                    TextInput::make('number')->required()->maxLength(10), TextInput::make('label')->required()->maxLength(50),
                ])->columns(2)->maxItems(4)->columnSpanFull(),
            ]),
            Tab::make('Guarantees')->columns(2)->schema([
                ...self::head('guarantees', false),
                Repeater::make('content.guarantees.items')->label('Promises')->schema([
                    self::icon(), TextInput::make('title')->required()->maxLength(40), TextInput::make('text')->maxLength(100)->columnSpanFull(),
                ])->columns(2)->maxItems(6)->columnSpanFull(),
            ]),
            Tab::make('FAQ')->columns(2)->schema([
                TextInput::make('content.faq.eyebrow')->label('Small label'),
                TextInput::make('content.faq.heading')->label('Heading'),
                Repeater::make('content.faq.items')->label('Questions')->schema([
                    TextInput::make('q')->label('Question')->required(),
                    Textarea::make('a')->label('Answer')->rows(3)->required(),
                ])->collapsible()->collapsed()->itemLabel(fn (array $state) => $state['q'] ?? null)->addActionLabel('Add question')->columnSpanFull(),
            ]),
            Tab::make('Final call-to-action')->columns(2)->schema([
                TextInput::make('content.final.heading')->label('Heading')->columnSpanFull(),
                Textarea::make('content.final.text')->label('Text')->rows(2)->columnSpanFull(),
                self::list('content.final.points', 'Tick points', 5),
                TextInput::make('content.final.whatsapp_text')->label('WhatsApp pre-filled message'),
            ]),
        ]);
    }
}
