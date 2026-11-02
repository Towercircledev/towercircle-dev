<?php

namespace App\Filament\Concerns;

/** Restricts a Filament resource or page to users with the "admin" role. Staff can't see or open it. */
trait AdminOnly
{
    public static function canAccess(array $parameters = []): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }
}
