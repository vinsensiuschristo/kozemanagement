<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MigrasiOwnerLama extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrasi-owner-lama';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasikan data owner dari database lama ke struktur baru, termasuk user-nya';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dataLama = DB::connection('mysql_lama')->table('owner')->get();

        $usedPhones = [];

        foreach ($dataLama as $lama) {
            // Email: jika kosong atau '-', buat dari nama
            $email = trim($lama->Email);
            if (empty($email) || $email === '-') {
                $email = Str::slug($lama->Nama, '-') . '@gmail.com';
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $lama->Nama,
                    'email' => $email,
                    'password' => Hash::make('password123'),
                ]
            );

            // Buat nomor telepon jika kosong atau "-"
            $phone = trim($lama->NoTelp);
            if ($phone === '-' || empty($phone)) {
                $phone = $this->generateUniquePhoneNumber($usedPhones);
            }



            // Simpan Owner
            Owner::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'nama' => $lama->Nama,
                'tempat_lahir' => $lama->TempatLahir,
                'tanggal_lahir' => $lama->TanggalLahir,
                'agama' => $lama->Agama,
                'nomor_telepon' => $phone,
                'email' => $email,
                'alamat' => $lama->TempatTinggal,
                'bank' => $lama->Bank,
                'nomor_rekening' => $lama->NoRek,
                'nomor_ktp' => $lama->NoKTP,
                'foto_ktp' => $lama->FotoKTP,
            ]);

            $usedPhones[] = $phone;
            $this->info("✅ Owner {$lama->Nama} berhasil dimigrasi.");
        }

        $this->info("✅ Selesai migrasi semua data owner.");
    }

    private function generateUniquePhoneNumber(array $usedPhones): string
    {
        $base = 80000000001;

        while (true) {
            $generated = 'new' . $base;

            if (
                !in_array($generated, $usedPhones) &&
                !Owner::where('nomor_telepon', $generated)->exists()
            ) {
                return $generated;
            }

            $base++;
        }
    }
}
