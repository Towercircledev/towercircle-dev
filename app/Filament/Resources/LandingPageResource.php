<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LandingPageResource\Pages;
use App\Filament\Support\Fields;
use App\Models\LandingPage;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LandingPageResource extends Resource
{
    protected static ?string $model = LandingPage::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|\UnitEnum|null $navigationGroup = 'Leads';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Ad landing pages';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Group::make()->columnSpan(2)->schema([
                Section::make('Page')->columns(2)->schema([
                    Fields::title()->label('Internal name')->helperText('e.g. Shopify – Google Ads'),
                    Fields::slug('lp'),
                    TextInput::make('eyebrow')->label('Small line above heading')->maxLength(80)->placeholder('Shopify experts in India'),
                    TextInput::make('offer')->label('Offer badge')->maxLength(80)->placeholder('Free store audit this month'),
                    TextInput::make('heading')->required()->maxLength(120)->columnSpanFull()
                        ->helperText('Match the Google search. e.g. "Shopify Store Development That Brings Sales"'),
                    Textarea::make('subheading')->rows(2)->maxLength(300)->columnSpanFull(),
                    TextInput::make('price_from')->label('Starting price')->numeric()->prefix('₹'),
                    Select::make('service_id')->label('Related service')->relationship('service', 'title')
                        ->helperText('Saved with each lead from this page'),
                ]),
                Section::make('Why choose us')->schema([
                    Repeater::make('benefits')->hiddenLabel()->schema([
                        TextInput::make('title')->required()->maxLength(80),
                        Textarea::make('text')->rows(2)->maxLength(200),
                    ])->columns(2)->defaultItems(0)->maxItems(6)->addActionLabel('Add benefit')
                        ->itemLabel(fn (array $state) => $state['title'] ?? null)->collapsible(),
                ]),
                Section::make('How it works')->collapsed()->schema([
                    Repeater::make('steps')->hiddenLabel()->schema([
                        TextInput::make('title')->required()->maxLength(60),
                        TextInput::make('text')->maxLength(160),
                    ])->columns(2)->defaultItems(0)->maxItems(5)->addActionLabel('Add step'),
                ]),
                Section::make('Questions & answers')->collapsed()->schema([
                    Repeater::make('faqs')->hiddenLabel()->schema([
                        TextInput::make('q')->label('Question')->required(),
                        Textarea::make('a')->label('Answer')->rows(2)->required(),
                    ])->defaultItems(0)->addActionLabel('Add question')->itemLabel(fn (array $state) => $state['q'] ?? null)->collapsible(),
                ]),
                Fields::seo(),
            ]),
            Group::make()->columnSpan(1)->schema([
                Section::make('Form')->schema([
                    TextInput::make('form_heading')->maxLength(80)->placeholder('Get a free quote in 2 hours'),
                    TextInput::make('cta_label')->label('Button text')->maxLength(40)->placeholder('Get my free quote'),
                ]),
                Section::make('Image')->schema([Fields::image('hero_image', 'landing', 'Hero image')
                    ->helperText('Optional. Without one, the homepage illustration is used.')]),
                Section::make('Options')->schema([
                    Toggle::make('show_projects')->label('Show recent projects')->default(true),
                    Toggle::make('show_testimonials')->label('Show client reviews')->default(true),
                    Toggle::make('is_visible')->label('Page is live')->default(true),
                    Toggle::make('noindex')->label('Hide from Google search')->default(true)
                        ->helperText('Keep on for ad-only pages so they don\'t compete with your main service pages'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->weight('bold')->description(fn ($record) => '/lp/'.$record->slug),
                TextColumn::make('service.title')->placeholder('—'),
                TextColumn::make('leads')->label('Leads (30 days)')
                    ->state(fn (LandingPage $record) => \App\Models\Lead::where('page_url', 'like', '%/lp/'.$record->slug.'%')->where('created_at', '>=', now()->subDays(30))->count()),
                ToggleColumn::make('is_visible')->label('Live'),
            ])
            ->recordActions([
                Action::make('open')->icon('heroicon-o-arrow-top-right-on-square')->label('View')->color('gray')
                    ->url(fn (LandingPage $record) => route('landing', $record), true),
                ReplicateAction::make()->label('Duplicate')
                    ->mutateRecordDataUsing(function (array $data) {
                        $data['title'] .= ' (copy)';
                        $data['slug'] .= '-'.Str::lower(Str::random(4));
                        $data['is_visible'] = false;

                        return $data;
                    }),
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLandingPages::route('/'),
            'create' => Pages\CreateLandingPage::route('/create'),
            'edit' => Pages\EditLandingPage::route('/{record}/edit'),
        ];
    }
}
