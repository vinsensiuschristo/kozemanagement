<?php

namespace App\Filament\Pages;

use App\Models\PenghuniVoucher;
use App\Models\Mitra;
use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class RedeemVoucher extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static string $view = 'filament.pages.redeem-voucher';
    protected static ?string $title = 'Redeem Voucher';
    protected static ?string $navigationLabel = 'Redeem Voucher';
    protected static ?int $navigationSort = 1;

    public ?array $data = [];
    public ?PenghuniVoucher $foundVoucher = null;
    public bool $showVoucherDetail = false;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Mitra');
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Scan atau Input Kode Voucher')
                    ->description('Masukkan kode voucher yang ingin diredeem')
                    ->schema([
                        TextInput::make('kode_voucher')
                            ->label('Kode Voucher')
                            ->placeholder('Masukkan kode voucher...')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state) {
                                if ($state) {
                                    $this->searchVoucher($state);
                                }
                            }),
                    ]),
            ])
            ->statePath('data');
    }

    public function searchVoucher(string $kodeVoucher): void
    {
        $this->foundVoucher = null;
        $this->showVoucherDetail = false;

        if (empty($kodeVoucher)) {
            return;
        }

        // Cari voucher berdasarkan kode
        $voucher = PenghuniVoucher::with(['voucher', 'penghuni', 'digunakan_pada_mitra'])
            ->whereHas('voucher', function ($query) use ($kodeVoucher) {
                $query->where('kode_voucher', $kodeVoucher);
            })
            ->first();

        if (!$voucher) {
            Notification::make()
                ->title('Voucher Tidak Ditemukan')
                ->body('Kode voucher yang Anda masukkan tidak valid.')
                ->danger()
                ->send();
            return;
        }

        // Cek apakah voucher sudah digunakan
        if ($voucher->is_used) {
            Notification::make()
                ->title('Voucher Sudah Digunakan')
                ->body('Voucher ini telah digunakan pada ' . $voucher->tanggal_digunakan?->format('d M Y H:i'))
                ->warning()
                ->send();
            $this->foundVoucher = $voucher;
            $this->showVoucherDetail = true;
            return;
        }

        // Cek apakah voucher untuk mitra ini
        $userMitra = Auth::user()->mitra;
        if (!$userMitra || $voucher->voucher->mitra_id !== $userMitra->id) {
            Notification::make()
                ->title('Voucher Bukan Untuk Mitra Ini')
                ->body('Voucher ini tidak dapat digunakan di mitra Anda.')
                ->danger()
                ->send();
            return;
        }

        $this->foundVoucher = $voucher;
        $this->showVoucherDetail = true;

        Notification::make()
            ->title('Voucher Valid!')
            ->body('Voucher ditemukan dan siap untuk diredeem.')
            ->success()
            ->send();
    }

    public function redeemVoucher(): void
    {
        if (!$this->foundVoucher || $this->foundVoucher->is_used) {
            Notification::make()
                ->title('Error')
                ->body('Voucher tidak valid atau sudah digunakan.')
                ->danger()
                ->send();
            return;
        }

        $userMitra = Auth::user()->mitra;

        try {
            $this->foundVoucher->gunakan($userMitra->id);

            Notification::make()
                ->title('Voucher Berhasil Diredeem!')
                ->body('Voucher telah berhasil digunakan.')
                ->success()
                ->send();

            // Reset form
            $this->form->fill();
            $this->foundVoucher = null;
            $this->showVoucherDetail = false;

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function resetForm(): void
    {
        $this->form->fill();
        $this->foundVoucher = null;
        $this->showVoucherDetail = false;
    }
}
