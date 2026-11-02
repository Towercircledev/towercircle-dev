<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

/** Form pieces reused across resources. */
class Fields
{
    public static function title(string $name = 'title'): TextInput
    {
        return TextInput::make($name)->required()->maxLength(150)->live(onBlur: true)
            ->afterStateUpdated(function (Set $set, ?string $state, $operation) {
                if ($operation === 'create') {
                    $set('slug', Str::slug((string) $state));
                }
            });
    }

    public static function slug(string $prefix): TextInput
    {
        return TextInput::make('slug')->required()->maxLength(150)->unique(ignoreRecord: true)
            ->alphaDash()->prefix(rtrim(url($prefix), '/').'/')
            ->helperText('The web address. Short, lowercase, words joined with hyphens.');
    }

    public static function image(string $name, string $dir, ?string $label = null): FileUpload
    {
        return FileUpload::make($name)->label($label ?? Str::headline($name))->image()->disk('public')->directory($dir)
            ->maxSize(8192)->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
            ->saveUploadedFileUsing(fn ($file) => \App\Support\ImageOptimizer::store($file, $dir))
            ->helperText('JPG, PNG, WebP or SVG. Photos are resized and converted to WebP automatically.');
    }

    public static function visibility(bool $withFeatured = false): Section
    {
        $fields = [
            Toggle::make('is_visible')->label('Show on website')->default(true),
            TextInput::make('sort')->label('Order')->numeric()->default(0)->helperText('Lower numbers appear first'),
        ];
        if ($withFeatured) {
            array_splice($fields, 1, 0, [Toggle::make('is_featured')->label('Show on homepage')]);
        }

        return Section::make('Visibility')->schema($fields);
    }

    public static function seo(): Section
    {
        return Section::make('Google search (SEO)')
            ->description('How this page appears in Google results. Leave empty to use the title and summary.')
            ->collapsed()
            ->schema([
                TextInput::make('seo_title')->label('Search title')->maxLength(70)->helperText('Up to about 60 characters'),
                Textarea::make('seo_description')->label('Search description')->rows(2)->maxLength(300)->helperText('Up to about 155 characters'),
            ]);
    }
}
