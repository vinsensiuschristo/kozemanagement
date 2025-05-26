<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestingOwnerResource\Pages;
use App\Filament\Resources\TestingOwnerResource\RelationManagers;
use App\Models\TestingOwner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestingOwnerResource extends Resource
{
    protected static ?string $model = TestingOwner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->hasRole('Owner')) {
            return true;
        } else {
            return false;
        }
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
                //
            ])
            ->filters([
                //
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
            'index' => Pages\ListTestingOwners::route('/'),
            'create' => Pages\CreateTestingOwner::route('/create'),
            'edit' => Pages\EditTestingOwner::route('/{record}/edit'),
        ];
    }
}
