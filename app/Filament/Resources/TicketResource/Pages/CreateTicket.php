<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Kamar;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);

        $user = auth()->user();
        $penghuni = $user->penghuni;

        if (! $penghuni) {
            throw new \Exception('Data penghuni tidak ditemukan.');
        }

        $log = $penghuni->logs()
            ->where('status', 'checkin')
            ->latest('tanggal')
            ->first();

        if (! $log) {
            throw new \Exception('Penghuni tidak memiliki log aktif.');
        }

        $kamar = Kamar::find($data['kamar_id']);
        // $kamar = Kamar::find('36603024-34c2-4803-b1d0-603ee93c60bd');

        if (! $kamar) {
            throw new \Exception('Kamar tidak ditemukan.');
        }

        $data['unit_id'] = $kamar->unit_id;

        $data['user_id'] = $user->id;
        $data['kamar_id'] = $kamar->id;
        $data['unit_id'] = $kamar->unit_id;
        $data['tanggal_lapor'] = now()->toDateString();

        return $data;
    }
}
