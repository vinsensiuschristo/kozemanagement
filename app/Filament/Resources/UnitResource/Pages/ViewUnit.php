<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUnit extends ViewRecord
{
    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil-square')
                ->color('warning'),

            Actions\Action::make('room_layout')
                ->label('🏠 Lihat Layout Kamar')
                ->icon('heroicon-o-squares-2x2')
                ->color('success')
                ->url(fn() => static::getResource()::getUrl('room-layout', ['record' => $this->record]))
                ->tooltip('Visualisasi dan manajemen kamar secara real-time'),

            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->color('danger'),
        ];
    }
}
