<?php

namespace App\Filament\Resources\MitraResource\Pages;

use App\Filament\Resources\MitraResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Spatie\Permission\Models\Role;

class CreateMitra extends CreateRecord
{
    protected static string $resource = MitraResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pisahkan data user dari data mitra
        $userData = [
            'name' => $data['nama'],
            'email' => $data['user_email'],
            'password' => $data['user_password'],
            'email_verified_at' => now(),
        ];

        // Hapus data user dari data mitra
        unset($data['user_email'], $data['user_password']);

        // Buat user terlebih dahulu
        $user = User::create($userData);

        // Assign role Mitra
        $mitraRole = Role::firstOrCreate(['name' => 'Mitra']);
        $user->assignRole($mitraRole);

        // Tambahkan user_id ke data mitra
        $data['user_id'] = $user->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Mitra berhasil dibuat')
            ->body('Mitra dan akun login telah berhasil dibuat.');
    }
}
