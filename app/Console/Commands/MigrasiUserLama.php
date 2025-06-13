<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\User;

class MigrasiUserLama extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrasi-user-lama';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasikan data user dari database lama ke tabel users baru dan assign role dengan Spatie';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $usersLama = DB::connection('mysql_lama')->table('user')->get();

        foreach ($usersLama as $lama) {
            $roleName = ucfirst(strtolower($lama->Status)); // e.g. "Admin"

            // Validasi role sudah ada
            if (!Role::where('name', $roleName)->exists()) {
                $this->warn("Role '{$roleName}' belum ada. Lewati user {$lama->Nama}.");
                continue;
            }

            // Cek jika sudah ada user dengan nama yang sama
            $email = strtolower(str_replace(' ', '', $lama->Nama)) . '@gmail.com';

            if (User::where('email', $email)->exists()) {
                $this->warn("User dengan email {$email} sudah ada. Lewati...");
                continue;
            }

            // Simpan user
            $user = User::create([
                'name' => $lama->Nama,
                'email' => $email,
                'password' => Hash::make($roleName . '123'),
            ]);

            // Assign role
            $user->assignRole($roleName);

            $this->info("✅ User {$lama->Nama} berhasil dimigrasi dan diberikan role {$roleName}.");
        }

        $this->info("🚀 Selesai migrasi semua user lama.");
    }
}
