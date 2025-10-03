<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Tugas;

class NewTugasNotification extends Notification
{
    use Queueable;

    public $tugas;

    public function __construct(Tugas $tugas)
    {
        $this->tugas = $tugas;
    }

    // Hanya simpan ke database — tidak kirim email
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'tugas_baru',
            'tugas_id' => $this->tugas->id,
            'judul' => $this->tugas->judul,
            'kelas' => $this->tugas->kelas->nama_kelas,
            'message' => "Tugas baru: {$this->tugas->judul}"
        ];
    }
}
