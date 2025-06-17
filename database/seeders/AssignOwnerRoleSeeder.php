<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class AssignOwnerRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil role Owner
        $ownerRole = Role::where('name', 'Owner')->first();

        if (!$ownerRole) {
            $this->command->error('Role Owner tidak ditemukan!');
            return;
        }

        // Ambil semua user dari ID 416 sampai 493
        $users = User::whereBetween('id', [416, 493])->get();

        if ($users->isEmpty()) {
            $this->command->error('Tidak ada user ditemukan dengan ID 416-493!');
            return;
        }

        $this->command->info('Memulai proses assign role Owner...');
        $bar = $this->command->getOutput()->createProgressBar($users->count());

        foreach ($users as $user) {
            // Assign role Owner ke user
            $user->assignRole($ownerRole);
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('Berhasil mengassign role Owner ke ' . $users->count() . ' user!');
    }
}
