<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Notifications\NewTugasNotification;
use Illuminate\Support\Facades\Notification;

class TugasController extends Controller
{
    public function create(Kelas $kelas)
    {
        if (auth()->id() !== $kelas->creator_id) abort(403);
        return view('tugas.create', compact('kelas'));
    }

    public function store(Request $request, Kelas $kelas)
    {
        if (auth()->id() !== $kelas->creator_id) abort(403);
        // assume $kelas injected
        if (auth()->user()->isGuru()) {
            if (! auth()->user()->kelasMengajar->contains($kelas->id)) {
                abort(403, 'Anda bukan pengajar di kelas ini.');
            }
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'nullable|date'
        ]);

        $tugas = Tugas::create([
            'kelas_id' => $kelas->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline ? date('Y-m-d H:i:s', strtotime($request->deadline)) : null,
            'guru_id' => auth()->id(),
        ]);

        // kirim ke semua siswa kelas
        $users = $kelas->siswa()->get(); // koleksi user
        Notification::send($users, new NewTugasNotification($tugas));

        //return
        return redirect()->route('kelas.show', $kelas)->with('success', 'Tugas dibuat');
    }

    public function show(Tugas $tugas)
    {
        $user = auth()->user();
        $kelas = $tugas->kelas;
        if ($user->role === 'Guru' && $kelas->creator_id !== $user->id) abort(403);
        if ($user->role === 'Siswa' && ! $user->kelas->contains($kelas->id)) abort(403);

        // jika guru, tampilkan juga pengumpulan siswa
        $pengumpulan = null;
        $pengumpulanSaya = null;
        if ($user->role === 'Siswa') {
            $pengumpulanSaya = \App\Models\Pengumpulan::where('tugas_id', $tugas->id)
                ->where('siswa_id', auth()->id())
                ->first();
        } elseif ($user->role === 'Guru') {

            $pengumpulan = $tugas->pengumpulan()->with('siswa')->get();
        }
        return view('tugas.show', compact('tugas', 'pengumpulan', 'pengumpulanSaya'));
    }

    public function edit(Tugas $tugas)
    {
        $kelas = $tugas->kelas;
        if (auth()->id() !== $kelas->creator_id) abort(403);
        return view('tugas.edit', compact('tugas'));
    }

    public function update(Request $request, Tugas $tugas)
    {
        $kelas = $tugas->kelas;
        if (auth()->id() !== $kelas->creator_id) abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'nullable|date'
        ]);

        $tugas->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline ? date('Y-m-d H:i:s', strtotime($request->deadline)) : null,
        ]);

        return redirect()->route('tugas.show', $tugas)->with('success', 'Tugas diperbarui');
    }

    public function destroy(Tugas $tugas)
    {
        $kelas = $tugas->kelas;
        if (auth()->id() !== $kelas->guru_id) abort(403);

        $tugas->delete();
        return back()->with('success', 'Tugas dihapus');
    }
}
