<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Penghuni;
use App\Mail\PenghuniBirthdayGreeting;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendPenghuniBirthdayEmails extends Command
{
    protected $signature = 'email:penghuni-birthday';
    protected $description = 'Send birthday greeting emails to penghuni';

    public function handle()
    {
        $today = Carbon::today();
        
        $penghunis = Penghuni::whereMonth('tanggal_lahir', $today->month)
                        ->whereDay('tanggal_lahir', $today->day)
                        ->whereNotNull('email')
                        ->get();

        foreach ($penghunis as $penghuni) {
            Mail::to($penghuni->email)->send(new PenghuniBirthdayGreeting($penghuni));
            $this->info("Birthday email sent to penghuni: {$penghuni->nama} ({$penghuni->email})");
        }

        $this->info('Penghuni birthday emails sent successfully!');
    }
}