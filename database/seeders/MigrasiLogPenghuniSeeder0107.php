<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MigrasiLogPenghuniSeeder0107 extends Seeder
{
    private $debugLogPath;
    private $errorCount = 0;
    private $maxErrors = 100;

    public function __construct()
    {
        $this->debugLogPath = storage_path('logs/migration_debug_'.now()->format('Ymd_His').'.log');
        file_put_contents($this->debugLogPath, "=== MIGRATION DEBUG LOG ===\n", FILE_APPEND);
    }

    private function logDebug($message)
    {
        $logMessage = now()->format('Y-m-d H:i:s').' - '.$message."\n";
        file_put_contents($this->debugLogPath, $logMessage, FILE_APPEND);
        $this->command->info($message);
    }

    private function logError($message, $exception = null)
    {
        $this->errorCount++;
        $logMessage = now()->format('Y-m-d H:i:s').' - ERROR: '.$message."\n";
        if ($exception) {
            $logMessage .= "Exception: ".$exception->getMessage()."\n";
            $logMessage .= "Trace: ".$exception->getTraceAsString()."\n";
        }
        file_put_contents($this->debugLogPath, $logMessage, FILE_APPEND);
        $this->command->error($message);
    }

    public function run()
    {
        $this->logDebug('Memulai proses migrasi data log penghuni');
        
        DB::beginTransaction();

        try {
            // 1. Validasi dan persiapan file CSV
            $csvPaths = $this->prepareCsvFiles();
            
            // 2. Buat mapping data
            $mappings = $this->createMappings($csvPaths);
            
            // 3. Proses migrasi log
            $migrationResult = $this->migrateLogData($csvPaths['log_lama'], $mappings);
            
            // 4. Update ketersediaan kamar dengan pendekatan baru
            $kamarUpdateResult = $this->updateKetersediaanKamarNewApproach();
            
            DB::commit();
            
            $this->showResults($migrationResult, $kamarUpdateResult);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleFailure($e);
        }
    }

    protected function prepareCsvFiles()
    {
        $csvPaths = [
            'penghuni_lama' => storage_path('app/csv/penghuni_lama.csv'),
            'penghuni_baru' => storage_path('app/csv/penghuni_baru.csv'),
            'kamar_lama' => storage_path('app/csv/kamar_lama.csv'),
            'kamar_baru' => storage_path('app/csv/kamar_baru.csv'),
            'log_lama' => storage_path('app/csv/log_penghuni_lama.csv'),
        ];

        foreach ($csvPaths as $key => $path) {
            if (!file_exists($path)) {
                throw new \Exception("File CSV {$key} tidak ditemukan: {$path}");
            }
            $this->logDebug("File {$key} valid: {$path}");
        }

        return $csvPaths;
    }

    protected function createMappings($csvPaths)
    {
        $this->logDebug('Memulai pembuatan mapping data');
        
        // Mapping penghuni
        $penghuniMapping = $this->mapPenghuni(
            $csvPaths['penghuni_lama'],
            $csvPaths['penghuni_baru']
        );
        
        // Mapping kamar
        $kamarMapping = $this->mapKamar(
            $csvPaths['kamar_lama'],
            $csvPaths['kamar_baru']
        );
        
        $this->logDebug(sprintf(
            "Mapping selesai. Penghuni: %d, Kamar: %d", 
            count($penghuniMapping), 
            count($kamarMapping)
        ));
        
        return [
            'penghuni' => $penghuniMapping,
            'kamar' => $kamarMapping
        ];
    }

    protected function mapPenghuni($lamaPath, $baruPath)
    {
        $mapping = [];
        $lamaData = $this->readCsv($lamaPath);
        $baruData = $this->readCsv($baruPath);
        
        // Index data baru untuk pencarian cepat
        $baruIndex = [];
        foreach ($baruData as $item) {
            $namaClean = $this->cleanKtpName($item['no_ktp']);
            $baruIndex[$namaClean] = $item['id_penghuni'];
        }
        
        // Buat mapping
        foreach ($lamaData as $item) {
            $namaClean = $this->cleanKtpName($item['no_ktp']);
            $mapping[$item['id']] = $baruIndex[$namaClean] ?? null;
            
            if (!$mapping[$item['id']]) {
                $this->logError("Tidak ditemukan mapping untuk penghuni: {$item['nama_penghuni']}");
            }
        }
        
        return $mapping;
    }

    protected function mapKamar($lamaPath, $baruPath)
    {
        $mapping = [];
        $lamaData = $this->readCsv($lamaPath);
        $baruData = $this->readCsv($baruPath);
        
        // Index data baru untuk pencarian cepat
        $baruIndex = [];
        foreach ($baruData as $item) {
            $baruIndex[$item['nomor_kamar']] = $item['id_kamar_baru'];
        }
        
        // Buat mapping
        foreach ($lamaData as $item) {
            $mapping[$item['id_kamar_lama']] = $baruIndex[$item['nomor_kamar']] ?? null;
            
            if (!$mapping[$item['id_kamar_lama']]) {
                $this->logError("Tidak ditemukan mapping untuk kamar: {$item['nomor_kamar']}");
            }
        }
        
        return $mapping;
    }

    protected function migrateLogData($logPath, $mappings)
    {
        $result = [
            'total' => 0,
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];

        $logData = $this->readCsv($logPath);
        $batch = [];
        $batchSize = 1000;
        
        $this->logDebug("Memproses {$logPath} dengan " . count($logData) . " records");

        foreach ($logData as $row) {
            $result['total']++;
            
            try {
                if ($this->errorCount >= $this->maxErrors) {
                    throw new \Exception("Batas maksimum error ({$this->maxErrors}) tercapai");
                }
                
                $logRecord = $this->processLogRecord($row, $mappings);
                $batch[] = $logRecord;
                
                if (count($batch) >= $batchSize) {
                    DB::table('log_penghunis')->insert($batch);
                    $batch = [];
                }
                
                $result['success']++;
                
            } catch (\Exception $e) {
                $result['failed']++;
                $errorMsg = "Log ID {$row['idLog']}: " . $e->getMessage();
                $result['errors'][] = $errorMsg;
                $this->logError($errorMsg, $e);
            }
        }
        
        // Insert sisa data
        if (!empty($batch)) {
            DB::table('log_penghunis')->insert($batch);
        }
        
        return $result;
    }

    protected function processLogRecord($row, $mappings)
    {
        // Validasi data dasar
        if (empty($row['ID_Penghuni']) || empty($row['ID_Kamar'])) {
            throw new \Exception("Data penghuni atau kamar tidak valid");
        }
        
        $penghuniIdBaru = $mappings['penghuni'][$row['ID_Penghuni']] ?? null;
        $kamarIdBaru = $mappings['kamar'][$row['ID_Kamar']] ?? null;
        
        if (!$penghuniIdBaru) {
            throw new \Exception("Mapping penghuni tidak ditemukan untuk ID {$row['ID_Penghuni']}");
        }
        
        if (!$kamarIdBaru) {
            throw new \Exception("Mapping kamar tidak ditemukan untuk ID {$row['ID_Kamar']}");
        }
        
        // Konversi status dengan penanganan khusus untuk pindah dan cancel
        $status = $this->convertLogStatus($row['Status']);
        
        return [
            'id' => Str::uuid(),
            'penghuni_id' => $penghuniIdBaru,
            'kamar_id' => $kamarIdBaru,
            'tanggal' => $this->parseDate($row['Tanggal']),
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    protected function convertLogStatus($status)
    {
        $status = strtolower(trim($status));
        
        return match($status) {
            'check in', 'checkin' => 'checkin',
            'check out', 'checkout' => 'checkout',
            'booking', 'booked' => 'booked',
            'pindah' => 'pindah', // Status pindah tetap sama
            'cancel' => 'cancel', // Status cancel tetap sama
            default => throw new \Exception("Status log tidak valid: {$status}")
        };
    }

    protected function updateKetersediaanKamarNewApproach()
    {
        $result = [
            'updated' => 0,
            'created' => 0,
            'failed' => 0
        ];

        $this->logDebug("Memulai update ketersediaan kamar dengan pendekatan baru");

        try {
            // Ambil status terakhir per kamar
            $statusTerakhir = DB::table('log_penghunis')
                ->select('kamar_id', DB::raw('MAX(tanggal) as max_tanggal'))
                ->groupBy('kamar_id')
                ->get();

            $progressBar = $this->command->getOutput()->createProgressBar(count($statusTerakhir));
            $progressBar->start();

            foreach ($statusTerakhir as $item) {
                try {
                    $lastStatus = DB::table('log_penghunis')
                        ->where('kamar_id', $item->kamar_id)
                        ->where('tanggal', $item->max_tanggal)
                        ->orderByDesc('created_at')
                        ->first();

                    if (!$lastStatus) {
                        $this->logDebug("Tidak ditemukan status untuk kamar: {$item->kamar_id}");
                        continue;
                    }

                    // Konversi status untuk ketersediaan kamar
                    $statusKamar = $this->convertKamarStatus($lastStatus->status);

                    DB::table('ketersediaan_kamars')->updateOrInsert(
                        ['kamar_id' => $item->kamar_id],
                        [
                            'id' => Str::uuid(),
                            'status' => $statusKamar,
                            'updated_at' => now(),
                            'created_at' => DB::table('ketersediaan_kamars')
                                ->where('kamar_id', $item->kamar_id)
                                ->value('created_at') ?? now()
                        ]
                    );

                    if (DB::table('ketersediaan_kamars')->where('kamar_id', $item->kamar_id)->exists()) {
                        $result['updated']++;
                    } else {
                        $result['created']++;
                    }

                } catch (\Exception $e) {
                    $result['failed']++;
                    $this->logError("Gagal update kamar {$item->kamar_id}: " . $e->getMessage(), $e);
                }
                
                $progressBar->advance();
            }

            $progressBar->finish();

        } catch (\Exception $e) {
            $this->logError("Error dalam proses update ketersediaan kamar: " . $e->getMessage(), $e);
            throw $e;
        }

        return $result;
    }

    protected function convertKamarStatus($statusLog)
    {
        $status = strtolower(trim($statusLog));
        
        return match($status) {
            'checkin' => 'terisi',
            'checkout' => 'kosong',
            'booked' => 'booked',
            'pindah' => 'kosong', // Status pindah membuat kamar menjadi kosong
            'cancel' => 'kosong', // Status cancel membuat kamar menjadi kosong
            default => throw new \Exception("Status kamar tidak valid: {$status}")
        };
    }

    protected function readCsv($path)
    {
        $data = [];
        $header = [];
        
        if (($handle = fopen($path, 'r')) === false) {
            throw new \Exception("Gagal membuka file: {$path}");
        }
        
        // Baca header
        if (($headerRow = fgetcsv($handle)) !== false) {
            $header = array_map('trim', $headerRow);
        }
        
        // Baca data
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($header)) {
                $data[] = array_combine($header, array_map('trim', $row));
            }
        }
        
        fclose($handle);
        return $data;
    }

    protected function cleanKtpName($ktpPath)
    {
        $filename = pathinfo($ktpPath, PATHINFO_FILENAME);
        return trim(explode('-', $filename)[0]);
    }

    protected function parseDate($dateString)
    {
        try {
            return Carbon::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            throw new \Exception("Format tanggal tidak valid: {$dateString}");
        }
    }

    protected function showResults($migrationResult, $kamarUpdateResult)
    {
        $this->logDebug("\n=== HASIL MIGRASI ===");
        $this->logDebug("Total log diproses: {$migrationResult['total']}");
        $this->logDebug("Log berhasil dimigrasi: {$migrationResult['success']}");
        $this->logDebug("Log gagal dimigrasi: {$migrationResult['failed']}");
        $this->logDebug("Kamar diupdate: {$kamarUpdateResult['updated']}");
        $this->logDebug("Kamar baru dibuat: {$kamarUpdateResult['created']}");
        $this->logDebug("Kamar gagal diproses: {$kamarUpdateResult['failed']}");
        
        if (!empty($migrationResult['errors'])) {
            $this->logDebug("\n=== ERROR LOG ===");
            foreach (array_slice($migrationResult['errors'], 0, 5) as $error) {
                $this->logDebug("- {$error}");
            }
            if (count($migrationResult['errors']) > 5) {
                $this->logDebug("... dan ".(count($migrationResult['errors']) - 5)." error lainnya");
            }
        }
        
        $this->logDebug("\nLog detail tersedia di: {$this->debugLogPath}");
    }

    protected function handleFailure($exception)
    {
        $this->logError("\n=== MIGRASI GAGAL ===");
        $this->logError("Error: ".$exception->getMessage());
        $this->logError("File: ".$exception->getFile());
        $this->logError("Line: ".$exception->getLine());
        $this->logError("Total error: {$this->errorCount}");
        
        // Tampilkan snippet log terakhir
        if (file_exists($this->debugLogPath)) {
            $lines = file($this->debugLogPath);
            $this->logError("\n=== LOG TERAKHIR ===");
            foreach (array_slice($lines, max(0, count($lines)-10), 10) as $line) {
                $this->logError(trim($line));
            }
        }
        
        $this->logError("\nSilakan periksa file log lengkap di: {$this->debugLogPath}");
        $this->command->error('Proses migrasi gagal. Silakan cek file log untuk detailnya.');
    }
}