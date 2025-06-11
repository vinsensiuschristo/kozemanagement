<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';

    public $role;
    public $user;
    public $ownerData;

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->role = $this->user->getRoleNames()->first();

        // Jika owner, ambil data owner
        if ($this->role === 'Owner') {
            $this->ownerData = $this->user->owner;
        }
    }

    public function getTitle(): string
    {
        return match ($this->role) {
            'Superadmin' => '🚀 Dashboard Superadmin',
            'Owner' => '🏢 Dashboard Owner - ' . ($this->ownerData?->nama ?? 'Owner'),
            default => '📊 Dashboard Koze Management'
        };
    }

    public function getSubheading(): ?string
    {
        return match ($this->role) {
            'Superadmin' => 'Kelola seluruh sistem manajemen kos',
            'Owner' => 'Kelola unit kos Anda dengan mudah dan efisien',
            default => 'Sistem manajemen kos terpadu'
        };
    }
}
