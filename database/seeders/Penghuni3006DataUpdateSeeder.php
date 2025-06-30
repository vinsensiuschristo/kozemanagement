<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Penghuni;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Penghuni3006DataUpdateSeeder extends Seeder
{
    public function run()
    {
        // $csvFile = storage_path('storage\app\UPDATEDPENGHUNI3006.csv');
        $csvFile = storage_path('app/UPDATEDPENGHUNI3006.csv');
        
        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan: {$csvFile}");
            return;
        }

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file);

        DB::transaction(function () use ($file, $header) {
            $roleUser = Role::find(5);
            if (!$roleUser) {
                $this->command->error('Role dengan ID 5 (User) tidak ditemukan');
                return;
            }

            $totalImported = 0;
            $totalSkipped = 0;

            while (($row = fgetcsv($file)) !== false) {
                $data = array_combine($header, $row);

                // Skip jika sudah ada
                if (Penghuni::where('nama', $data['Nama'])->exists()) {
                    $totalSkipped++;
                    continue;
                }

                // Buat user
                $user = User::create([
                    'name' => $data['Nama'],
                    'email' => $this->generateEmail($data),
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ]);
                $user->assignRole($roleUser);

                // Format tanggal lahir
                $tanggalLahir = null;
                try {
                    $tanggalLahir = Carbon::createFromFormat('d/m/Y', $data['TglLahir'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Tanggal invalid akan diisi null
                }

                // Mapping status sesuai enum ('In', 'Out')
                $status = $data['Status'] === 'Out' ? 'Out' : 'In';

                // Buat penghuni
                Penghuni::create([
                    'id' => Str::uuid(),
                    'kode' => 'PH-' . Str::upper(Str::random(3)) . rand(100, 999),
                    'nama' => $data['Nama'],
                    'tempat_lahir' => $data['TempatLahir'],
                    'tanggal_lahir' => $tanggalLahir,
                    'agama' => strtolower($data['Agama']),
                    'no_telp' => $data['NoTelp'] === '-' ? null : $data['NoTelp'],
                    'email' => $data['Email'] === '-' ? null : $data['Email'],
                    'kontak_darurat' => '-',
                    'hubungan_kontak_darurat' => '-',
                    'kendaraan' => '-',
                    'foto_ktp' => $data['FotoKTP'],
                    'referensi' => $data['Referensi'] === '-' ? null : $data['Referensi'],
                    'status' => $status, // Hanya 'In' atau 'Out'
                ]);

                $totalImported++;
            }

            fclose($file);
            $this->command->info("Import selesai. Diimpor: {$totalImported}, Dilewati: {$totalSkipped}");
        });
    }

    protected function generateEmail($data)
    {
        if ($data['Email'] !== '-') {
            return $data['Email'];
        }
        return Str::slug($data['Nama']) . '@gmail.com';
    }
}