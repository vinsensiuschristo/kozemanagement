<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengeluaranResource\Pages;
use App\Filament\Resources\PengeluaranResource\RelationManagers;
use App\Models\Pengeluaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengeluaranResource extends Resource
{
    protected static ?string $model = Pengeluaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
    protected static ?string $navigationLabel = 'Pengeluaran';
    protected static ?string $pluralModelLabel = 'Data Pengeluaran';
    protected static ?string $navigationGroup = 'Keuangan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('unit_id')
                ->relationship('unit', 'nama')
                ->searchable()
                ->required()
                ->label('Unit'),

            Forms\Components\DatePicker::make('tanggal')
                ->required()
                ->label('Tanggal Pengeluaran'),

            Forms\Components\TextInput::make('jumlah')
                ->numeric()
                ->required()
                ->prefix('Rp')
                ->label('Jumlah'),

            Forms\Components\TextInput::make('kategori')
                ->required()
                ->label('Kategori (Listrik, Air, dll)'),

            Textarea::make('deskripsi')
                ->nullable()
                ->label('Deskripsi'),

            FileUpload::make('bukti')
                ->directory('bukti/pengeluaran')
                ->label('Bukti Pengeluaran (opsional)')
                ->nullable(),

            Toggle::make('is_konfirmasi')
                ->label('Telah Dikonfirmasi')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('tanggal')->date()->label('Tanggal'),
            Tables\Columns\TextColumn::make('unit.nama')->label('Unit'),
            Tables\Columns\TextColumn::make('kategori')->label('Kategori'),
            Tables\Columns\TextColumn::make('jumlah')->money('IDR')->label('Jumlah'),
            Tables\Columns\IconColumn::make('is_konfirmasi')
                ->boolean()
                ->label('Konfirmasi'),
        ])->filters([
            Tables\Filters\TernaryFilter::make('is_konfirmasi')
                ->label('Status Konfirmasi')
                ->trueLabel('Terkonfirmasi')
                ->falseLabel('Belum')
                ->placeholder('Semua'),
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
            'index' => Pages\ListPengeluarans::route('/'),
            'create' => Pages\CreatePengeluaran::route('/create'),
            'edit' => Pages\EditPengeluaran::route('/{record}/edit'),
        ];
    }
}
