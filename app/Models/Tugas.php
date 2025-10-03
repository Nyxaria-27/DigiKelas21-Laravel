<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $fillable = [
        'guru_id',
        'kelas_id',
        'judul',
        'deskripsi',
        'deadline',
        
    ];
    protected $table = 'tugas';

    protected $casts = [
        'deadline'   => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function pengumpulan()
    {
        return $this->hasMany(Pengumpulan::class);
    }

  
}
