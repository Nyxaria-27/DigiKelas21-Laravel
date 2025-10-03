<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Pengumpulan extends Model
{
    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'path_file_jawaban',
        'teks_jawaban',
        'nilai',
        'komentar',
        'tanggal_kumpul',
    ];
    protected $table = 'pengumpulan';

    protected $casts = [
        'tanggal_kumpul' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
