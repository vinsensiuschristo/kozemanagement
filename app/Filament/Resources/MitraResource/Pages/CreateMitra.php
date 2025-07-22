<?php

namespace App\Filament\Resources\MitraResource\Pages;

use App\Filament\Resources\MitraResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMitra extends CreateRecord
{
    protected static string $resource = MitraResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Buat user terlebih dahulu
        $userData = $data['user'];
        $user = User::create($userData);

        // Assign role Mitra
        $user->assignRole('Mitra');

        // Hapus data user dari array data mitra
        unset($data['user']);

        // Buat mitra dan hubungkan dengan user
        $data['user_id'] = $user->id;
        $mitra = static::getModel()::create($data);

        return $mitra;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Mitra berhasil dibuat dengan akun login!';
    }
}
