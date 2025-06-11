<?php

namespace App\Filament\Resources\PemasukanResource\Pages;

use App\Filament\Resources\PemasukanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePemasukan extends CreateRecord
{
    protected static string $resource = PemasukanResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['is_checkin'] ?? false) {
            $checkin = \App\Models\LogPenghuni::with('kamar.unit')->find($data['checkin_id']);
            $data['unit_id'] = $checkin->kamar->unit_id ?? null;
            $data['kamar_id'] = $checkin->kamar_id ?? null;
            $data['penghuni_id'] = $checkin->penghuni_id ?? null;
        }

        // Jangan simpan ke DB
        unset($data['is_checkin']);

        $data['created_by'] = auth()->id();

        return $data;
    }
}
