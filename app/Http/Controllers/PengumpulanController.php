<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Pengumpulan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PengumpulanDinilaiNotification;
use App\Notifications\PengumpulanSubmittedNotification;

class PengumpulanController extends Controller
{
    // siswa: list pengumpulan miliknya
    public function index()
    {
        $user = auth()->user();
        if ($user->role !== 'Siswa') abort(403);
        $pengumpulan = $user->pengumpulan()->with('tugas.kelas')->latest()->paginate(10);
        return view('pengumpulan.index', compact('pengumpulan'));
    }

    // show form create (submit) for a tugas
    public function create(Request $request)
    {
        $tugasId = $request->query('tugas_id');
        $tugas = Tugas::findOrFail($tugasId);

        // siswa harus tergabung di kelas
        $user = auth()->user();
        if ($user->role !== 'Siswa' || ! $user->kelas->contains($tugas->kelas_id)) abort(403);

        // opsional: jika deadline exist, masih boleh submit? kita izinkan tapi simpan tanggal_kumpul -> late flag bisa diproses di view.
        return view('pengumpulan.create', compact('tugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'teks_jawaban' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:15360'
        ]);

        $tugas = Tugas::findOrFail($request->tugas_id);
        $user = auth()->user();

        // pastikan siswa tergabung dsb (cek yg sama seperti sebelumnya)

        // cari existing submission
        $existing = Pengumpulan::where('tugas_id', $tugas->id)
            ->where('siswa_id', $user->id)
            ->first();

        $path = $existing ? $existing->path_file_jawaban : null;
        if ($request->hasFile('file')) {
            // delete old file jika mengganti (opsional)
            if ($existing && $existing->path_file_jawaban && Storage::disk('public')->exists($existing->path_file_jawaban)) {
                Storage::disk('public')->delete($existing->path_file_jawaban);
            }
            $path = $request->file('file')->store('jawaban', 'public');
        }

        if ($existing) {
            // jika sudah dinilai, tolak perubahan (opsional). Atau izinkan update jika belum dinilai.
            if ($existing->nilai !== null) {
                return back()->withErrors('Tidak dapat mengubah pengumpulan karena sudah dinilai.');
            }

            $existing->update([
                'path_file_jawaban' => $path,
                'teks_jawaban' => $request->teks_jawaban,
                'tanggal_kumpul' => now(),
            ]);

            $pengumpulan = $existing;
        } else {
            $pengumpulan = Pengumpulan::create([
                'tugas_id' => $tugas->id,
                'siswa_id' => $user->id,
                'path_file_jawaban' => $path,
                'teks_jawaban' => $request->teks_jawaban,
                'tanggal_kumpul' => now(),
            ]);
        }

        $guru = $pengumpulan->tugas->kelas->guru;
        $guru->notify(new PengumpulanSubmittedNotification($pengumpulan));

        return redirect()->route('pengumpulan.show', $pengumpulan)->with('success', 'Tugas berhasil dikumpulkan');
    }


    public function show(Pengumpulan $pengumpulan)
    {
        $user = auth()->user();
        // siswa melihat miliknya, guru melihat jika pemilik kelas
        if ($user->role === 'Siswa' && $pengumpulan->siswa_id !== $user->id) abort(403);
        if ($user->role === 'Guru' && $pengumpulan->tugas->kelas->creator_id !== $user->id) abort(403);

        return view('pengumpulan.show', compact('pengumpulan'));
    }

    // ---------- Penilaian oleh guru ----------
    public function editNilai(Pengumpulan $pengumpulan)
    {
        if (auth()->id() !== $pengumpulan->tugas->kelas->creator_id) abort(403);
        return view('pengumpulan.edit_nilai', compact('pengumpulan'));
    }

    public function updateNilai(Request $request, Pengumpulan $pengumpulan)
    {
        if (auth()->id() !== $pengumpulan->tugas->kelas->creator_id) abort(403);

        $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'komentar' => 'nullable|string',
        ]);

        $pengumpulan->update([
            'nilai' => $request->nilai,
            'komentar' => $request->komentar,
        ]);

        $nilai = $request->nilai;
        $komentar = $request->komentar;

        $pengumpulan->update(['nilai' => $nilai, 'komentar' => $komentar]);
        $pengumpulan->siswa->notify(new PengumpulanDinilaiNotification($pengumpulan));

        return redirect()->route('tugas.show', $pengumpulan->tugas)->with('success', 'Nilai disimpan');
    }

    // (opsional) delete pengumpulan by siswa before deadline
    public function destroy(Pengumpulan $pengumpulan)
    {
        $user = auth()->user();
        if ($user->role === 'Siswa' && $pengumpulan->siswa_id === $user->id) {
            // allow delete only if not graded and before deadline (optional)
            if ($pengumpulan->nilai !== null) {
                return back()->withErrors('Tidak dapat menghapus karena sudah dinilai');
            }
            // delete file
            if ($pengumpulan->path_file_jawaban && Storage::disk('public')->exists($pengumpulan->path_file_jawaban)) {
                Storage::disk('public')->delete($pengumpulan->path_file_jawaban);
            }
            $pengumpulan->delete();
            return redirect()->route('pengumpulan.index')->with('success', 'Pengumpulan dihapus');
        }
        abort(403);
    }

    /**
     * Tampilkan daftar pengumpulan untuk sebuah tugas (hanya Guru pemilik kelas / Admin)
     */
    public function listByTugas(Tugas $tugas)
    {
        $user = auth()->user();

        // Autentikasi: hanya guru pemilik kelas atau admin (opsional)
        if ($user->role === 'Guru' && $tugas->kelas->creator_id !== $user->id) {
            abort(403, 'Anda tidak punya akses ke pengumpulan tugas ini.');
        }

        // ambil pengumpulan, relasikan siswa, pagination
        $pengumpulan = $tugas->pengumpulan()
            ->with('siswa') // pastikan relasi pengumpulan->siswa ada
            ->orderByDesc('tanggal_kumpul') // atau created_at
            ->paginate(12);

        return view('pengumpulan.list_by_tugas', compact('tugas', 'pengumpulan'));
    }
}
