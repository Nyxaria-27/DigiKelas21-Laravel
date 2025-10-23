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
            $kelas = $user->kelasMengajar()->paginate(10);
        } else {
            $kelas = $user->kelasDikuti()->paginate(10);
        }
        return view('kelas.index', compact('kelas'));
    }


    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $r)
    {
        $r->validate(['nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas']);

        // generate codes
        do {
            $kodeS = strtoupper(Str::random(6));
        } while (Kelas::where('kode_siswa', $kodeS)->exists());
        do {
            $kodeG = strtoupper(Str::random(6));
        } while (Kelas::where('kode_guru', $kodeG)->exists());

        $kelas = Kelas::create([
            'nama_kelas' => $r->nama_kelas,
            'kode_siswa' => $kodeS,
            'kode_guru' => $kodeG,
            'creator_id' => auth()->id(),
        ]);

        // jika pembuat adalah guru -> attach as guru pengajar
        if (auth()->user()->isGuru()) {
            $kelas->guru()->attach(auth()->id());
        }

        return redirect()->route('kelas.show', $kelas)->with('success', 'Kelas dibuat. Kode siswa: ' . $kodeS . ' Kode guru: ' . $kodeG);
    }


    public function show(Kelas $kelas)
    {
        $user = auth()->user();
        // Guru may view if they are teacher on that class OR Admin
        if ($user->role === 'Guru' && ! $user->kelasMengajar->contains($kelas->id) && ! $user->isAdmin()) {
            abort(403);
        }
        // Siswa must be member or Admin
        if ($user->role === 'Siswa' && ! $user->kelasDikuti->contains($kelas->id) && ! $user->isAdmin()) {
            abort(403);
        }
        // else Admin can view all
        $materi = $kelas->materi()->latest()->get();
        $tugas = $kelas->tugas()->latest()->get();
        $guru = $kelas->guru()->get();
        $siswa = $kelas->siswa()->get();

        return view('kelas.show', compact('kelas', 'materi', 'tugas', 'guru', 'siswa'));
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

        return redirect()->route('kelas.show', $kelas)->with('success', 'Kelas diperbarui');
    }

    public function destroy(Kelas $kelas)
    {
        $this->authorizeGuruOwns($kelas);
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas dihapus');
    }

    // siswa join (existing)
    public function joinSiswa(Request $request)
    {
        $request->validate(['kode_siswa' => 'required|string']);
        $kode = strtoupper($request->kode_siswa);
        $kelas = Kelas::where('kode_siswa', $kode)->first();
        if (! $kelas) return back()->withErrors('Kode kelas siswa tidak ditemukan.');

        $user = auth()->user();
        if ($user->role !== 'Siswa') return back()->withErrors('Hanya siswa yang dapat menggunakan kode ini.');

        if ($user->kelasDikuti()->where('kelas_id', $kelas->id)->exists()) {
            return back()->with('info', 'Kamu sudah tergabung di kelas ini.');
        }

        $user->kelasDikuti()->attach($kelas->id, ['joined_at' => now()]);
        return redirect()->route('kelas.show', $kelas)->with('success', 'Berhasil bergabung sebagai siswa.');
    }

    // guru join
    public function joinGuru(Request $request)
    {
        $request->validate(['kode_guru' => 'required|string']);
        $kode = strtoupper($request->kode_guru);
        $kelas = Kelas::where('kode_guru', $kode)->first();
        if (! $kelas) return back()->withErrors('Kode kelas guru tidak ditemukan.');

        $user = auth()->user();
        if ($user->role !== 'Guru') return back()->withErrors('Hanya guru yang dapat menggunakan kode ini.');

        if ($user->kelasMengajar()->where('kelas_id', $kelas->id)->exists()) {
            return back()->with('info', 'Kamu sudah terdaftar sebagai guru pengajar di kelas ini.');
        }

        $user->kelasMengajar()->attach($kelas->id);
        return redirect()->route('kelas.show', $kelas)->with('success', 'Berhasil tergabung sebagai guru pengajar.');
    }


    private function authorizeGuruOwns(Kelas $kelas)
    {
        $user = auth()->user();
        if ($user->role !== 'Guru' || $kelas->creator_id !== $user->id) {
            abort(403);
        }
    }
}
