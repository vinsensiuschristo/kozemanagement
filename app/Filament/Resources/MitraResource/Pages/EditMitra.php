<?php

namespace App\Filament\Resources\MitraResource\Pages;

use App\Filament\Resources\MitraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMitra extends EditRecord
{
    protected static string $resource = MitraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {
                    // Hapus user terkait jika ada
                    if ($this->record->user) {
                        $this->record->user->delete();
                    }
                }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load data user untuk form
        if ($this->record->user) {
            $data['user'] = [
                'name' => $this->record->user->name,
                'email' => $this->record->user->email,
            ];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Update user jika ada perubahan
        if (isset($data['user']) && $this->record->user) {
            $userData = $data['user'];

            // Hapus password jika kosong
            if (empty($userData['password'])) {
                unset($userData['password']);
            }

            $this->record->user->update($userData);
        }

        // Hapus data user dari array data mitra
        unset($data['user']);

        return $data;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Mitra berhasil diperbarui!';
    }
}
