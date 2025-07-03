<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\Unit;
use App\Models\Kamar;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class UserTicketingWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.user-ticketing';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];
    public $showCreateForm = false;

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->hasRole('User');
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('unit_id')
                    ->label('Unit Kos')
                    ->options(Unit::pluck('nama_cluster', 'id'))
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('kamar_id', null)),

                Select::make('kamar_id')
                    ->label('Kamar (Opsional)')
                    ->options(function (callable $get) {
                        $unitId = $get('unit_id');
                        if (!$unitId) return [];
                        return Kamar::where('unit_id', $unitId)->pluck('nama', 'id');
                    })
                    ->searchable(),

                TextInput::make('judul')
                    ->label('Judul Pengaduan')
                    ->required()
                    ->maxLength(255),

                Select::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'fasilitas' => 'Fasilitas Rusak',
                        'kebersihan' => 'Kebersihan',
                        'keamanan' => 'Keamanan',
                        'listrik' => 'Listrik',
                        'air' => 'Air',
                        'internet' => 'Internet/WiFi',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required(),

                Select::make('prioritas')
                    ->label('Prioritas')
                    ->options([
                        'low' => 'Rendah',
                        'medium' => 'Sedang',
                        'high' => 'Tinggi',
                        'urgent' => 'Mendesak',
                    ])
                    ->default('medium')
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Detail')
                    ->required()
                    ->rows(4),

                FileUpload::make('foto')
                    ->label('Foto Pendukung (Opsional)')
                    ->image()
                    ->maxSize(5120)
                    ->directory('tickets'),
            ])
            ->statePath('data');
    }

    protected function getViewData(): array
    {
        $user = Auth::user();
        
        // Ambil tickets user
        $tickets = Ticket::where('user_id', $user->id)
            ->with(['unit', 'kamar'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Stats
        $stats = [
            'total' => Ticket::where('user_id', $user->id)->count(),
            'open' => Ticket::where('user_id', $user->id)->where('status', 'open')->count(),
            'in_progress' => Ticket::where('user_id', $user->id)->where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('user_id', $user->id)->where('status', 'resolved')->count(),
        ];

        return [
            'tickets' => $tickets,
            'stats' => $stats,
        ];
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        if (!$this->showCreateForm) {
            $this->form->fill();
        }
    }

    public function create()
    {
        try {
            $data = $this->form->getState();
            
            $data['user_id'] = Auth::id();
            $data['status'] = 'open';
            $data['tanggal_lapor'] = now();

            Ticket::create($data);

            Notification::make()
                ->title('Ticket Berhasil Dibuat')
                ->body('Pengaduan Anda telah diterima dan akan segera ditindaklanjuti.')
                ->success()
                ->send();

            $this->showCreateForm = false;
            $this->form->fill();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Gagal membuat ticket: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
