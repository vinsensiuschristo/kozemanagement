<?php

namespace App\Console\Commands;

use App\Models\Penghuni;
use App\Models\PenghuniVoucher;
use App\Models\LogPenghuni;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyVouchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:monthly-vouchers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly vouchers for all active Penghuni based on their unit voucher rules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $periode = Carbon::now()->startOfMonth();
        $this->info("🧾 Generating vouchers for the period: " . $periode->format('F Y'));

        // Ambil semua log penghuni aktif (checkin)
        $logCheckins = LogPenghuni::with(['penghuni', 'kamar.unit.voucherRules'])
            ->where('status', 'checkin')
            ->get();

        $this->info("🧾 Jumlah log penghuni 'checkin': " . $logCheckins->count());

        $totalVoucher = 0;

        foreach ($logCheckins as $log) {
            $penghuni = $log->penghuni;
            $unit = $log->kamar->unit ?? null;
            $voucherRules = $unit?->voucherRules ?? collect();

            foreach ($voucherRules as $rule) {
                $alreadyGenerated = PenghuniVoucher::where('penghuni_id', $penghuni->id)
                    ->where('voucher_id', $rule->voucher_id)
                    ->whereDate('periode', $periode)
                    ->exists();

                if (! $alreadyGenerated) {
                    for ($i = 0; $i < $rule->kuota_per_bulan; $i++) {
                        PenghuniVoucher::create([
                            'penghuni_id' => $penghuni->id,
                            'voucher_id' => $rule->voucher_id,
                            'periode' => $periode,
                            'is_used' => false,
                        ]);
                        $totalVoucher++;
                    }

                    $this->info("✅ Voucher diberikan ke: {$penghuni->nama}");
                }
            }
        }

        $this->info("🎉 Proses selesai. Total voucher dibuat: {$totalVoucher}");
    }

}
