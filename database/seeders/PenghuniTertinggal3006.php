<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Penghuni;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PenghuniTertinggal3006 extends Seeder
{
    public function run()
    {
        $penghuniData = [
            [
                'nama' => 'Abdufattah Yurianta',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2000-09-05',
                'agama' => 'islam',
                'no_telp' => '+62 856-5510-3170',
                'foto_ktp' => 'KTPPenghuni/Abdufattah Yurianta-2000-09-05.jpeg',
                'status' => 'In'
            ],
            [
                'nama' => 'Sainah',
                'tempat_lahir' => 'Pemalang',
                'tanggal_lahir' => '1981-10-17',
                'agama' => 'islam',
                'no_telp' => null,
                'foto_ktp' => 'KTPPenghuni/Sainah -1981-10-17.jpeg',
                'status' => 'In'
            ],
            [
                'nama' => 'Muhammad Rizki Zamzami',
                'tempat_lahir' => 'Sungai Belida',
                'tanggal_lahir' => '2002-05-26',
                'agama' => 'islam',
                'no_telp' => null,
                'foto_ktp' => 'KTPPenghuni/Muhammad Rizki Zamzami -2002-05-26.jpeg',
                'status' => 'In'
            ],
            [
                'nama' => 'Dino Rafly Priatna',
                'tempat_lahir' => 'Samarinda',
                'tanggal_lahir' => '2000-11-12',
                'agama' => 'islam',
                'no_telp' => null,
                'foto_ktp' => 'KTPPenghuni/Dino Rafly Priatna-2000-11-12.jpeg',
                'status' => 'Out'
            ]
        ];

        DB::transaction(function () use ($penghuniData) {
            $roleUser = Role::find(5); // Role User
            
            foreach ($penghuniData as $data) {
                // Cek apakah penghuni sudah ada
                if (Penghuni::where('nama', $data['nama'])->exists()) {
                    continue;
                }

                // Generate email unik dengan angka acak
                $email = $this->generateUniqueEmail($data['nama']);

                // Buat user
                $user = User::create([
                    'name' => $data['nama'],
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ]);

                if ($roleUser) {
                    $user->assignRole($roleUser);
                }

                // Buat penghuni
                Penghuni::create([
                    'id' => Str::uuid(),
                    'kode' => 'PH-' . Str::upper(Str::random(3)) . rand(100, 999),
                    'nama' => $data['nama'],
                    'tempat_lahir' => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'agama' => $data['agama'],
                    'no_telp' => $data['no_telp'],
                    'email' => null, // Email tidak disimpan di tabel penghuni
                    'kontak_darurat' => '-',
                    'hubungan_kontak_darurat' => '-',
                    'kendaraan' => '-',
                    'foto_ktp' => $data['foto_ktp'],
                    'referensi' => null,
                    'status' => $data['status']
                ]);
            }
        });

        $this->command->info('4 data penghuni yang terlewat berhasil diimpor');
    }

    protected function generateUniqueEmail($name)
    {
        $baseEmail = Str::slug($name) . '-' . rand(100, 999) . '@gmail.com';
        $counter = 1;
        
        while (User::where('email', $baseEmail)->exists()) {
            $baseEmail = Str::slug($name) . '-' . rand(100, 999) . '-' . $counter . '@gmail.com';
            $counter++;
        }
        
        return $baseEmail;
    }
}