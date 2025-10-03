<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{

    protected $fillable = [
        'kelas_id',
        'judul',
        'deskripsi',
        'tipe_file',
        'path_file'
    ];
    protected $table = 'materi';

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
