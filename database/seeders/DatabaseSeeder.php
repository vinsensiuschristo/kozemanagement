<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil role seeder dulu
        $this->call(RoleSeeder::class);

        // Buat akun Superadmin
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
            ]
        );

        $superadmin->assignRole('Superadmin');

        $this->command->info('Akun Superadmin berhasil dibuat:');
        $this->command->info('Email: superadmin@example.com');
        $this->command->info('Password: password123');

        // $this->call([
        //     UserSeeder::class,
        //     OwnerSeeder::class,
        //     FasilitasSeeder::class,
        //     UnitSeeder::class,
        //     AlamatUnitSeeder::class,
        //     FotoUnitSeeder::class,
        //     TipeKamarSeeder::class,
        //     KamarSeeder::class,
        //     HargaKamarSeeder::class,
        //     KetersediaanKamarSeeder::class,
        //     FotoKamarSeeder::class,
        //     FasilitasUnitSeeder::class,
        //     PenghuniSeeder::class,
        //     PemasukanSeeder::class,
        //     PengeluaranSeeder::class,
        //     LogPenghuniSeeder::class,
        // ]);
    }
}
