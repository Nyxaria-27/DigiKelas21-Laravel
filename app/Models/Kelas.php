<?php

namespace App\Models;

use App\Models\User;
use App\Models\Tugas;
use App\Models\Materi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'creator_id',
        'nama_kelas',
        'kode_siswa',
        'kode_guru',
    ];

    protected $table = 'kelas';

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
    public function siswa()
    {
        return $this->belongsToMany(User::class, 'kelas_member', 'kelas_id', 'siswa_id')->withTimestamps();
    }


    protected $guarded = [];

    // creator (user who created the class)
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function guru()
    {
        return $this->belongsToMany(User::class, 'kelas_guru', 'kelas_id', 'guru_id');
    }
}
