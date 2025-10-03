<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'Guru') {
            $kelas = Kelas::where('guru_id', $user->id)->latest()->paginate(10);
        } else {
            // siswa
            $kelas = $user->kelas()->latest()->paginate(10);
        }
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
        ]);

        // generate kode unik, cek collision
        do {
            $kode = strtoupper(Str::random(6));
        } while (Kelas::where('kode_kelas', $kode)->exists());

        $kelas = Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'kode_kelas' => $kode,
            'guru_id' => auth()->id(),
        ]);

        return redirect()->route('kelas.show', $kelas)->with('success', 'Kelas berhasil dibuat. Kode: '.$kode);
    }

    public function show(Kelas $kelas)
    {
        // Pastikan user boleh melihat:
        $user = auth()->user();
        if ($user->role === 'Guru' && $kelas->guru_id !== $user->id) {
            abort(403);
        }
        if ($user->role === 'Siswa' && ! $user->kelas->contains($kelas->id)) {
            abort(403);
        }

        $materi = $kelas->materi()->latest()->get();
        $tugas = $kelas->tugas()->latest()->get();
        $siswa = $kelas->siswa()->get();

        return view('kelas.show', compact('kelas','materi','tugas','siswa'));
    }

    public function edit(Kelas $kelas)
    {
        $this->authorizeGuruOwns($kelas);
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $this->authorizeGuruOwns($kelas);

        $request->validate(['nama_kelas' => 'required|string|max:255']);
        $kelas->update(['nama_kelas' => $request->nama_kelas]);

        return redirect()->route('kelas.show', $kelas)->with('success','Kelas diperbarui');
    }

    public function destroy(Kelas $kelas)
    {
        $this->authorizeGuruOwns($kelas);
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success','Kelas dihapus');
    }

    // siswa join kelas via kode
    public function join(Request $request)
    {
        $request->validate(['kode_kelas'=>'required|string']);
        $kode = strtoupper($request->kode_kelas);
        $kelas = Kelas::where('kode_kelas', $kode)->first();

        if (! $kelas) {
            return back()->withErrors(['kode_kelas'=>'Kode kelas tidak ditemukan']);
        }

        $user = auth()->user();
        if ($user->role !== 'Siswa') {
            abort(403);
        }

        // attach jika belum tergabung
        if ($user->kelas()->where('kelas_id',$kelas->id)->exists()) {
            return back()->with('info','Kamu sudah tergabung di kelas ini');
        }
        $user->kelas()->attach($kelas->id, ['joined_at' => now()]);
        return redirect()->route('kelas.show', $kelas)->with('success','Berhasil bergabung ke kelas');
    }

    private function authorizeGuruOwns(Kelas $kelas)
    {
        $user = auth()->user();
        if ($user->role !== 'Guru' || $kelas->guru_id !== $user->id) {
            abort(403);
        }
    }
}

