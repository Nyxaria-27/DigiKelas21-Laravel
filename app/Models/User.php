<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function kelasDibuat()
    { // untuk guru
        return $this->hasMany(Kelas::class, 'guru_id');
    }
    public function tugas()
    { // untuk guru
        return $this->hasMany(Tugas::class, 'guru_id');
    }
    public function kelas()
    { // kelas yang diikuti (pivot)
        return $this->belongsToMany(Kelas::class, 'kelas_member', 'siswa_id', 'kelas_id')->withTimestamps();
    }
    public function pengumpulan()
    {
        return $this->hasMany(Pengumpulan::class, 'siswa_id');
    }
}
