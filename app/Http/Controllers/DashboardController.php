<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'Guru') {
            $kelas = $user->kelasDibuat()->withCount('siswa')->get();
            $recentTugas = Tugas::whereHas('kelas', fn($q) => $q->where('guru_id', $user->id))->latest()->take(5)->get();
            return view('dashboard.guru', compact('kelas', 'recentTugas'));
        } else {
            $kelas = $user->kelas()->with('guru')->get();
            // tugas terbaru dari kelas yang diikuti, timeline 7 hari terakhir
            $tugasBaru = Tugas::whereIn('kelas_id', $kelas->pluck('id'))->where('created_at', '>=', now()->subDays(7))->latest()->get();
            // tugas yang mendekati deadline (3 hari)
            $tugasDeadline = Tugas::whereIn('kelas_id', $kelas->pluck('id'))->whereBetween('deadline', [now(), now()->addDays(3)])->get();
            return view('dashboard.siswa', compact('kelas', 'tugasBaru', 'tugasDeadline'));
        }
    }
}
