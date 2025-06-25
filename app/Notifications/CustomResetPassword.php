<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Facades\Filament;

class CustomResetPassword extends Notification
{
    use Queueable;

    public function __construct(public string $token) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = filament()->getResetPasswordUrl($this->token, $notifiable);

        return (new MailMessage)
            ->subject('Permintaan Reset Password')
            ->greeting('Halo!')
            ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.')
            ->action('Reset Password', $url)
            ->line('Link ini akan kadaluarsa dalam 60 menit.')
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.');
    }

    protected function resetUrl(mixed $notifiable): string
    {
        return Filament::getResetPasswordUrl($this->token, $notifiable);
    }
}
