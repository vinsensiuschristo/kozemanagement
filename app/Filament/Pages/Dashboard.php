<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Dashboard extends BaseDashboard
{
    public function mount(): void
    {
        $user = Auth::user();

        // Debug: Log user info
        Log::info('Dashboard mount - User ID: ' . ($user?->id ?? 'null'));
        Log::info('Dashboard mount - User roles: ' . ($user ? $user->getRoleNames()->toJson() : 'no user'));

        if ($user && $user->hasRole('Owner')) {
            Log::info('Dashboard mount - User is Owner, loading owner data');
            $this->loadOwnerData();
        }
    }

    protected function loadOwnerData()
    {
        $user = Auth::user();
        $ownerData = $user->owner ?? null;

        Log::info('Dashboard loadOwnerData - Owner data: ' . ($ownerData ? 'exists' : 'null'));

        $this->ownerData = $ownerData;
    }

    protected function getViewData(): array
    {
        $user = Auth::user();
        $data = [
            'role' => $user ? ($user->hasRole('Owner') ? 'owner' : 'admin') : 'guest',
            'ownerData' => $this->ownerData ?? null,
        ];

        Log::info('Dashboard getViewData: ' . json_encode($data));

        return $data;
    }

    public $ownerData = null;
}
