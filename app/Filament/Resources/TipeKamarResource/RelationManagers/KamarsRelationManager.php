<?php

namespace App\Filament\Resources\TipeKamarResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KamarsRelationManager extends RelationManager
{
    protected static string $relationship = 'kamars';
    protected static ?string $recordTitleAttribute = 'nama';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lantai')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('ukuran')
                    ->label('Ukuran')
                    ->options([
                        '3x3' => '3x3',
                        '4x4' => '4x4',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required()
                    ->reactive(),
                Forms\Components\TextInput::make('ukuran_custom')
                    ->label('Ukuran Custom')
                    ->visible(fn($get) => $get('ukuran') === 'lainnya')
                    ->required(fn($get) => $get('ukuran') === 'lainnya')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('lantai')->label('Lantai'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Kamar'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
