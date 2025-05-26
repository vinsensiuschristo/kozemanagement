<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OwnerResource\Pages;
use App\Filament\Resources\OwnerResource\RelationManagers;
use App\Models\Owner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TextFilter;

class OwnerResource extends Resource
{
    protected static ?string $model = Owner::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'SuperAdmin';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->unique(ignorable: fn(?Owner $record) => $record)
                    ->maxLength(50),

                Forms\Components\TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->placeholder('Contoh: +6281234567890')
                    ->helperText('Gunakan angka saja, boleh diawali + untuk format internasional')
                    ->nullable()
                    ->unique(ignorable: fn(?Owner $record) => $record)
                    ->maxLength(15)
                    ->rules(['regex:/^\+?\d{9,15}$/']),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->nullable()
                    ->email()
                    ->unique(ignorable: fn(?Owner $record) => $record)
                    ->maxLength(50),

                Forms\Components\TextInput::make('alamat')
                    ->label('Alamat')
                    ->nullable()
                    ->maxLength(255),

                Forms\Components\TextInput::make('nomor_ktp')
                    ->label('Nomor KTP')
                    ->placeholder('Contoh: 327501xxxxxxxxxx')
                    ->helperText('Masukkan 16 digit angka sesuai KTP')
                    ->nullable()
                    ->unique(ignorable: fn(?Owner $record) => $record)
                    ->maxLength(16)
                    ->rules(['regex:/^\d{16}$/']),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListOwners::route('/'),
            'create' => Pages\CreateOwner::route('/create'),
            'edit' => Pages\EditOwner::route('/{record}/edit'),
        ];
    }
}
