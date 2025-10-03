<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Pengumpulan;

class PengumpulanSubmittedNotification extends Notification
{
    use Queueable;
    public $pengumpulan;

    public function __construct(Pengumpulan $pengumpulan)
    {
        $this->pengumpulan = $pengumpulan;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    

    public function toArray($notifiable)
    {
        return [
            'type' => 'pengumpulan_baru',
            'pengumpulan_id' => $this->pengumpulan->id,
            'tugas_id' => $this->pengumpulan->tugas->id,
            'siswa' => $this->pengumpulan->siswa->nama ?? $this->pengumpulan->siswa->nama,
            'message' => "Pengumpulan oleh {$this->pengumpulan->siswa->nama }"
        ];
    }
}
