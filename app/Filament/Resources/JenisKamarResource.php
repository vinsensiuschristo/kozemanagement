<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisKamarResource\Pages;
use App\Filament\Resources\JenisKamarResource\RelationManagers;
use App\Models\JenisKamar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\DetailFotoKamar;
use Filament\Tables\Actions\LinkAction;

class JenisKamarResource extends Resource
{
    protected static ?string $model = JenisKamar::class;

    // protected static string $view = 'filament.resources.jeniskamarresource.pages.viewjeniskamar';

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationGroup = 'Master Data';

    protected ?array $tempFotos = null;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('harga')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('deskripsi')
                    ->maxLength(500),

                Forms\Components\Select::make('fasilitasKamar')
                    ->relationship('fasilitasKamar', 'nama')
                    ->multiple()
                    ->preload()
                    ->label('Fasilitas')
                    ->searchable(),

                Forms\Components\FileUpload::make('foto_kamars')
                    ->label('Foto Jenis Kamar')
                    ->multiple()
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(5120) // 5MB
                    ->directory('jenis_kamar/fotos')
                    ->preserveFilenames()
                    ->reorderable()
                    ->helperText('Upload beberapa foto untuk jenis kamar ini'),
            ]);
    }

    // tambahan
    public function mutateFormDataBeforeCreate(array $data): array
    {
        $this->tempFotos = $data['foto_kamars'] ?? null;
        unset($data['foto_kamars']);
        return $data;
    }

    public function afterCreate($record): void
    {
        if ($this->tempFotos) {
            foreach ($this->tempFotos ?? [] as $path) {
                DetailFotoKamar::create([
                    'jenis_kamar_id' => $record->id,
                    'path' => $path,
                ]);
            }
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('harga')->money('IDR', true)->sortable(),
                Tables\Columns\TextColumn::make('fasilitasKamar.nama')
                    ->label('Fasilitas')
                    ->badge()
                    ->limit(2)
                    ->separator(', '),
            ])
            ->filters([
                //
            ])
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
            'index' => Pages\ListJenisKamars::route('/'),
            'create' => Pages\CreateJenisKamar::route('/create'),
            'edit' => Pages\EditJenisKamar::route('/{record}/edit'),
            'view' => Pages\ViewJenisKamar::route('/{record}'), // ← ini penting
        ];
    }
}
