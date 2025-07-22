<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggunaanVoucherResource\Pages;
use App\Models\PenghuniVoucher;
use App\Models\Mitra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PenggunaanVoucherResource extends Resource
{
    protected static ?string $model = PenghuniVoucher::class;
    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationLabel = 'Voucher Saya';
    protected static ?string $modelLabel = 'Voucher';
    protected static ?string $pluralModelLabel = 'Voucher Saya';
    protected static ?string $navigationGroup = 'Voucher';

    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole(['Penghuni']);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $penghuni = $user->penghuni;

        return parent::getEloquentQuery()
            ->where('penghuni_id', $penghuni?->id)
            ->with(['voucher.mitra', 'mitraDigunakan']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('voucher.nama')
                    ->label('Nama Voucher')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('voucher.kode_voucher')
                    ->label('Kode Voucher')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kode voucher disalin!')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('voucher.mitra.nama')
                    ->label('Mitra')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('voucher.deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

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

                Tables\Columns\TextColumn::make('mitraDigunakan.nama')
                    ->label('Digunakan di')
                    ->placeholder('Belum digunakan')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diterima')
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
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('gunakan')
                    ->label('Gunakan')
                    ->icon('heroicon-o-gift')
                    ->color('success')
                    ->visible(fn(PenghuniVoucher $record): bool => !$record->is_used)
                    ->form([
                        Forms\Components\Select::make('mitra_id')
                            ->label('Pilih Mitra')
                            ->options(function (PenghuniVoucher $record) {
                                return Mitra::where('id', $record->voucher->mitra_id)
                                    ->pluck('nama', 'id');
                            })
                            ->required()
                            ->helperText('Voucher hanya bisa digunakan di mitra yang terkait'),
                    ])
                    ->action(function (PenghuniVoucher $record, array $data): void {
                        $record->gunakan($data['mitra_id']);

                        \Filament\Notifications\Notification::make()
                            ->title('Voucher Berhasil Digunakan!')
                            ->body("Voucher '{$record->voucher->nama}' telah digunakan.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn(PenghuniVoucher $record): string => "Detail Voucher: {$record->voucher->nama}")
                    ->modalContent(function (PenghuniVoucher $record): \Illuminate\Contracts\View\View {
                        return view('filament.resources.penggunaan-voucher-resource.modals.voucher-detail', [
                            'record' => $record
                        ]);
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum Ada Voucher')
            ->emptyStateDescription('Anda belum memiliki voucher. Voucher akan muncul di sini ketika admin memberikannya kepada Anda.')
            ->emptyStateIcon('heroicon-o-gift');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenggunaanVouchers::route('/'),
        ];
    }
}
