<?php

namespace App\Filament\Pages;

use App\Models\Unit;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class UnitDetail extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static string $view = 'filament.pages.unit-detail';
    protected static bool $shouldRegisterNavigation = false;

    public Unit $unit;
    public $tenants = [];

    public function mount($unitId)
    {
        $user = Auth::user();

        // Pastikan user adalah owner dan unit miliknya
        $this->unit = Unit::where('id', $unitId)
            ->where('id_owner', $user->owner?->id)
            ->firstOrFail();

        // Load dummy data penghuni
        $this->tenants = $this->getDummyTenants();
    }

    public function getTitle(): string
    {
        return "Detail Kamar {$this->unit->nama_cluster}";
    }

    private function getDummyTenants(): array
    {
        return [
            [
                'no' => '101',
                'nama_penghuni' => 'Nadia Aulanar Kartikasari',
                'deposit' => 'Rp 1.000.000,00',
                'no_telp' => '+62 878-8726-7356',
            ],
            [
                'no' => '102',
                'nama_penghuni' => 'Intan Saty Damayanti',
                'deposit' => 'Rp 1.000.000,00',
                'no_telp' => '+62 858-9493-8571',
            ],
            [
                'no' => '103',
                'nama_penghuni' => 'Putri Anggraini',
                'deposit' => 'Rp 1.000.000,00',
                'no_telp' => '+62 857-1690-5458',
            ],
        ];
    }

    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('Owner');
    }
}
