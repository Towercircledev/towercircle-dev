<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Models\Client;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    public function getSubheading(): ?string
    {
        $c = $this->record;
        $bits = [];
        if (auth()->user()?->isAdmin() && ($owed = $c->outstanding())) {
            $bits[] = 'Owes '.inr($owed);
        }
        if ($r = $c->renewals()->where('is_active', true)->orderBy('due_on')->first()) {
            $bits[] = 'Next renewal '.$r->due_on->format('d M Y').' ('.$r->label().')';
        }
        if ($down = $c->sites()->where('monitor', true)->where('uptime_status', 'down')->count()) {
            $bits[] = $down.' website(s) DOWN';
        }

        return $bits ? implode(' · ', $bits) : null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('wa')->label('WhatsApp')->icon('heroicon-o-chat-bubble-left-right')->color('success')
                ->url(fn (Client $record) => $record->whatsapp(), true)->visible(fn (Client $record) => (bool) $record->whatsapp()),
            Action::make('call')->label('Call')->icon('heroicon-o-phone')->color('gray')
                ->url(fn (Client $record) => 'tel:+91'.substr(preg_replace('/\D/', '', (string) $record->phone), -10))->visible(fn (Client $record) => (bool) $record->phone),
            DeleteAction::make(),
        ];
    }
}
