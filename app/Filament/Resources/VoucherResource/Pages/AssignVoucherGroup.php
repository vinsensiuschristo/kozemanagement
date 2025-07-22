<?php

namespace App\Filament\Resources\VoucherResource\Pages;

use App\Filament\Resources\VoucherResource;
use App\Models\Voucher;
use App\Models\Penghuni;
use App\Models\PenghuniVoucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class AssignVoucherGroup extends Page
{
    protected static string $resource = VoucherResource::class;
    protected static string $view = 'filament.resources.voucher-resource.pages.assign-voucher-group';
    protected static ?string $title = 'Assign Voucher ke Penghuni';
    protected static ?string $navigationLabel = 'Assign Voucher';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Assign Voucher ke Penghuni')
                    ->description('Pilih voucher dan penghuni yang akan menerima voucher tersebut')
                    ->schema([
                        Forms\Components\Select::make('voucher_id')
                            ->label('Pilih Voucher')
                            ->options(function () {
                                return Voucher::with('mitra')
                                    ->whereHas('mitra') // Hanya voucher yang memiliki mitra
                                    ->get()
                                    ->mapWithKeys(function ($voucher) {
                                        $label = $voucher->nama;
                                        if ($voucher->mitra) {
                                            $label .= ' (' . $voucher->mitra->nama . ')';
                                        }
                                        return [$voucher->id => $label];
                                    });
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih voucher yang akan diberikan kepada penghuni'),

                        Forms\Components\Select::make('penghuni_ids')
                            ->label('Pilih Penghuni')
                            ->options(Penghuni::all()->pluck('nama', 'id'))
                            ->multiple()
                            ->required()
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih satu atau lebih penghuni yang akan menerima voucher'),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('assign')
                ->label('Assign Voucher')
                ->color('primary')
                ->icon('heroicon-o-gift')
                ->action('assignVoucher'),
        ];
    }

    public function assignVoucher(): void
    {
        $data = $this->form->getState();

        try {
            DB::beginTransaction();

            $voucher = Voucher::with('mitra')->findOrFail($data['voucher_id']);
            $penghuniIds = $data['penghuni_ids'];

            // Validasi voucher memiliki mitra
            if (!$voucher->mitra) {
                throw new \Exception('Voucher tidak memiliki mitra yang valid.');
            }

            $assignedCount = 0;
            $duplicateCount = 0;

            foreach ($penghuniIds as $penghuniId) {
                // Cek apakah penghuni sudah memiliki voucher ini
                $exists = PenghuniVoucher::where('penghuni_id', $penghuniId)
                    ->where('voucher_id', $data['voucher_id'])
                    ->where('is_used', false)
                    ->exists();

                if (!$exists) {
                    PenghuniVoucher::create([
                        'penghuni_id' => $penghuniId,
                        'voucher_id' => $data['voucher_id'],
                        'is_used' => false,
                    ]);
                    $assignedCount++;
                } else {
                    $duplicateCount++;
                }
            }

            DB::commit();

            $message = "Berhasil memberikan voucher '{$voucher->nama}' kepada {$assignedCount} penghuni.";
            if ($duplicateCount > 0) {
                $message .= " {$duplicateCount} penghuni sudah memiliki voucher ini sebelumnya.";
            }

            Notification::make()
                ->title('Voucher Berhasil Diberikan!')
                ->body($message)
                ->success()
                ->send();

            $this->form->fill();

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Gagal memberikan voucher!')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
