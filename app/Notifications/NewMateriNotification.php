<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Materi;

class NewMateriNotification extends Notification
{
    use Queueable;
    public $materi;

    public function __construct(Materi $materi)
    {
        $this->materi = $materi;
    }

    public function via($notifiable)
    {
        return ['database'];
    }



    public function toArray($notifiable)
    {
        return [
            'type' => 'materi_baru',
            'materi_id' => $this->materi->id,
            'judul' => $this->materi->judul,
            'kelas' => $this->materi->kelas->nama_kelas,
            'message' => "Materi baru: {$this->materi->judul}"
        ];
    }
}
