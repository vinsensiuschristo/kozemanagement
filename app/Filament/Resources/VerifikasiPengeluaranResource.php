<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VerifikasiPengeluaranResource\Pages;
use App\Filament\Resources\VerifikasiPengeluaranResource\RelationManagers;
use App\Models\Pengeluaran;
use App\Models\VerifikasiPengeluaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;

class VerifikasiPengeluaranResource extends Resource
{
    protected static ?string $model = Pengeluaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Verifikasi Pengeluaran';
    protected static ?string $navigationGroup = 'Verifikasi';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->hasRole('Superadmin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(static::getEloquentQuery())
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date()->label('Tanggal'),
                Tables\Columns\TextColumn::make('unit.nama_cluster')->label('Unit'),
                Tables\Columns\TextColumn::make('kategori')->label('Kategori'),
                Tables\Columns\TextColumn::make('jumlah')->money('IDR'),
                Tables\Columns\TextColumn::make('deskripsi')->label('Deskripsi')->limit(30),
                Tables\Columns\ImageColumn::make('bukti')->label('Bukti')->height(100)->circular(),
            ])
            ->filters([
                SelectFilter::make('unit_id')
                    ->relationship('unit', 'nama_cluster')
                    ->label('Filter Unit'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('konfirmasi')
                    ->label('Konfirmasi')
                    ->action(function (Pengeluaran $record) {
                        $record->is_konfirmasi = true;
                        $record->save();
                    })
                    ->requiresConfirmation()
                    ->icon('heroicon-o-check')
                    ->color('success'),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListVerifikasiPengeluarans::route('/'),
            'create' => Pages\CreateVerifikasiPengeluaran::route('/create'),
            'edit' => Pages\EditVerifikasiPengeluaran::route('/{record}/edit'),
        ];
    }
}
