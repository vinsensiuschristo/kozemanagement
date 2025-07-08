<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Penghuni;

class PenghuniBirthdayGreeting extends Mailable
{
    use Queueable, SerializesModels;

    public $penghuni;

    public function __construct(Penghuni $penghuni)
    {
        $this->penghuni = $penghuni;
    }

    public function build()
    {
        return $this->subject('Selamat Ulang Tahun Penghuni!')
                    ->view('emails.penghuni_birthday');
    }
}