<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'nama' => 'Guru Demo',
            'email' => 'guru.demo@smk21.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'Guru'
        ]);
        User::factory()->create([
            'nama' => 'Siswa Demo',
            'email' => 'siswa.demo@smk21.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'Siswa'
        ]);
    }
}
