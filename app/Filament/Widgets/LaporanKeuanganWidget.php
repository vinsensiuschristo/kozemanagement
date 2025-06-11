<?php

namespace App\Filament\Widgets;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Unit;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Carbon\Carbon;

class LaporanKeuanganWidget extends ChartWidget
{
    protected static ?string $heading = 'Laporan Keuangan Detail';
    protected static ?string $description = 'Analisis keuangan mendalam per unit dan periode';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 'full';

    public ?string $unitFilter = 'all';
    public ?string $startDate = null;
    public ?string $endDate = null;

    public function getFilterFormSchema(): array
    {
        return [
            Select::make('unitFilter')
                ->label('Filter Unit')
                ->options([
                    'all' => 'Semua Unit',
                    ...Unit::pluck('nama_cluster', 'id')->toArray()
                ])
                ->default('all')
                ->reactive(),

            DatePicker::make('startDate')
                ->label('Tanggal Mulai')
                ->default(now()->subMonths(3))
                ->reactive(),

            DatePicker::make('endDate')
                ->label('Tanggal Akhir')
                ->default(now())
                ->reactive(),
        ];
    }

    protected function getData(): array
    {
        $startDate = $this->startDate ? Carbon::parse($this->startDate) : now()->subMonths(3);
        $endDate = $this->endDate ? Carbon::parse($this->endDate) : now();

        $period = $startDate->diffInDays($endDate);
        $groupBy = $period > 90 ? 'month' : 'week';

        $labels = [];
        $pemasukanData = [];
        $pengeluaranData = [];
        $profitData = [];

        if ($groupBy === 'month') {
            $current = $startDate->copy()->startOfMonth();
            while ($current <= $endDate) {
                $labels[] = $current->format('M Y');

                $pemasukan = $this->getPemasukanByPeriod($current, $current->copy()->endOfMonth());
                $pengeluaran = $this->getPengeluaranByPeriod($current, $current->copy()->endOfMonth());

                $pemasukanData[] = $pemasukan;
                $pengeluaranData[] = $pengeluaran;
                $profitData[] = $pemasukan - $pengeluaran;

                $current->addMonth();
            }
        } else {
            $current = $startDate->copy()->startOfWeek();
            while ($current <= $endDate) {
                $labels[] = $current->format('d M');

                $pemasukan = $this->getPemasukanByPeriod($current, $current->copy()->endOfWeek());
                $pengeluaran = $this->getPengeluaranByPeriod($current, $current->copy()->endOfWeek());

                $pemasukanData[] = $pemasukan;
                $pengeluaranData[] = $pengeluaran;
                $profitData[] = $pemasukan - $pengeluaran;

                $current->addWeek();
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $pemasukanData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'type' => 'bar',
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $pengeluaranData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 2,
                    'type' => 'bar',
                ],
                [
                    'label' => 'Profit/Loss',
                    'data' => $profitData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderWidth' => 3,
                    'type' => 'line',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    private function getPemasukanByPeriod($start, $end)
    {
        $query = Pemasukan::whereBetween('tanggal', [$start, $end]);

        if ($this->unitFilter !== 'all') {
            $query->where('unit_id', $this->unitFilter);
        }

        return $query->sum('jumlah');
    }

    private function getPengeluaranByPeriod($start, $end)
    {
        $query = Pengeluaran::whereBetween('tanggal', [$start, $end]);

        if ($this->unitFilter !== 'all') {
            $query->where('unit_id', $this->unitFilter);
        }

        return $query->sum('jumlah');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.dataset.label + ": Rp " + context.parsed.y.toLocaleString("id-ID"); }',
                    ],
                ],
                'legend' => [
                    'position' => 'top',
                ],
            ],
        ];
    }
}
