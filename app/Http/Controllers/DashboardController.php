<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
  public function index()
{
      $user = auth()->user();
    if ($user->role === 'Guru') {
        $kelas = $user->kelasMengajar()->withCount('siswa')->get(); // kumpulan kelas yang dia ajar
        $recentTugas = \App\Models\Tugas::whereHas('kelas', function($q) use ($user) {
            $q->whereExists(function($qq) use ($user) {
                $qq->select(DB::raw(1))
                   ->from('kelas_guru')
                   ->whereColumn('kelas_guru.kelas_id','kelas.id')
                   ->where('kelas_guru.guru_id', $user->id);
            });
        })->latest()->take(5)->get();
        return view('dashboard.guru', compact('kelas','recentTugas'));
    } else {
        // Untuk siswa
        $kelas = $user->kelasDikuti()->with('guru')->get(); // nama relasi kamu 'kelasDikuti'

        $tugasBaru = Tugas::whereIn('kelas_id', $kelas->pluck('id'))
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->get();

        $tugasDeadline = Tugas::whereIn('kelas_id', $kelas->pluck('id'))
            ->whereBetween('deadline', [now(), now()->addDays(3)])
            ->get();

        return view('dashboard.siswa', compact('kelas', 'tugasBaru', 'tugasDeadline'));
    }
}


}
