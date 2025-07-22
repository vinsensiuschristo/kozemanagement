<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ValidasiVoucherResource\Pages;
use App\Models\PenghuniVoucher;
use App\Models\Mitra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ValidasiVoucherResource extends Resource
{
    protected static ?string $model = PenghuniVoucher::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Validasi Voucher';
    protected static ?string $modelLabel = 'Voucher';
    protected static ?string $pluralModelLabel = 'Validasi Voucher';
    protected static ?string $navigationGroup = 'Voucher';

    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole(['Mitra', 'Superadmin', 'Admin']);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        // Jika user adalah mitra, hanya tampilkan voucher untuk mitra tersebut
        if ($user->hasRole('Mitra')) {
            $mitra = Mitra::where('user_id', $user->id)->first();
            return parent::getEloquentQuery()
                ->whereHas('voucher', function (Builder $query) use ($mitra) {
                    $query->where('mitra_id', $mitra?->id);
                })
                ->with(['voucher.mitra', 'penghuni', 'mitraDigunakan']);
        }

        // Untuk admin dan superadmin, tampilkan semua
        return parent::getEloquentQuery()
            ->with(['voucher.mitra', 'penghuni', 'mitraDigunakan']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('voucher.kode_voucher')
                    ->label('Kode Voucher')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kode voucher disalin!')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('voucher.nama')
                    ->label('Nama Voucher')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('penghuni.nama')
                    ->label('Nama Penghuni')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('voucher.mitra.nama')
                    ->label('Mitra')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_used')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->tooltip(fn($state): string => $state ? 'Sudah Digunakan' : 'Belum Digunakan'),

                Tables\Columns\TextColumn::make('tanggal_digunakan')
                    ->label('Tanggal Digunakan')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Belum digunakan'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diberikan')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_used')
                    ->label('Status Penggunaan')
                    ->options([
                        '0' => 'Belum Digunakan',
                        '1' => 'Sudah Digunakan',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('voucher.mitra_id')
                    ->label('Mitra')
                    ->relationship('voucher.mitra', 'nama')
                    ->searchable()
                    ->preload()
                    ->visible(fn() => Auth::user()->hasRole(['Superadmin', 'Admin'])),
            ])
            ->actions([
                Tables\Actions\Action::make('validasi')
                    ->label('Validasi & Gunakan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(PenghuniVoucher $record): bool => !$record->is_used)
                    ->requiresConfirmation()
                    ->modalHeading('Validasi Voucher')
                    ->modalDescription(
                        fn(PenghuniVoucher $record): string =>
                        "Apakah Anda yakin ingin memvalidasi dan menggunakan voucher '{$record->voucher->nama}' untuk penghuni '{$record->penghuni->nama}'?"
                    )
                    ->modalSubmitActionLabel('Ya, Validasi')
                    ->action(function (PenghuniVoucher $record): void {
                        $user = Auth::user();
                        $mitraId = null;

                        if ($user->hasRole('Mitra')) {
                            $mitra = Mitra::where('user_id', $user->id)->first();
                            $mitraId = $mitra?->id;
                        } else {
                            $mitraId = $record->voucher->mitra_id;
                        }

                        $record->gunakan($mitraId);

                        \Filament\Notifications\Notification::make()
                            ->title('Voucher Berhasil Divalidasi!')
                            ->body("Voucher '{$record->voucher->nama}' telah divalidasi dan digunakan.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn(PenghuniVoucher $record): string => "Detail Voucher: {$record->voucher->nama}")
                    ->modalContent(function (PenghuniVoucher $record): \Illuminate\Contracts\View\View {
                        return view('filament.resources.validasi-voucher-resource.modals.voucher-detail', [
                            'record' => $record
                        ]);
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum Ada Voucher')
            ->emptyStateDescription('Belum ada voucher yang perlu divalidasi.')
            ->emptyStateIcon('heroicon-o-shield-check');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListValidasiVouchers::route('/'),
        ];
    }
}
