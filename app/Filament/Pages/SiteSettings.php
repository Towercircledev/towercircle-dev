<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use App\Models\Setting;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Site settings';

    protected string $view = 'filament.pages.settings-form';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site' => Setting::get('site', []) + \App\Support\Defaults::site(),
            'pages' => Setting::get('pages', []) + ['team' => true, 'blog' => true, 'faq' => true],
            'tracking' => Setting::get('tracking', []),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Section::make('Business & contact')->columns(3)->schema([
                TextInput::make('site.name')->label('Business name')->default('Towercircle')->required(),
                TextInput::make('site.tagline')->maxLength(80),
                TextInput::make('site.email')->email()->required(),
                TextInput::make('site.phone')->tel()->regex('/^[6-9]\d{9}$/')->helperText('10-digit mobile'),
                TextInput::make('site.whatsapp')->label('WhatsApp number')->tel()->regex('/^[6-9]\d{9}$/')->helperText('Powers every WhatsApp button'),
                TextInput::make('site.hours')->label('Working hours')->placeholder('Mon – Sat, 10am – 7pm'),
                Textarea::make('site.address')->rows(2)->columnSpan(2),
                TextInput::make('site.city')->placeholder('Hyderabad'),
                TextInput::make('site.map_embed')->label('Google Maps embed link')->url()->columnSpanFull()
                    ->helperText('Google Maps → Share → Embed a map → copy only the link inside src="…"'),
                Textarea::make('site.footer_about')->label('Footer text')->rows(2)->maxLength(250)->columnSpanFull(),
                TextInput::make('site.lead_emails')->label('Send new-lead alerts to')->columnSpanFull()
                    ->helperText('One or more emails, separated by commas'),
            ]),
            Section::make('Social links')->columns(3)->schema([
                TextInput::make('site.instagram')->url(),
                TextInput::make('site.facebook')->url(),
                TextInput::make('site.linkedin')->label('LinkedIn')->url(),
                TextInput::make('site.youtube')->label('YouTube')->url(),
                TextInput::make('site.google_reviews')->label('Google reviews link')->url(),
            ]),
            Section::make('Pages on/off')->description('Hidden pages disappear from the menu, footer, homepage and Google.')->columns(3)->schema([
                Toggle::make('pages.team')->label('Team'),
                Toggle::make('pages.blog')->label('Blog'),
                Toggle::make('pages.faq')->label('FAQ'),
            ]),
            Section::make('Tracking & Google Ads')->columns(2)->collapsed()->schema([
                TextInput::make('tracking.ga4')->label('Google Analytics ID')->placeholder('G-XXXXXXX'),
                TextInput::make('tracking.ads_id')->label('Google Ads ID')->placeholder('AW-123456789'),
                TextInput::make('tracking.ads_lead_label')->label('Lead conversion label')
                    ->helperText('From Google Ads → Conversions → your "Lead form" action → Tag setup'),
                TextInput::make('tracking.ads_conversion_name')->label('Offline conversion name')->placeholder('Client won')
                    ->helperText('Must match the name of your "Import from clicks" conversion action'),
                TextInput::make('tracking.meta_pixel')->label('Meta Pixel ID (optional)'),
                TextInput::make('tracking.search_console')->label('Google Search Console code')->helperText('Only the content="…" value'),
            ]),
            Section::make('Default sharing image')->collapsed()->schema([
                Fields::image('site.share_image', 'site', 'Share picture (1200 × 630)'),
            ]),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();
        foreach (['site', 'pages', 'tracking'] as $key) {
            Setting::put($key, $state[$key] ?? []);
        }
        Notification::make()->title('Settings saved')->success()->send();
    }
}
