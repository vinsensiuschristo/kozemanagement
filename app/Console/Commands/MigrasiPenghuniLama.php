<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Penghuni;

class MigrasiPenghuniLama extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrasi-penghuni-lama';



    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasikan data dari tabel penghuni_lama ke tabel penghunis baru';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $penghuniLama = DB::connection('mysql_lama')->table('penghuni')->get();

        foreach ($penghuniLama as $lama) {
            // Cek duplikat email
            // if (!empty($lama->Email) && Penghuni::where('email', $lama->Email)->exists()) {
            //     $this->warn("Email {$lama->Email} sudah ada, lewati...");
            //     continue;
            // }



            Penghuni::create([
                'kode' => 'PH-' . Str::upper(Str::random(6)),
                'nama' => $lama->Nama,
                'tempat_lahir' => $lama->TempatLahir,
                'tanggal_lahir' => ($lama->TglLahir === '0000-00-00' || empty($lama->TglLahir)) ? '2000-01-01' : $lama->TglLahir,
                'agama' => $lama->Agama,
                'no_telp' => $lama->NoTelp,
                'email' => $lama->Email,
                'kontak_darurat' => $lama->EmergencyCtc,
                'hubungan_kontak_darurat' => $lama->HubEC,
                'kendaraan' => $lama->Kendaraan,
                'foto_ktp' => $lama->FotoKTP,
                'referensi' => $lama->Referensi,
                'status' => $lama->Status === 'In' ? 'In' : 'Out',
            ]);

            $this->info("✅ Penghuni {$lama->Nama} berhasil dimigrasi.");
        }

        $this->info("Selesai migrasi semua data.");
    }
}
