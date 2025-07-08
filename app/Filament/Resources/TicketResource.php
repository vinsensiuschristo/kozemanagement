<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use App\Models\Kamar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Components\Tab;
class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['User','Admin']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('User');
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole('User') && $record->user_id === auth()->id();

    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (!auth()->user()?->hasRole('Admin')) {
            $query->where('user_id', auth()->id());
        }

        return $query->orderBy('created_at'); // paling lama muncul paling atas
    }

    public static function getNavigationBadge(): ?string
    {
        if (auth()->user()?->hasRole('Admin')) {
            return (string) Ticket::where('status', 'Baru')->count();
        }

        return null; // Hanya admin yang melihat badge
    }

    public function getDefaultActiveTab(): string
    {
        return 'baru';
    }



    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('judul')
                    ->label('Judul Laporan')
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Keluhan')
                    ->required()
                    ->autosize(),

                Select::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'Kebocoran' => 'Kebocoran',
                        'Kerusakan' => 'Kerusakan',
                        'Layanan'   => 'Layanan',
                        'Penghuni'  => 'Penghuni',
                        'Keamanan'  => 'Keamanan',
                        'Lainnya'   => 'Lainnya',
                    ]),
                    // ->required(),

                Select::make('prioritas')
                    ->label('Prioritas')
                    ->options([
                        'Rendah' => 'Rendah',
                        'Sedang' => 'Sedang',
                        'Tinggi' => 'Tinggi',
                    ])
                    ->default('Sedang')
                    ->required(),

                TextInput::make('unit_nama')
                ->label('Unit')
                ->disabled()
                ->reactive()
                ->afterStateHydrated(function ($state, callable $set, $get) {
                    $kamar = Kamar::find($get('kamar_id'));
                    if ($kamar) {
                        $set('unit_nama', $kamar->unit->nama_cluster ?? '-');
                    }
                }),
                Select::make('kamar_id')
                    ->label('Pilih Kamar')
                    ->options(function () {
                        $user = auth()->user();
                        $penghuni = $user->penghuni;

                        if (! $penghuni) {
                            return [];
                        }

                        // Ambil semua kamar yang aktif (log status IN)
                        return $penghuni->logs()
                            ->where('status', 'checkin')
                            ->with('kamar')
                            ->get()
                            ->pluck('kamar.nama', 'kamar.id'); // sesuaikan field
                    })
                    ->searchable(),
                    // ->required(),


                FileUpload::make('foto')
                    ->label('Foto Bukti')
                    ->image()
                    ->directory('ticketing/foto')
                    ->maxSize(2048)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('judul')->label('Judul'),
                    Tables\Columns\TextColumn::make('kategori')->label('Kategori'),
                    Tables\Columns\TextColumn::make('prioritas')->label('Prioritas'),
                    Tables\Columns\TextColumn::make('status')->label('Status')
                        ->badge()
                        ->color(fn (string $state) => match ($state) {
                            'Baru' => 'danger',
                            'Diproses' => 'warning',
                            'Selesai' => 'success',
                            'Ditolak' => 'gray',
                        }),
                    Tables\Columns\TextColumn::make('created_at')
                        ->label('Dibuat')
                        ->dateTime('d M Y H:i'),
                    Tables\Columns\TextColumn::make('user.penghuni.nama')
                        ->label('Nama Penghuni')
                        ->visible(fn () => auth()->user()?->hasRole('Admin')),
                        Tables\Columns\TextColumn::make('waiting_time')
                        ->label('Waiting')
                        ->state(function (Model $record) {
                            return now()->diffForHumans($record->created_at, [
                                'parts' => 2, // Biar bisa "1 day 3 hours"
                                'short' => true, // Lebih singkat formatnya
                                'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                            ]);
                        })
                        ->sortable()
                        ->icon('heroicon-m-clock'),

                ])
                ->actions([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Balas')
                        ->label('Balas')
                        ->icon('heroicon-m-chat-bubble-left-ellipsis')
                        ->visible(fn () => auth()->user()?->hasRole('Admin')),
                ])
                ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
