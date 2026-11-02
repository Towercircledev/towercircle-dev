<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

/** Key/value store for site content and settings, edited from the admin. */
class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    protected $casts = ['value' => 'array'];

    /** Setting::get('site.phone') reads key "site", then the "phone" entry inside it. */
    public static function get(string $path, mixed $default = null): mixed
    {
        $all = Cache::rememberForever('settings.all', fn () => static::query()->get()->mapWithKeys(
            fn ($s) => [$s->key => $s->value]
        )->all());

        $value = Arr::get($all, $path);

        return ($value === null || $value === '') ? $default : $value;
    }

    public static function put(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings.all');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings.all'));
        static::deleted(fn () => Cache::forget('settings.all'));
    }
}
