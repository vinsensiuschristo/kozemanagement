<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole(['Superadmin', 'Admin']);
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasRole(['Superadmin', 'Admin']);
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->hasRole(['Superadmin', 'Admin']);
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->hasRole(['Superadmin', 'Admin']);
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
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Voucher')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->columnSpanFull()
                    ->maxLength(500),

                Forms\Components\TextInput::make('kode_voucher')
                    ->label('Kode Voucher')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('mitra_id')
                    ->label('Mitra')
                    ->relationship('mitra', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Voucher')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kode_voucher')
                    ->label('Kode Voucher')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mitra.nama')
                    ->label('Mitra')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('penghuniVouchers_count')
                    ->label('Jumlah Pengguna')
                    ->counts('penghuniVouchers')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('mitra_id')
                    ->label('Mitra')
                    ->relationship('mitra', 'nama')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn() => Auth::user()->hasRole(['Superadmin', 'Admin'])),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => Auth::user()->hasRole(['Superadmin', 'Admin'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => Auth::user()->hasRole(['Superadmin', 'Admin'])),
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
            'assign' => Pages\AssignVoucherGroup::route('/assign'),
        ];
    }
}
