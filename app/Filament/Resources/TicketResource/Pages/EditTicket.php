<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('conversation')
                ->label('Lihat Percakapan')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('primary')
                ->url(fn() => TicketResource::getUrl('conversation', ['record' => $this->record])),

            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ticket berhasil diperbarui')
            ->body('Data ticket telah berhasil diperbarui.');
    }
}
