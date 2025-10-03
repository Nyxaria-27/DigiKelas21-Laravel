<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Pengumpulan;

class PengumpulanDinilaiNotification extends Notification
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
            'type' => 'pengumpulan_dinilai',
            'pengumpulan_id' => $this->pengumpulan->id,
            'tugas_id' => $this->pengumpulan->tugas->id,
            'nilai' => $this->pengumpulan->nilai,
            'message' => "Nilai: {$this->pengumpulan->nilai} untuk {$this->pengumpulan->tugas->judul}"
        ];
    }
}
