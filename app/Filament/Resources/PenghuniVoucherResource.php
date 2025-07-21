<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenghuniVoucherResource\Pages;
use App\Filament\Resources\PenghuniVoucherResource\RelationManagers;
use App\Models\PenghuniVoucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class PenghuniVoucherResource extends Resource
{
    protected static ?string $model = PenghuniVoucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Vouchers';
    protected static ?string $navigationLabel = 'Riwayat Voucher';

    public static function canCreate(): bool { return false; }
    public static function canEdit(Model $record): bool { return false; }
    public static function canDelete(Model $record): bool { return false; }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('User');
    }


    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where('penghuni_id', $user->penghuni?->id ?? null);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('voucher.nama')
                    ->label('Nama Voucher')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('voucher.kode_voucher')
                    ->label('Kode Voucher')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('periode')
                ->label('Periode')
                ->date('F Y')
            ])
            ->filters([
                SelectFilter::make('is_used')
                    ->label('Status Penggunaan')
                    ->options([
                        '1' => 'Digunakan',
                        '0' => 'Belum Digunakan',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenghuniVouchers::route('/'),
            'create' => Pages\CreatePenghuniVoucher::route('/create'),
            'edit' => Pages\EditPenghuniVoucher::route('/{record}/edit'),
        ];
    }
}
