<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Owner;
use App\Models\Unit;
use App\Models\AlamatUnit;
use App\Models\TipeKamar;
use App\Models\HargaKamar;
use App\Models\Kamar;
use App\Models\KetersediaanKamar;
use App\Models\Penghuni;
use App\Models\LogPenghuni;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DataUpdateSeederJuni30 extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Dapatkan owner yang sudah ada
            $owner = Owner::where('nomor_ktp', '3674041610670002')->firstOrFail();

            // 2. Cari atau buat unit
            $unit = Unit::firstOrCreate(
                ['nama_cluster' => 'Alesha House Purple 1 No 10'],
                [
                    'id' => (string) Str::uuid(),
                    'id_owner' => $owner->id,
                    'nomor_kontrak' => '001/KZK/06/2025',
                    'tanggal_awal_kontrak' => Carbon::createFromFormat('Y-m-d', '2025-06-02'),
                    'tanggal_akhir_kontrak' => Carbon::createFromFormat('Y-m-d', '2027-06-30'),
                    'multi_tipe' => 1,
                    'disewakan_untuk' => 'campur',
                    'status' => 1,
                    'tahun_dibangun' => null,
                    'deskripsi' => null,
                ]
            );

            $this->command->info('Menggunakan unit dengan ID: '.$unit->id);

            // 3. Update atau buat alamat unit
            AlamatUnit::updateOrCreate(
                ['unit_id' => $unit->id],
                [
                    'alamat' => 'Jl. Raya Pagedangan No.67, Pagedangan, Kec. Pagedangan, Kabupaten Tangerang, Banten 15339',
                    'provinsi' => 'Banten',
                    'kabupaten' => 'Kabupaten Tangerang',
                    'kecamatan' => 'Pagedangan',
                    'deskripsi' => 'Alamat lengkap kosan Alesha House Purple 1 No 10',
                ]
            );

            // 4. Daftar fasilitas yang akan digunakan (hanya yang sudah ada)
            $fasilitasYangDigunakan = [
                'AC', 'Water Heater', 'Lemari', 'Kamar Mandi Luar', 'Dapur Bersama'
            ];

            // 5. Buat atau update tipe kamar, harga, dan kamar
            $tipeKamars = [
                [
                    'lantai' => 1,
                    'harga' => 1800000,
                    'kamars' => ['101', '102'],
                    'fasilitas' => ['AC', 'Lemari', 'Water Heater']
                ],
                [
                    'lantai' => 2,
                    'harga' => 1700000,
                    'kamars' => ['201', '202'],
                    'fasilitas' => ['AC', 'Lemari']
                ],
                [
                    'lantai' => 3,
                    'harga' => 1600000,
                    'kamars' => ['301', '302'],
                    'fasilitas' => ['Lemari']
                ]
            ];

            foreach ($tipeKamars as $data) {
                $tipeKamar = TipeKamar::updateOrCreate(
                    [
                        'unit_id' => $unit->id,
                        'nama_tipe' => 'Lantai ' . $data['lantai']
                    ],
                    ['id' => (string) Str::uuid()]
                );

                HargaKamar::updateOrCreate(
                    ['tipe_kamar_id' => $tipeKamar->id],
                    [
                        'id' => (string) Str::uuid(),
                        'harga_perbulan' => $data['harga'],
                        'minimal_deposit' => 500000,
                        'harga_perminggu' => ceil($data['harga'] / 4),
                        'harga_perhari' => ceil($data['harga'] / 30),
                    ]
                );

                // Attach fasilitas ke tipe kamar
                foreach ($data['fasilitas'] as $fasilitasName) {
                    $fasilitas = Fasilitas::where('nama', $fasilitasName)->first();
                    if ($fasilitas) {
                        $tipeKamar->fasilitas()->syncWithoutDetaching([$fasilitas->id]);
                    }
                }

                foreach ($data['kamars'] as $noKamar) {
                    Kamar::updateOrCreate(
                        [
                            'unit_id' => $unit->id,
                            'nama' => $noKamar
                        ],
                        [
                            'id' => (string) Str::uuid(),
                            'tipe_kamar_id' => $tipeKamar->id,
                            'lantai' => $data['lantai'],
                            'ukuran' => null,
                        ]
                    );
                }
            }

            // 6. Buat atau update ketersediaan kamar dan penghuni
            $this->updateKetersediaanDanPenghuni($unit->id, $owner->user_id);

            $this->command->info('Proses update data selesai');
        });
    }

    protected function updateKetersediaanDanPenghuni($unitId, $adminId)
    {
        // Data penghuni
        $penghuniData = [
            [
                'nama' => 'Leni Fatmawati',
                'tanggal_lahir' => '1995-05-15',
                'no_telp' => '081234567891',
                'kamar' => '202',
                'checkin_date' => now()->subMonths(3),
                'status' => 'In' // Sesuai enum penghuni
            ],
            [
                'nama' => 'Mutiah Kharisma Alenta',
                'tanggal_lahir' => '1998-08-20',
                'no_telp' => '081234567892',
                'kamar' => '302',
                'checkin_date' => now()->subMonth(),
                'status' => 'In' // Sesuai enum penghuni
            ]
        ];

        // Mapping status ketersediaan kamar
        $statusMapping = [
            '101' => 'kosong',
            '102' => 'kosong',
            '201' => 'kosong',
            '202' => 'terisi',
            '301' => 'booked',
            '302' => 'terisi'
        ];

        foreach ($statusMapping as $noKamar => $status) {
            $kamar = Kamar::where('nama', $noKamar)
                        ->where('unit_id', $unitId)
                        ->first();

            if ($kamar) {
                // Update ketersediaan kamar
                KetersediaanKamar::updateOrCreate(
                    ['kamar_id' => $kamar->id],
                    ['status' => $status]
                );

                // Cari penghuni untuk kamar ini
                $penghuni = collect($penghuniData)->firstWhere('kamar', $noKamar);

                if ($penghuni) {
                    // Buat atau update data penghuni
                    $penghuniModel = Penghuni::updateOrCreate(
                        ['nama' => $penghuni['nama']],
                        [
                            'id' => (string) Str::uuid(),
                            'kode' => 'PH-'.Str::upper(Str::random(5)),
                            'tempat_lahir' => 'Jakarta',
                            'tanggal_lahir' => $penghuni['tanggal_lahir'],
                            'agama' => 'islam',
                            'no_telp' => $penghuni['no_telp'],
                            'email' => Str::slug($penghuni['nama']).'-'.rand(100,999).'@example.com',
                            'kontak_darurat' => '081298765432',
                            'hubungan_kontak_darurat' => 'Orang Tua',
                            'kendaraan' => 'B '.rand(1000,9999).' ABC',
                            'foto_ktp' => 'KTPPenghuni/'.Str::slug($penghuni['nama']).'.jpg',
                            'referensi' => 'Teman',
                            'status' => $penghuni['status']
                        ]
                    );

                    // Buat log penghuni jika belum ada dengan status lowercase sesuai enum
                    LogPenghuni::firstOrCreate(
                        [
                            'penghuni_id' => $penghuniModel->id,
                            'kamar_id' => $kamar->id
                        ],
                        [
                            'id' => (string) Str::uuid(),
                            'tanggal' => $penghuni['checkin_date'],
                            'status' => 'checkin', // Lowercase sesuai enum
                            'created_by' => $adminId
                        ]
                    );
                }
            }
        }

        $this->command->info('Update ketersediaan kamar dan penghuni selesai');
    }
}