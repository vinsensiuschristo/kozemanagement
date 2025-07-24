<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use App\Models\Kamar;
use App\Models\Unit;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationItem;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Ticketing';
    protected static ?string $modelLabel = 'Ticket';
    protected static ?string $pluralModelLabel = 'Tickets';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Ticket')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('kategori')
                                    ->label('Kategori')
                                    ->options([
                                        'Kebocoran' => 'Kebocoran',
                                        'Listrik' => 'Listrik',
                                        'AC' => 'AC',
                                        'Furniture' => 'Furniture',
                                        'Kebersihan' => 'Kebersihan',
                                        'Keamanan' => 'Keamanan',
                                        'Lainnya' => 'Lainnya',
                                    ])
                                    ->required(),

                                Forms\Components\Select::make('prioritas')
                                    ->label('Prioritas')
                                    ->options([
                                        'Rendah' => 'Rendah',
                                        'Sedang' => 'Sedang',
                                        'Tinggi' => 'Tinggi',
                                        'Mendesak' => 'Mendesak',
                                    ])
                                    ->required(),
                            ]),

                        Forms\Components\TextInput::make('judul')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(4),

                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Bukti')
                            ->image()
                            ->directory('ticket-photos')
                            ->maxSize(2048)
                            ->nullable(),
                    ]),

                Forms\Components\Section::make('Detail Lokasi')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('unit_id')
                                    ->label('Unit')
                                    ->options(Unit::pluck('nama', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn(callable $set) => $set('kamar_id', null)),

                                Forms\Components\Select::make('kamar_id')
                                    ->label('Kamar')
                                    ->options(function (callable $get) {
                                        $unitId = $get('unit_id');
                                        if (!$unitId)
                                            return [];
                                        return Kamar::where('unit_id', $unitId)->pluck('nama', 'id');
                                    })
                                    ->searchable()
                                    ->required(),
                            ]),
                    ])
                    ->visible(fn() => Auth::user()->hasRole(['Superadmin', 'Admin'])),

                Forms\Components\Section::make('Status & Tanggal')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'Baru' => 'Baru',
                                        'Diproses' => 'Diproses',
                                        'Selesai' => 'Selesai',
                                        'Ditolak' => 'Ditolak',
                                    ])
                                    ->default('Baru')
                                    ->required(),

                                Forms\Components\DatePicker::make('tanggal_lapor')
                                    ->label('Tanggal Lapor')
                                    ->default(now())
                                    ->required(),

                                Forms\Components\DatePicker::make('tanggal_selesai')
                                    ->label('Tanggal Selesai')
                                    ->nullable(),
                            ]),

                        Forms\Components\Textarea::make('respon_admin')
                            ->label('Respon Admin')
                            ->rows(3)
                            ->nullable(),
                    ])
                    ->visible(fn() => Auth::user()->hasRole(['Superadmin', 'Admin'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'Baru',
                        'warning' => 'Diproses',
                        'success' => 'Selesai',
                        'secondary' => 'Ditolak',
                    ]),

                Tables\Columns\BadgeColumn::make('prioritas')
                    ->label('Prioritas')
                    ->colors([
                        'secondary' => 'Rendah',
                        'info' => 'Sedang',
                        'warning' => 'Tinggi',
                        'danger' => 'Mendesak',
                    ]),

                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelapor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kamar.nama')
                    ->label('Kamar')
                    ->searchable(),

                Tables\Columns\TextColumn::make('unread_messages_count')
                    ->label('Pesan Baru')
                    ->getStateUsing(function (Ticket $record) {
                        return $record->getUnreadMessagesCountForUser(Auth::id());
                    })
                    ->badge()
                    ->color(fn($state) => $state > 0 ? 'danger' : 'gray')
                    ->formatStateUsing(fn($state) => $state > 0 ? $state : ''),

                Tables\Columns\TextColumn::make('tanggal_lapor')
                    ->label('Tanggal Lapor')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Baru' => 'Baru',
                        'Diproses' => 'Diproses',
                        'Selesai' => 'Selesai',
                        'Ditolak' => 'Ditolak',
                    ]),
                Tables\Filters\SelectFilter::make('prioritas')
                    ->options([
                        'Rendah' => 'Rendah',
                        'Sedang' => 'Sedang',
                        'Tinggi' => 'Tinggi',
                        'Mendesak' => 'Mendesak',
                    ]),
                Tables\Filters\SelectFilter::make('kategori')
                    ->options([
                        'Kebocoran' => 'Kebocoran',
                        'Listrik' => 'Listrik',
                        'AC' => 'AC',
                        'Furniture' => 'Furniture',
                        'Kebersihan' => 'Kebersihan',
                        'Keamanan' => 'Keamanan',
                        'Lainnya' => 'Lainnya',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('conversation')
                    ->label('Chat')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('primary')
                    ->url(fn(Ticket $record): string => route('filament.admin.resources.tickets.conversation', $record)),

                Tables\Actions\EditAction::make()
                    ->visible(fn() => Auth::user()->hasRole(['Superadmin', 'Admin'])),

                Tables\Actions\Action::make('mark_resolved')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn(Ticket $record) => $record->update([
                        'status' => 'Selesai',
                        'tanggal_selesai' => now()->toDateString(),
                    ]))
                    ->visible(
                        fn(Ticket $record) =>
                        Auth::user()->hasRole(['Superadmin', 'Admin']) &&
                        $record->status !== 'Selesai'
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => Auth::user()->hasRole(['Superadmin', 'Admin'])),

                    Tables\Actions\BulkAction::make('mark_resolved')
                        ->label('Tandai Selesai')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn($records) => $records->each(fn($record) => $record->update([
                            'status' => 'Selesai',
                            'tanggal_selesai' => now()->toDateString(),
                        ])))
                        ->visible(fn() => Auth::user()->hasRole(['Superadmin', 'Admin'])),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (!Auth::user()->hasRole(['Superadmin', 'Admin'])) {
                    return $query->where('user_id', Auth::id());
                }
                return $query;
            })
            ->poll('30s');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
            'conversation' => Pages\TicketConversation::route('/{record}/conversation'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        if ($user->hasRole(['Superadmin', 'Admin'])) {
            // Untuk admin: hitung ticket baru + total pesan belum dibaca
            $newTickets = static::getModel()::where('status', 'Baru')->count();
            $unreadMessages = static::getModel()::whereHas('messages', function ($query) use ($user) {
                $query->where('user_id', '!=', $user->id)
                    ->whereNull('read_at');
            })->count();

            $total = $newTickets + $unreadMessages;
            return $total > 0 ? (string) $total : null;
        } else {
            // Untuk user: hitung pesan belum dibaca dari admin
            $unreadMessages = static::getModel()::where('user_id', $user->id)
                ->whereHas('messages', function ($query) use ($user) {
                    $query->where('user_id', '!=', $user->id)
                        ->whereNull('read_at');
                })->count();

            return $unreadMessages > 0 ? (string) $unreadMessages : null;
        }
    }

    public static function canCreate(): bool
    {
        return !Auth::user()->hasRole(['Superadmin', 'Admin']);
    }
}
