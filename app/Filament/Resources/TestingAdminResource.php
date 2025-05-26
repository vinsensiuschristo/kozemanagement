<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestingAdminResource\Pages;
use App\Filament\Resources\TestingAdminResource\RelationManagers;
use App\Models\TestingAdmin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestingAdminResource extends Resource
{
    protected static ?string $model = TestingAdmin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->hasRole('Admin')) {
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
            'index' => Pages\ListTestingAdmins::route('/'),
            'create' => Pages\CreateTestingAdmin::route('/create'),
            'edit' => Pages\EditTestingAdmin::route('/{record}/edit'),
        ];
    }
}
