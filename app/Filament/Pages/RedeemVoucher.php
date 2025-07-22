<?php

namespace App\Filament\Pages;

use App\Models\PenghuniVoucher;
use App\Models\Mitra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class RedeemVoucher extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static string $view = 'filament.pages.redeem-voucher';
    protected static ?string $navigationLabel = 'Redeem Voucher';
    protected static ?string $title = 'Redeem Voucher';
    protected static ?string $navigationGroup = 'Voucher';

    public ?array $data = [];
    public ?PenghuniVoucher $voucherFound = null;
    public bool $showVoucherDetail = false;

    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('Mitra');
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Cari Voucher')
                    ->description('Masukkan kode voucher yang ingin di-redeem')
                    ->schema([
                        Forms\Components\TextInput::make('kode_voucher')
                            ->label('Kode Voucher')
                            ->placeholder('Masukkan kode voucher...')
                            ->required()
                            ->maxLength(255)
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('cari')
                                    ->icon('heroicon-m-magnifying-glass')
                                    ->action('cariVoucher')
                            ),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function cariVoucher(): void
    {
        $this->validate([
            'data.kode_voucher' => 'required|string',
        ]);

        $kodeVoucher = $this->data['kode_voucher'];
        $user = Auth::user();
        $mitra = $user->mitra;

        if (!$mitra) {
            Notification::make()
                ->title('Error')
                ->body('Akun Anda tidak terhubung dengan mitra manapun.')
                ->danger()
                ->send();
            return;
        }

        // Cari voucher berdasarkan kode
        $voucher = PenghuniVoucher::whereHas('voucher', function ($query) use ($kodeVoucher, $mitra) {
            $query->where('kode_voucher', $kodeVoucher)
                ->where('mitra_id', $mitra->id);
        })
            ->with(['voucher.mitra', 'penghuni'])
            ->first();

        if (!$voucher) {
            Notification::make()
                ->title('Voucher Tidak Ditemukan')
                ->body('Kode voucher tidak ditemukan atau bukan untuk mitra Anda.')
                ->warning()
                ->send();

            $this->voucherFound = null;
            $this->showVoucherDetail = false;
            return;
        }

        if ($voucher->is_used) {
            Notification::make()
                ->title('Voucher Sudah Digunakan')
                ->body('Voucher ini sudah pernah digunakan pada ' . $voucher->tanggal_digunakan?->format('d M Y H:i'))
                ->warning()
                ->send();

            $this->voucherFound = $voucher;
            $this->showVoucherDetail = true;
            return;
        }

        $this->voucherFound = $voucher;
        $this->showVoucherDetail = true;

        Notification::make()
            ->title('Voucher Ditemukan!')
            ->body('Voucher valid dan siap untuk di-redeem.')
            ->success()
            ->send();
    }

    public function redeemVoucher(): void
    {
        if (!$this->voucherFound || $this->voucherFound->is_used) {
            Notification::make()
                ->title('Error')
                ->body('Voucher tidak valid atau sudah digunakan.')
                ->danger()
                ->send();
            return;
        }

        $user = Auth::user();
        $mitra = $user->mitra;

        try {
            $this->voucherFound->gunakan($mitra->id);

            Notification::make()
                ->title('Voucher Berhasil Di-redeem!')
                ->body("Voucher '{$this->voucherFound->voucher->nama}' telah berhasil di-redeem untuk penghuni {$this->voucherFound->penghuni->nama}")
                ->success()
                ->send();

            // Reset form
            $this->data = [];
            $this->voucherFound = null;
            $this->showVoucherDetail = false;
            $this->form->fill();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Terjadi kesalahan saat redeem voucher: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Reset Form')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    $this->data = [];
                    $this->voucherFound = null;
                    $this->showVoucherDetail = false;
                    $this->form->fill();
                }),
        ];
    }
}
