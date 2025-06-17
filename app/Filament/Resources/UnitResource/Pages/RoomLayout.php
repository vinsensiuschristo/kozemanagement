<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use App\Models\Unit;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class RoomLayout extends Page
{
    protected static string $resource = UnitResource::class;
    protected static string $view = 'filament.resources.unit.pages.room-layout';

    public Unit $record;
    public Collection $rooms;

    public $hargaPerTipe = 10;

    public function mount(Unit $record): void
    {
        $this->record = $record;
        $this->rooms = $this->getRoomsData();
    }

    public function getTitle(): string
    {
        return "Layout Kamar - {$this->record->nama_cluster}";
    }

    public function getSubheading(): ?string
    {
        return 'Manajemen kamar dan penghuni';
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url($this->getResource()::getUrl('view', ['record' => $this->record])),
        ];
    }

    // Method untuk mendapatkan data dummy kamar
    public function getRoomsData(): Collection
    {
        $rooms = [
            [
                'id' => 1,
                'nama' => 'K101',
                'lantai' => 1,
                'tipe' => 'Standard',
                'status' => 'kosong',
                'harga' => 800000,
                'ukuran' => '3x4m',
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar'],
                'penghuni' => null,
            ],
            [
                'id' => 2,
                'nama' => 'K102',
                'lantai' => 1,
                'tipe' => 'Standard',
                'status' => 'terisi',
                'harga' => 800000,
                'ukuran' => '3x4m',
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar'],
                'penghuni' => [
                    'nama' => 'Ahmad Rizki Pratama',
                    'telepon' => '081234567890',
                    'email' => 'ahmad.rizki@email.com',
                    'pekerjaan' => 'Software Developer',
                    'tanggal_masuk' => '2024-01-15',
                    'tanggal_berakhir' => '2024-07-15',
                    'status_pembayaran' => 'Lunas',
                    'alamat_asal' => 'Jakarta Selatan',
                    'umur' => 25,
                    'jenis_kelamin' => 'Laki-laki',
                ],
            ],
            [
                'id' => 3,
                'nama' => 'K103',
                'lantai' => 1,
                'tipe' => 'Standard',
                'status' => 'booked',
                'harga' => 800000,
                'ukuran' => '3x4m',
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar'],
                'penghuni' => [
                    'nama' => 'Siti Nurhaliza',
                    'telepon' => '081234567891',
                    'email' => 'siti.nurhaliza@email.com',
                    'pekerjaan' => 'Marketing Executive',
                    'tanggal_masuk' => '2024-12-20',
                    'tanggal_berakhir' => '2025-12-20',
                    'status_pembayaran' => 'DP 50%',
                    'alamat_asal' => 'Bandung',
                    'umur' => 23,
                    'jenis_kelamin' => 'Perempuan',
                ],
            ],
            [
                'id' => 4,
                'nama' => 'K104',
                'lantai' => 1,
                'tipe' => 'Premium',
                'status' => 'terisi',
                'harga' => 1200000,
                'ukuran' => '4x5m',
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Kerja', 'Kulkas Mini', 'TV'],
                'penghuni' => [
                    'nama' => 'Budi Santoso',
                    'telepon' => '081234567892',
                    'email' => 'budi.santoso@email.com',
                    'pekerjaan' => 'Data Analyst',
                    'tanggal_masuk' => '2024-02-01',
                    'tanggal_berakhir' => '2025-02-01',
                    'status_pembayaran' => 'Lunas',
                    'alamat_asal' => 'Surabaya',
                    'umur' => 27,
                    'jenis_kelamin' => 'Laki-laki',
                ],
            ],
            [
                'id' => 5,
                'nama' => 'K105',
                'lantai' => 1,
                'tipe' => 'Standard',
                'status' => 'kosong',
                'harga' => 800000,
                'ukuran' => '3x4m',
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar'],
                'penghuni' => null,
            ],
            [
                'id' => 6,
                'nama' => 'K201',
                'lantai' => 2,
                'tipe' => 'Standard',
                'status' => 'kosong',
                'harga' => 850000,
                'ukuran' => '3x4m',
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar'],
                'penghuni' => null,
            ],
            [
                'id' => 7,
                'nama' => 'K202',
                'lantai' => 2,
                'tipe' => 'Standard',
                'status' => 'terisi',
                'harga' => 850000,
                'ukuran' => '3x4m',
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar'],
                'penghuni' => [
                    'nama' => 'Dewi Sartika Putri',
                    'telepon' => '081234567893',
                    'email' => 'dewi.sartika@email.com',
                    'pekerjaan' => 'Graphic Designer',
                    'tanggal_masuk' => '2024-03-10',
                    'tanggal_berakhir' => '2024-09-10',
                    'status_pembayaran' => 'Lunas',
                    'alamat_asal' => 'Yogyakarta',
                    'umur' => 24,
                    'jenis_kelamin' => 'Perempuan',
                ],
            ],
            [
                'id' => 8,
                'nama' => 'K203',
                'lantai' => 2,
                'tipe' => 'Premium',
                'status' => 'kosong',
                'harga' => 1300000,
                'ukuran' => '4x5m',
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Kerja', 'Kulkas Mini', 'TV'],
                'penghuni' => null,
            ],
            [
                'id' => 9,
                'nama' => 'K204',
                'lantai' => 2,
                'tipe' => 'Premium',
                'status' => 'terisi',
                'harga' => 1300000,
                'ukuran' => '4x5m',
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Kerja', 'Kulkas Mini', 'TV'],
                'penghuni' => [
                    'nama' => 'Eko Prasetyo Wijaya',
                    'telepon' => '081234567894',
                    'email' => 'eko.prasetyo@email.com',
                    'pekerjaan' => 'Financial Analyst',
                    'tanggal_masuk' => '2024-01-20',
                    'tanggal_berakhir' => '2025-01-20',
                    'status_pembayaran' => 'Lunas',
                    'alamat_asal' => 'Semarang',
                    'umur' => 26,
                    'jenis_kelamin' => 'Laki-laki',
                ],
            ],
            [
                'id' => 10,
                'nama' => 'K205',
                'lantai' => 2,
                'tipe' => 'Standard',
                'status' => 'maintenance',
                'harga' => 850000,
                'ukuran' => '3x4m',
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar'],
                'penghuni' => null,
            ],
        ];

        return collect($rooms);
    }

    // Method untuk checkout penghuni
    public function checkoutPenghuni($kamarId)
    {
        Notification::make()
            ->title('Checkout Berhasil')
            ->body("Penghuni kamar telah berhasil di-checkout.")
            ->success()
            ->send();

        // Update status kamar di collection
        $this->rooms = $this->rooms->map(function ($room) use ($kamarId) {
            if ($room['id'] == $kamarId) {
                $room['status'] = 'kosong';
                $room['penghuni'] = null;
            }
            return $room;
        });
    }

    // Method untuk checkin penghuni baru
    public function checkinPenghuni($kamarId)
    {
        Notification::make()
            ->title('Checkin Berhasil')
            ->body("Penghuni baru telah berhasil di-checkin ke kamar.")
            ->success()
            ->send();

        // Update status kamar di collection
        $this->rooms = $this->rooms->map(function ($room) use ($kamarId) {
            if ($room['id'] == $kamarId) {
                $room['status'] = 'terisi';
                // Dalam implementasi nyata, ini akan membuka form untuk input data penghuni
            }
            return $room;
        });
    }

    // Method untuk konfirmasi booking
    public function konfirmasiBooking($kamarId)
    {
        Notification::make()
            ->title('Booking Dikonfirmasi')
            ->body("Booking kamar telah dikonfirmasi.")
            ->success()
            ->send();

        // Update status kamar di collection
        $this->rooms = $this->rooms->map(function ($room) use ($kamarId) {
            if ($room['id'] == $kamarId) {
                $room['status'] = 'terisi';
            }
            return $room;
        });
    }

    // Method untuk view detail kamar
    public function viewDetail($kamarId)
    {
        $room = $this->rooms->firstWhere('id', $kamarId);

        if (!$room) {
            Notification::make()
                ->title('Error')
                ->body('Kamar tidak ditemukan.')
                ->danger()
                ->send();
            return;
        }

        // Dalam implementasi nyata, ini akan membuka modal atau redirect ke halaman detail
        Notification::make()
            ->title('Detail Kamar')
            ->body("Menampilkan detail kamar {$room['nama']}")
            ->info()
            ->send();
    }

    // Method untuk mendapatkan statistik kamar
    public function getStatistik(): array
    {
        $allRooms = $this->rooms;

        return [
            'total' => $allRooms->count(),
            'kosong' => $allRooms->where('status', 'kosong')->count(),
            'terisi' => $allRooms->where('status', 'terisi')->count(),
            'booked' => $allRooms->where('status', 'booked')->count(),
            'maintenance' => $allRooms->where('status', 'maintenance')->count(),
            'tingkat_hunian' => $allRooms->count() > 0 ?
                round(($allRooms->where('status', 'terisi')->count() / $allRooms->count()) * 100, 1) : 0,
            'revenue_aktual' => $allRooms->where('status', 'terisi')->sum('harga'),
            'revenue_potensial' => $allRooms->sum('harga'),
        ];
    }

    // Helper method untuk format status
    public function getStatusLabel($status): string
    {
        return match ($status) {
            'kosong' => 'Tersedia',
            'terisi' => 'Terisi',
            'booked' => 'Dipesan',
            'maintenance' => 'Maintenance',
            default => $status,
        };
    }

    // Helper method untuk status color
    public function getStatusColor($status): string
    {
        return match ($status) {
            'kosong' => 'success',
            'terisi' => 'danger',
            'booked' => 'warning',
            'maintenance' => 'gray',
            default => 'gray',
        };
    }
}
