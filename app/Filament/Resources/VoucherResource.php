<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Filament\Resources\VoucherResource\RelationManagers;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    public static function canViewAny():bool
    {
        return Auth::user()->hasRole(['Superadmin', 'Admin']);
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasRole('Superadmin');
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->hasRole('Superadmin');
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->hasRole('Superadmin');
    }

    public static function canView(Model $record): bool
    {
        return Auth::user()->hasRole(['Superadmin', 'Admin']);
    }

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Vouchers';
    protected static ?string $modelLabel = 'Voucher';
    protected static ?string $pluralModelLabel = 'Vouchers';
    protected static ?string $navigationGroup = 'Manajemen Voucher';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Voucher')
                    ->required()
                    ->maxLength(255),
                TextInput::make('deskripsi')
                    ->label('Deskripsi')
                    ->columnSpanFull()
                    ->maxLength(500),
                TextInput::make('kode_voucher')
                    ->label('Kode Voucher')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true),
                Select::make('mitra_id')
                    ->label('Mitra')
                    ->relationship('mitra', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),

                Repeater::make('unitVoucherRules')
                ->label('Kuota Voucher per Unit')
                ->relationship('unitVoucherRules')
                ->schema([
                    Select::make('unit_id')
                        ->label('Unit')
                        ->relationship('unit', 'nama_cluster')
                        ->required()
                        ->searchable()
                        ->preload(),
                    TextInput::make('kuota_per_bulan')
                        ->label('Kuota')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->default(1),
                ])
                ->columns(2)
                ->collapsible()
                ->createItemButtonLabel('Tambah Kuota untuk Unit')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Voucher')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kode_voucher')
                    ->label('Kode Voucher')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mitra.nama')
                    ->label('Mitra')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn () => Auth::user()->hasRole('Superadmin')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()->hasRole('Superadmin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()->hasRole('Superadmin')),
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
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}
