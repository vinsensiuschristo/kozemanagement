<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VerifikasiPemasukanResource\Pages;
use App\Filament\Resources\VerifikasiPemasukanResource\RelationManagers;
use App\Models\Pemasukan;
use App\Models\VerifikasiPemasukan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class VerifikasiPemasukanResource extends Resource
{
    protected static ?string $model = Pemasukan::class;

    protected static ?string $navigationGroup = 'Verifikasi';
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Verifikasi Pemasukan';

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

    public static function table(Table $table): Table
    {
        return $table
            ->query(Pemasukan::query()->where('is_konfirmasi', false))
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date()->label('Tanggal'),
                Tables\Columns\TextColumn::make('unit.nama_cluster')->label('Unit'),
                Tables\Columns\TextColumn::make('penghuni.nama')->label('Penghuni')->default('Pemasukan bukan checkin'),
                Tables\Columns\TextColumn::make('jumlah')->money('IDR'),
                Tables\Columns\TextColumn::make('deskripsi')->wrap()->label('Deskripsi'),
                Tables\Columns\ImageColumn::make('bukti')->label('Bukti')->disk('public')->height(50),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('konfirmasi')
                    ->label('Konfirmasi')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn(Pemasukan $record) => $record->update(['is_konfirmasi' => true])),
            ])
            ->filters([
                Tables\Filters\Filter::make('unit_id')
                    ->label('Unit')
                    ->form([
                        Forms\Components\Select::make('unit_id')
                            ->relationship('unit', 'nama_cluster')
                            ->searchable()
                            ->label('Filter Unit'),
                    ])
                    ->query(fn($query, $data) => $query->when(
                        $data['unit_id'],
                        fn($query, $unitId) => $query->where('unit_id', $unitId)
                    )),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('tanggal')
                    ->label('Tanggal')
                    ->date(),

                TextEntry::make('unit.nama_cluster')
                    ->label('Unit'),

                TextEntry::make('penghuni.nama')
                    ->label('Penghuni')
                    ->default('Pemasukan bukan checkin'),

                TextEntry::make('jumlah')
                    ->label('Jumlah')
                    ->money('IDR'),

                TextEntry::make('deskripsi')
                    ->label('Deskripsi')
                    ->columnSpanFull(),

                ImageEntry::make('bukti')
                    ->label('Bukti Pembayaran')
                    ->disk('public')
                    ->height(200)
                    ->hidden(fn($record) => blank($record->bukti)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVerifikasiPemasukans::route('/'),
            'create' => Pages\CreateVerifikasiPemasukan::route('/create'),
            'edit' => Pages\EditVerifikasiPemasukan::route('/{record}/edit'),
        ];
    }
}
