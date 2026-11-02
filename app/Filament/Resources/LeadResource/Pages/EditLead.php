<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\LeadResource;
use App\Models\Activity;
use App\Models\Client;
use App\Models\Lead;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditLead extends EditRecord
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('whatsapp')->label('WhatsApp')->icon('heroicon-o-chat-bubble-left-right')->color('success')
                ->url(fn (Lead $record) => 'https://wa.me/91'.$record->phone.'?text='.rawurlencode('Hi '.strtok($record->name, ' ').', this is '.(auth()->user()->name).' from Towercircle. Thanks for your enquiry'.($record->service ? ' about '.$record->service : '').'.'), true),
            Action::make('client')->label(fn (Lead $record) => $record->client ? 'Open client' : 'Convert to client')
                ->icon('heroicon-o-user-plus')->color('primary')
                ->requiresConfirmation(fn (Lead $record) => ! $record->client)
                ->modalDescription('Creates a client with this lead\'s details and marks the lead as Won.')
                ->action(function (Lead $record) {
                    $client = $record->client ?? Client::create([
                        'name' => $record->company ?: $record->name,
                        'contact_name' => $record->name,
                        'phone' => $record->phone,
                        'email' => $record->email,
                        'lead_id' => $record->id,
                        'notes' => trim(($record->service ? 'Service: '.$record->service."\n" : '').($record->website ? 'Website: '.$record->website."\n" : '').(string) $record->message),
                    ]);
                    if ($client->wasRecentlyCreated) {
                        if ($record->status !== 'won') {
                            $record->update(['status' => 'won']);
                        }
                        Activity::log($client, 'system', 'Client created from lead #'.$record->id.' ('.$record->sourceLabel().')');
                        Notification::make()->title('Client created')->success()->send();
                    }

                    return redirect(ClientResource::getUrl('edit', ['record' => $client]));
                }),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $lead = $this->record;
        if ($lead->wasChanged('status')) {
            Activity::log($lead, 'system', 'Status changed to '.(Lead::STATUSES[$lead->status] ?? $lead->status).($lead->status === 'won' && $lead->deal_value ? ' – '.inr($lead->deal_value) : ''));
        }
    }
}
