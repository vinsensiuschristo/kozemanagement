<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestingSuperAdminResource\Pages;
use App\Filament\Resources\TestingSuperAdminResource\RelationManagers;
use App\Models\TestingSuperAdmin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestingSuperAdminResource extends Resource
{
    protected static ?string $model = TestingSuperAdmin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->hasRole('Superadmin')) {
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
            'index' => Pages\ListTestingSuperAdmins::route('/'),
            'create' => Pages\CreateTestingSuperAdmin::route('/create'),
            'edit' => Pages\EditTestingSuperAdmin::route('/{record}/edit'),
        ];
    }
}
