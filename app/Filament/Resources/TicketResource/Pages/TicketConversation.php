<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class TicketConversation extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = TicketResource::class;
    protected static string $view = 'filament.resources.ticket-resource.pages.ticket-conversation';

    public ?Ticket $record = null;
    public ?array $data = [];

    public function mount($record): void
    {
        // Pastikan $record adalah instance Ticket, bukan string
        if (is_string($record)) {
            $this->record = Ticket::findOrFail($record);
        } else {
            $this->record = $record;
        }

        $this->form->fill();

        // Mark messages as read for current user
        $this->record->messages()
            ->where('user_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kirim Pesan Baru')
                    ->schema([
                        Textarea::make('message')
                            ->label('Pesan')
                            ->required()
                            ->rows(4)
                            ->placeholder('Ketik pesan Anda...'),

                        FileUpload::make('attachment')
                            ->label('Lampiran')
                            ->image()
                            ->directory('ticket-messages')
                            ->maxSize(2048)
                            ->nullable()
                            ->helperText('Upload gambar pendukung (maksimal 2MB)'),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function sendMessage(): void
    {
        $data = $this->form->getState();

        if (empty($data['message'])) {
            Notification::make()
                ->title('Pesan tidak boleh kosong')
                ->danger()
                ->send();
            return;
        }

        try {
            TicketMessage::create([
                'ticket_id' => $this->record->id,
                'user_id' => Auth::id(),
                'message' => $data['message'],
                'attachment' => $data['attachment'] ?? null,
            ]);

            // Update status ticket jika diperlukan
            if ($this->record->status === 'Baru') {
                $this->record->update(['status' => 'Diproses']);
            }

            // Reset form
            $this->form->fill([
                'message' => '',
                'attachment' => null,
            ]);

            Notification::make()
                ->title('Pesan berhasil dikirim')
                ->success()
                ->send();

            // Refresh the page to show new message
            $this->redirect(request()->header('Referer'));

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Gagal mengirim pesan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')),
        ];

        // Hanya admin yang bisa edit dan ubah status
        if (Auth::user()->hasRole(['Superadmin', 'Admin'])) {
            $actions[] = Action::make('edit')
                ->label('Edit Ticket')
                ->icon('heroicon-o-pencil')
                ->color('primary')
                ->url($this->getResource()::getUrl('edit', ['record' => $this->record]));

            $actions[] = Action::make('mark_resolved')
                ->label('Tandai Selesai')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Tandai Ticket Selesai')
                ->modalDescription('Apakah Anda yakin ingin menandai ticket ini sebagai selesai?')
                ->action(function () {
                    $this->record->update([
                        'status' => 'Selesai',
                        'tanggal_selesai' => now()->toDateString(),
                    ]);

                    Notification::make()
                        ->title('Ticket berhasil ditandai selesai')
                        ->success()
                        ->send();

                    return redirect()->to(TicketResource::getUrl('index'));
                })
                ->visible(fn() => $this->record->status !== 'Selesai');

            $actions[] = Action::make('reopen')
                ->label('Buka Kembali')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Buka Kembali Ticket')
                ->modalDescription('Apakah Anda yakin ingin membuka kembali ticket ini?')
                ->action(function () {
                    $this->record->update([
                        'status' => 'Diproses',
                        'tanggal_selesai' => null,
                    ]);

                    Notification::make()
                        ->title('Ticket berhasil dibuka kembali')
                        ->success()
                        ->send();
                })
                ->visible(fn() => $this->record->status === 'Selesai');
        }

        return $actions;
    }

    public function getTitle(): string
    {
        return 'Percakapan Ticket: ' . $this->record->judul;
    }

    public function getSubheading(): ?string
    {
        return 'Status: ' . $this->record->status . ' | Prioritas: ' . $this->record->prioritas;
    }
}
